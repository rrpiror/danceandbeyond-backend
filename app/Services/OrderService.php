<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\AddressRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderStatusRepository;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SellerOrderRepository;
use App\Services\PaymentService;
use App\Repositories\UserAddressRepository;
use App\Repositories\PayoutTransactionRepository;
use App\Models\StripeIntent;

/**
 * OrderService handles all order-related business logic including creation, updates, and retrieval of orders.
 * It manages the relationship between orders, seller orders, and order items while handling payment processing.
 */
class OrderService
{
    protected OrderRepository $orderRepository;
    protected ProductRepository $productRepository;
    protected UserAddressRepository $userAddressRepository;
    protected AddressRepository $addressRepository;
    protected OrderItemRepository $orderItemRepository;
    protected PaymentMethodRepository $paymentMethodRepository;
    protected PaymentService $paymentService;
    protected SellerOrderRepository $sellerOrderRepository;
    protected OrderStatusRepository $orderStatusRepository;
    protected PayoutTransactionRepository $payoutTransactionRepository;

    public function __construct(OrderRepository $orderRepository, OrderItemRepository $orderItemRepository, ProductRepository $productRepository, UserAddressRepository $userAddressRepository, AddressRepository $addressRepository, PaymentMethodRepository $paymentMethodRepository, PaymentService $paymentService, SellerOrderRepository $sellerOrderRepository, OrderStatusRepository $orderStatusRepository, PayoutTransactionRepository $payoutTransactionRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->productRepository = $productRepository;
        $this->userAddressRepository = $userAddressRepository;
        $this->addressRepository = $addressRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->paymentService = $paymentService;
        $this->sellerOrderRepository = $sellerOrderRepository;
        $this->orderStatusRepository = $orderStatusRepository;
        $this->payoutTransactionRepository = $payoutTransactionRepository;
    }

    /**
     * Get all available payment methods
     * @return Collection
     */
    public function getAllPaymentMethods()
    {
        return $this->paymentMethodRepository->getAll();
    }

    /**
     * Get all orders with their related seller orders and order items
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAll($page = 1, $perPage = 15, $filters = [])
    {
        return $this->orderRepository->getAll($page, $perPage, $filters);
    }

    public function findById($id)
    {
        return $this->orderRepository->findById($id);
    }

    /**
     * Update the status of a seller order
     * @param array $data Contains seller_order_id and status
     * @return SellerOrder
     * @throws Exception
     */
    public function update(array $data)
    {
        $sellerOrder = $this->sellerOrderRepository->findById($data['seller_order_id']);

        if (!$sellerOrder) {
            throw new Exception('Seller order not found.', 404);
        }

        $status = $this->orderStatusRepository->getStatusByName($data['status']);

        if (!$status) {
            throw new Exception('Status not found.', 404);
        }

        $sellerOrder->statuses()->attach($status->id);

        return $sellerOrder->load('statuses');
    }

    /**
     * Create a new order with multiple items from different sellers
     * This method handles:
     * 1. Order creation
     * 2. Seller order creation for each seller
     * 3. Order items creation
     * 4. Payment intent creation
     * 
     * @param array $data Order details including items, addresses, and payment info
     * @return array Payment intent details
     * @throws Exception
     */
    public function create(array $data)
    {
        try {
            DB::beginTransaction();

            // Get authenticated user ID
            $userId = Auth::id();
            $data['user_id'] = $userId;
            
            $data['payment_method_id'] = 1; // Stripe is the default payment method

            // Fetch all products involved in the order
            $productIds = collect($data['items'])->pluck('product_id')->toArray();
            $products = $this->productRepository->findByIds($productIds)->keyBy('id');

            // Store billing and shipping addresses
            $billingAddress = $this->addressRepository->findById($data['billing_address_id']);
            $shippingAddress = $this->addressRepository->findById($data['shipping_address_id']);
            if($billingAddress) {
                $data['addresses']['billing'] = $billingAddress;
            }
            if($shippingAddress) {
                $data['addresses']['shipping'] = $shippingAddress;
            }
            $data['addresses'] = json_encode($data['addresses']);

            // Calculate total amount and prepare items for payment
            $itemsCollection = collect($data['items']);
            $itemsArray = [];
            $errors = [];

            foreach ($itemsCollection as $item) {
                $product = $products[$item['product_id']];

                // For hire products, validate hiring_days
                if ($product->type === 'hire') {
                    $hiringDays = $item['hiring_days'] ?? null;
                    if (!$hiringDays || $hiringDays < $product->hiringDetail->min_hire_days) {
                        $errors[] = "Product {$product->name} requires minimum {$product->hiringDetail->min_hire_days} hire days";
                    }
                }

                // Validate stock availability for sizes
                if (!empty($item['sizes']) && is_array($item['sizes'])) {
                    foreach ($item['sizes'] as $size) {
                        if (isset($size['id']) && isset($size['quantity'])) {
                            $this->validateProductSizeStock($product->id, $size['id'], $size['quantity'], $product->name);
                        }
                    }
                } elseif (!empty($item['size_id'])) {
                    $this->validateProductSizeStock($product->id, $item['size_id'], $item['quantity'], $product->name);
                }

                $itemsArray[] = [
                    'quantity' => $item['quantity'],
                    'price' => $product->price * 100,
                    'name' => $product->name,
                    'product_id' => $item['product_id']
                ];

                if (!empty($errors)) {
                    throw new Exception(implode(', ', $errors), 422);
                }
            }

            $data['amount'] = $itemsCollection->sum(function ($item) use ($products) {
                return $this->calculateItemPrice($item, $products[$item['product_id']]);
            });

            // Create the main order
            $order = $this->orderRepository->create($data);
            // Group items by seller and create separate seller orders
            $sellerItems = $itemsCollection->groupBy(function ($item) use ($products) {
                return $products[$item['product_id']]->user_id;
            });

            foreach ($sellerItems as $sellerId => $sellerProducts) {
                $sellerOrder = $this->createSellerOrder($order->id, $sellerId, $sellerProducts, $products);
                $this->createOrderItems($sellerOrder, $sellerProducts, $products);
            }

            // Create payment intent for the order
            $paymentIntentData = $this->paymentService->createStripePaymentIntent(
                $order->toArray(),
                $itemsArray
            );

            // Store the stripe intent in the database
            $stripeIntent = StripeIntent::create([
                'order_id' => $order->id,
                'payment_intent_id' => $paymentIntentData['payment_intent_id'],
                'client_secret' => $paymentIntentData['client_secret'],
                'amount' => $data['amount'], // Store amount in regular currency format (to match orders table)
                'currency' => $paymentIntentData['currency'] ?? 'gbp',
                'status' => 'requires_payment_method', // Default status for new payment intents
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $userId,
                ],
            ]);

            DB::commit();

            return [
                'order' => $order->load('stripeIntent'),
                'client_secret' => $paymentIntentData['client_secret'],
                'payment_intent_id' => $paymentIntentData['payment_intent_id'],
                'amount' => $paymentIntentData['amount'],
                'currency' => $paymentIntentData['currency'],
                // Keep backward compatibility
                'stripe_url' => null, // We'll handle this in Flutter now
            ];
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage(), 422);
        }
    }

    /**
     * Create a seller order for a specific seller
     * @param int $orderId Main order ID
     * @param int $sellerId Seller's user ID
     * @param Collection $items Items belonging to this seller
     * @param Collection $products All products involved in the order
     * @return SellerOrder
     */
    private function createSellerOrder($orderId, $sellerId, $items, $products)
    {
        // Calculate total amount for this seller's items
        $totalAmount = collect($items)->sum(function ($item) use ($products) {
            return $this->calculateItemPrice($item, $products[$item['product_id']]);
        });

        // Create seller order and attach initial pending status
        $sellerOrder = $this->sellerOrderRepository->create([
            'order_id' => $orderId,
            'seller_id' => $sellerId,
            'amount' => $totalAmount
        ]);

        $pendingStatus = $this->orderStatusRepository->findById(env('ORDER_STATUS_PAYMENT_PENDING_ID'));
        $sellerOrder->statuses()->attach($pendingStatus->id);

        return $sellerOrder;
    }

    /**
     * Create order items for a seller order
     * @param SellerOrder $sellerOrder The seller order to create items for
     * @param Collection $items Items to create
     * @param Collection $products All products involved in the order
     * @throws Exception
     */
    private function createOrderItems($sellerOrder, $items, $products)
    {
        foreach ($items as $item) {
            $product = $products[$item['product_id']];

            $product_snapshot = $this->createProductSnapshot($item, $product);

            // Create order item with product details
            $orderItem = $this->orderItemRepository->create([
                'seller_order_id' => $sellerOrder->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'product_snapshot' => json_encode($product_snapshot)
            ]);

            // If it's a hire product, create hiring detail
            if ($product->type === 'hire' && isset($item['hiring_days'])) {
                $orderItem->hiringDetail()->create([
                    'hiring_days' => $item['hiring_days']
                ]);
            }

            // Process sizes array with quantities and reduce stock
            if (!empty($item['sizes']) && is_array($item['sizes'])) {
                $sizesData = [];
                foreach ($item['sizes'] as $size) {
                    if (isset($size['id']) && isset($size['quantity'])) {
                        $sizesData[$size['id']] = ['quantity' => $size['quantity']];
                        
                        // Reduce the product size quantity in stock
                        $this->reduceProductSizeQuantity($product->id, $size['id'], $size['quantity']);
                    }
                }
                if (!empty($sizesData)) {
                    $orderItem->sizes()->sync($sizesData);
                }
            } elseif (!empty($item['size_id'])) {
                // Fallback: if no sizes array but size_id exists, use item quantity
                $orderItem->sizes()->sync([
                    $item['size_id'] => ['quantity' => $item['quantity']]
                ]);
                
                // Reduce the product size quantity in stock
                $this->reduceProductSizeQuantity($product->id, $item['size_id'], $item['quantity']);
            }
        }
    }

    private function createProductSnapshot($item, $product)
    {
        $isHire = $product->type === 'hire';
        $sizes = $item['sizes'] ?? [];
        $colour = $item['colour'] ?? null;
        
        $product_snapshot = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'type' => $product->type,
            'media' => $product->media->map(function ($media) {
                return [
                    'id' => $media->id,
                    'model_type' => $media->model_type,
                    'model_id' => $media->model_id,
                    'uuid' => $media->uuid,
                    'collection_name' => $media->collection_name,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'disk' => $media->disk,
                    'conversions_disk' => $media->conversions_disk,
                    'size' => $media->size,
                    'manipulations' => $media->manipulations,
                    'custom_properties' => $media->custom_properties,
                    'generated_conversions' => $media->generated_conversions,
                    'responsive_images' => $media->responsive_images,
                    'order_column' => $media->order_column,
                    'created_at' => $media->created_at,
                    'updated_at' => $media->updated_at,
                    'original_url' => $media->getUrl(),
                    'preview_url' => $media->getUrl(),
                ];
            })->toArray(),
            'brand' => [
                'id' => $product->brand_id,
                'name' => $product->brand ? $product->brand->name : null,
            ],
            'category' => [
                'id' => $product->category_id,
                'name' => $product->category ? $product->category->name : null,
            ],
            'condition' => [
                'id' => $product->condition_id,
                'name' => $product->condition ? $product->condition->name : null,
            ],
        ];

        if ($isHire && $product->hiringDetail) {
            // Get hiring days from request
            $days = $item['hiring_days'] ?? $product->hiringDetail->min_hire_days;

            $product_snapshot['hiring_details'] = [
                'days' => $days,
                ...$product->hiringDetail->toArray(),
            ];
        }

        $product_snapshot['sizes'] = $sizes;

        $product_snapshot['colour'] = $colour;

        return $product_snapshot;
    }

    public function getSellerInfo()
    {
        $user = Auth::user();

        $sales = $this->sellerOrderRepository->findSalesProductsBySeller($user->id)->count();
        $hires = $this->sellerOrderRepository->findHireProductsBySeller($user->id)->count();
        $payouts = $this->payoutTransactionRepository->getSellerTransactions($user->id);
        $commission = $payouts->sum('commission');
        $income = $payouts->sum('transaction.amount') - $commission;

        return [
            'sales' => $sales,
            'hires' => $hires,
            'income' => $income,
            'commission' => $commission
        ];
    }

    public function getSellerOrders()
    {
        $user = Auth::user();
        $orders = $this->sellerOrderRepository->findAllSellerOrders($user->id);
        return $orders;
    }

    public function getSellerOrderById($id)
    {
        return $this->sellerOrderRepository->findById($id);
    }

    /**
     * Get all available order statuses
     * @return Collection
     */
    public function getAllOrderStatuses()
    {
        return $this->orderStatusRepository->getAll();
    }

    /**
     * Add a status to a seller order
     * @param int $sellerOrderId Seller order ID
     * @param int $statusId Status ID to add
     * @return SellerOrder
     * @throws Exception
     */
    public function addStatusToSellerOrder($sellerOrderId, $statusId)
    {
        try {
            $sellerOrder = $this->sellerOrderRepository->findByIdRaw($sellerOrderId);
            
            if (!$sellerOrder) {
                throw new Exception('Seller order not found.', 404);
            }

            $status = $this->orderStatusRepository->findById($statusId);
            
            if (!$status) {
                throw new Exception('Status not found.', 404);
            }

            // Check if status is already attached
            if (!$sellerOrder->statuses()->where('order_status_id', $statusId)->exists()) {
                $sellerOrder->statuses()->attach($statusId);
            }

            return $this->sellerOrderRepository->findById($sellerOrderId);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage(), $ex->getCode() ?? 500);
        }
    }

    /**
     * Calculate the total price for an item
     * For sale products: price * quantity
     * For hire products: (price * quantity) + (extra days * additional_fee_per_day * quantity)
     * 
     * @param array $item Item data from request
     * @param Product $product Product model
     * @return float Total price for the item
     */
    private function calculateItemPrice($item, $product)
    {
        $basePrice = $product->price * $item['quantity'];

        // For hire products with hiring days
        if ($product->type === 'hire' && isset($item['hiring_days']) && $product->hiringDetail) {
            $hiringDays = $item['hiring_days'];
            $minHireDays = $product->hiringDetail->min_hire_days;
            $additionalFeePerDay = $product->hiringDetail->additional_fee_per_day;

            // Base price covers minHireDays, additional days are charged separately
            if ($hiringDays > $minHireDays) {
                $extraDays = $hiringDays - $minHireDays;
                $additionalCost = $extraDays * $additionalFeePerDay * $item['quantity'];
                return $basePrice + $additionalCost;
            }
        }

        return $basePrice;
    }

    /**
     * Validate that sufficient stock is available for a product size
     * 
     * @param int $productId Product ID
     * @param int $sizeId Size ID
     * @param int $requestedQuantity Requested quantity
     * @param string $productName Product name for error message
     * @throws Exception If insufficient stock
     */
    private function validateProductSizeStock($productId, $sizeId, $requestedQuantity, $productName)
    {
        // Find the product size record
        $productSize = DB::table('product_sizes')
            ->where('product_id', $productId)
            ->where('size_id', $sizeId)
            ->whereNull('deleted_at')
            ->first();

        if (!$productSize) {
            throw new Exception("Size not available for product: {$productName}", 404);
        }

        // Check if sufficient quantity is available
        if ($productSize->quantity < $requestedQuantity) {
            // Get size name for better error message
            $size = DB::table('sizes')->where('id', $sizeId)->first();
            $sizeName = $size ? $size->name : "Size ID {$sizeId}";
            
            throw new Exception(
                "Insufficient stock for {$productName} - {$sizeName}. Available: {$productSize->quantity}, Requested: {$requestedQuantity}", 
                422
            );
        }
    }

    /**
     * Reduce the quantity of a product size in stock
     * 
     * @param int $productId Product ID
     * @param int $sizeId Size ID
     * @param int $quantity Quantity to reduce
     * @throws Exception If insufficient stock
     */
    private function reduceProductSizeQuantity($productId, $sizeId, $quantity)
    {
        // Find the product size record
        $productSize = DB::table('product_sizes')
            ->where('product_id', $productId)
            ->where('size_id', $sizeId)
            ->whereNull('deleted_at')
            ->first();

        if (!$productSize) {
            throw new Exception("Product size not found for product ID {$productId} and size ID {$sizeId}", 404);
        }

        // Check if sufficient quantity is available (double-check as safety measure)
        if ($productSize->quantity < $quantity) {
            throw new Exception("Insufficient stock for product ID {$productId}, size ID {$sizeId}. Available: {$productSize->quantity}, Requested: {$quantity}", 422);
        }

        // Reduce the quantity
        DB::table('product_sizes')
            ->where('product_id', $productId)
            ->where('size_id', $sizeId)
            ->whereNull('deleted_at')
            ->decrement('quantity', $quantity);
    }
}

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
     * @return Collection
     */
    public function getAll()
    {
        return $this->orderRepository->getAll();
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

                if ($product->type === 'hire' && $item['quantity'] < $product->hiringDetail->min_hire_days) {
                    $errors[] = "Product {$product->name} requires minimum {$product->hiringDetail->min_hire_days} hire days";
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
                return $products[$item['product_id']]->price * $item['quantity'];
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

            DB::commit();

            // Create payment intent for the order
            $stripeUrl = $this->paymentService->createStripePaymentIntent(
                $order->toArray(),
                $itemsArray
            );

            return [
                'order' => $order,
                'stripe_url' => $stripeUrl
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
            return $products[$item['product_id']]->price * $item['quantity'];
        });

        // Create seller order and attach initial pending status
        $sellerOrder = $this->sellerOrderRepository->create([
            'order_id' => $orderId,
            'seller_id' => $sellerId,
            'amount' => $totalAmount
        ]);

        $pendingStatus = $this->orderStatusRepository->getStatusByName('Pending');
        $sellerOrder->statuses()->attach($pendingStatus->id);

        return $sellerOrder;
    }

    /**
     * Create order items for a seller order
     * @param SellerOrder $sellerOrder The seller order to create items for
     * @param Collection $items Items to create
     * @param Collection $products All products involved in the order
     */
    private function createOrderItems($sellerOrder, $items, $products)
    {
        foreach ($items as $item) {
            $product = $products[$item['product_id']];

            $product_snapshot = $this->createProductSnapshot($item);

            // Create order item with product details
            $orderItem = $this->orderItemRepository->create([
                'seller_order_id' => $sellerOrder->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'product_snapshot' => json_encode($product_snapshot)
            ]);

            // Attach size if specified
            if (!empty($item['size_id'])) {
                $orderItem->sizes()->sync($item['size_id']);
            }
        }
    }

    private function createProductSnapshot($item)
    {
        $product = $item['product_snapshot'];
        $isHire = $product->type === 'hire';
        $sizes = $item['sizes'];
        $colours = $item['colours'];
        
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
            //TODO: get hiring days from request
            $days = rand($product->hiringDetail->min_hire_days, 10);

            $product_snapshot['hiring_details'] = [
                'days' => $days,
                ...$product->hiringDetail->toArray(),
            ];
        }

        $product_snapshot['sizes'] = $sizes;

        $product_snapshot['colour'] = $colours;

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
}

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

    public function __construct(OrderRepository $orderRepository, OrderItemRepository $orderItemRepository, ProductRepository $productRepository, UserAddressRepository $userAddressRepository, AddressRepository $addressRepository, PaymentMethodRepository $paymentMethodRepository, PaymentService $paymentService, SellerOrderRepository $sellerOrderRepository, OrderStatusRepository $orderStatusRepository)
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
        return $this->orderRepository->getAll()->load(['sellerOrders.statuses', 'orderItems.product']);
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
            $data['addresses'] = json_encode(
                $this->addressRepository->findAddressesByIds([$data['billing_address_id'], $data['shipping_address_id']])
            );

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
            return $this->paymentService->createStripePaymentIntent(
                $order->toArray(),
                $itemsArray
            );
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

            // Create order item with product details
            $orderItem = $this->orderItemRepository->create([
                'seller_order_id' => $sellerOrder->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'product_snapshot' => json_encode($product)
            ]);

            // Attach size if specified
            if (!empty($item['size_id'])) {
                $orderItem->sizes()->sync($item['size_id']);
            }
        }
    }
}

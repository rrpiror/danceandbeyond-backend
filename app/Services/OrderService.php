<?php

namespace App\Services;

use App\Models\ProductVariant;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Repositories\AddressRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderStatusRepository;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SellerOrderRepository;
use App\Repositories\TransactionRepository;
use App\Services\PaymentService;
use App\Repositories\UserAddressRepository;
use App\Repositories\PayoutTransactionRepository;
use App\Models\StripeIntent;
use App\Models\Product;
use App\Models\SellerOrder;
use App\Models\Order;
use App\Models\UnavailabilityDuration;
use App\Http\Resources\OrderResource;
use Stripe\BalanceTransaction;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\Transfer;

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
    protected TransactionRepository $transactionRepository;

    public function __construct(OrderRepository $orderRepository, OrderItemRepository $orderItemRepository, ProductRepository $productRepository, UserAddressRepository $userAddressRepository, AddressRepository $addressRepository, PaymentMethodRepository $paymentMethodRepository, PaymentService $paymentService, SellerOrderRepository $sellerOrderRepository, OrderStatusRepository $orderStatusRepository, PayoutTransactionRepository $payoutTransactionRepository, TransactionRepository $transactionRepository)
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
        $this->transactionRepository = $transactionRepository;
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
     * @return OrderResource Payment intent details
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
            $addresses = [];
            if ($billingAddress) {
                $addresses['billing'] = $billingAddress;
            }
            if ($shippingAddress) {
                $addresses['shipping'] = $shippingAddress;
            }
            $data['addresses'] = json_encode($addresses);

            $data['items'] = collect($data['items'])->map(function ($item) use ($products) {
                $product = $products[$item['product_id']];

                if ($product->type === 'hire') {
                    $item['hiring_days'] = $this->calculateHireDaysFromDates($item);
                }

                return $item;
            })->toArray();

            // Calculate total amount and prepare items for payment
            $itemsCollection = collect($data['items']);
            $itemsArrayForStripe = [];
            $sellerDeliveryCharges = $this->calculateSellerDeliveryCharges($itemsCollection, $products);

            foreach ($itemsCollection as $item) {
                $product = $products[$item['product_id']];

                if ($product->type === 'hire') {
                    $this->validateHireItem($item, $product);
                }

                // Validate stock availability
                if (isset($item['variant_id']) && isset($item['quantity'])) {
                    $this->validateProductVariantStock($product->id, $item['variant_id'], $item['quantity'], $product->name);
                }

                $price = $this->calculateSingleItemPrice($item, $product);
                $deposit = $this->calculateHireDeposit($item, $product);

                $itemsArrayForStripe[] = [
                    'quantity' => $item['quantity'],
                    'price' => $price * 100,
                    'name' => $product->name,
                    'product_id' => $item['product_id']
                ];

                if ($deposit > 0) {
                    $itemsArrayForStripe[] = [
                        'quantity' => 1,
                        'price' => $deposit * 100,
                        'name' => "{$product->name} refundable deposit",
                        'product_id' => $item['product_id']
                    ];
                }

            }

            foreach ($sellerDeliveryCharges as $sellerId => $deliveryCharge) {
                if ($deliveryCharge <= 0) {
                    continue;
                }

                $itemsArrayForStripe[] = [
                    'quantity' => 1,
                    'price' => $deliveryCharge * 100,
                    'name' => 'Delivery charge',
                    'product_id' => "delivery_seller_{$sellerId}"
                ];
            }

            $data['amount'] = $itemsCollection->sum(function ($item) use ($products) {
                $product = $products[$item['product_id']];
                return $this->calculateItemPrice($item, $product)
                    + $this->calculateHireDeposit($item, $product);
            }) + collect($sellerDeliveryCharges)->sum();

            // Create the main order
            $order = $this->orderRepository->create($data);
            // Group items by seller and create separate seller orders
            $sellerItems = $itemsCollection->groupBy(function ($item) use ($products) {
                return $products[$item['product_id']]->user_id;
            });

            foreach ($sellerItems as $sellerId => $sellerProducts) {
                $sellerOrder = $this->createSellerOrder($order->id, $sellerId, $sellerProducts, $products, $sellerDeliveryCharges[$sellerId] ?? 0);
                $this->createOrderItems($sellerOrder, $sellerProducts, $products);
            }

            // Create payment intent for the order
            $paymentIntentData = $this->paymentService->createStripePaymentIntent(
                $order->toArray(),
                $itemsArrayForStripe
            );

            // Store the stripe intent in the database
            $stripeIntent = StripeIntent::create([
                'order_id' => $order->id,
                'payment_intent_id' => $paymentIntentData['payment_intent_id'],
                'client_secret' => $paymentIntentData['client_secret'],
                'amount' => $data['amount']* 100,
                'currency' => $paymentIntentData['currency'] ?? 'gbp',
                'status' => 'requires_payment_method', // Default status for new payment intents
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $userId,
                ],
            ]);

            DB::commit();

            return new OrderResource($order->load($this->orderRepository->orderDetailedRelations));
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
    private function createSellerOrder($orderId, $sellerId, $items, $products, float $deliveryCharge = 0)
    {
        // Calculate total amount for this seller's items
        $totalAmount = collect($items)->sum(function ($item) use ($products) {
            return $this->calculateItemPrice($item, $products[$item['product_id']]);
        }) + $deliveryCharge;

        // Create seller order and attach initial pending status
        $sellerOrder = $this->sellerOrderRepository->create([
            'order_id' => $orderId,
            'seller_id' => $sellerId,
            'amount' => $totalAmount,
            'delivery_charge' => $deliveryCharge
        ]);

        $pendingStatus = $this->orderStatusRepository->findById(env('ORDER_STATUS_PENDING_ID'))
            ?? $this->orderStatusRepository->getStatusByName('Pending');

        if (!$pendingStatus) {
            throw new Exception('Pending order status is not configured', 422);
        }

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

            $product_snapshot = $this->createProductSnapshot($product);

            // Create order item with product details
            $orderItem = $this->orderItemRepository->create([
                'seller_order_id' => $sellerOrder->id,
                'product_id' => $product->id,
                'variant_id' => $item['variant_id'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $this->calculateSingleItemPrice($item, $product),
                'product_snapshot' => json_encode($product_snapshot)
            ]);

            // If it's a hire product, create hiring detail
            if ($product->type === 'hire' && isset($item['hiring_days'])) {
                $startDate = isset($item['start_date'])
                    ? Carbon::parse($item['start_date'])->startOfDay()
                    : null;
                $endDate = isset($item['end_date'])
                    ? Carbon::parse($item['end_date'])->startOfDay()
                    : null;

                $orderItem->hiringDetail()->create([
                    'hiring_days' => $item['hiring_days'],
                    'start_date' => $startDate?->toDateString(),
                    'end_date' => $endDate?->toDateString(),
                    'deposit_amount' => $this->calculateHireDeposit($item, $product),
                    'deposit_status' => $this->calculateHireDeposit($item, $product) > 0 ? 'held' : 'none',
                ]);

                if ($startDate && $endDate) {
                    UnavailabilityDuration::create([
                        'product_id' => $product->id,
                        'start_date' => $startDate->copy()->subDays(3)->toDateString(),
                        'end_date' => $endDate->copy()->addDays(3)->toDateString(),
                    ]);
                }
            }

            // process variant
            if (isset($item['variant_id'])) {
                $this->reduceProductVariantQuantity($item['variant_id'], $item['quantity']);
            }
        }
    }

    function createProductSnapshot($product)
    {
        $variants = $product->variants->load(['colour', 'size']);

        $product_snapshot = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'delivery_charge' => $product->delivery_charge ?? 0,
            'type' => $product->type,
            'variants' => $variants,
            'hiring_details'=> $product->hiringDetail ? $product->hiringDetail->toArray() : null,
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
            'user' => [
                'id' => $product->user_id,
                'name' => $product->user ? $product->user->name : null,
            ],
        ];

        return $product_snapshot;
    }

    public function getSellerInfo()
    {
        $user = Auth::user();

        $sales = $this->sellerOrderRepository->findSalesProductsBySeller($user->id)->count();
        $hires = $this->sellerOrderRepository->findHireProductsBySeller($user->id)->count();
        $payouts = $this->payoutTransactionRepository->getSellerTransactions($user->id);
        $commission = $payouts->sum('commission');
        $income = $payouts->sum('transaction.amount');

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
        DB::beginTransaction();

        try {
            $sellerOrder = SellerOrder::with(['statuses', 'order.stripeIntent', 'orderItems.hiringDetail'])
                ->where('id', $sellerOrderId)
                ->lockForUpdate()
                ->first();

            if (!$sellerOrder) {
                throw new Exception('Seller order not found.', 404);
            }

            $status = $this->orderStatusRepository->findById($statusId);

            if (!$status) {
                throw new Exception('Status not found.', 404);
            }

            $alreadyHasStatus = $sellerOrder->statuses->contains('id', $status->id);

            $this->validateSellerOrderStatusTransition($sellerOrder, $status->name);

            if ($alreadyHasStatus) {
                DB::commit();

                return $this->sellerOrderRepository->findById($sellerOrderId);
            }

            if ($status->name === 'Cancelled') {
                $this->refundCancelledSellerOrder($sellerOrder);
            }

            if (!$sellerOrder->statuses()->where('order_status_id', $statusId)->exists()) {
                $sellerOrder->statuses()->attach($statusId);
            }

            DB::commit();

            return $this->sellerOrderRepository->findById($sellerOrderId);
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage(), $ex->getCode() ?? 500);
        }
    }

    public function markOverdueSellerOrders(): int
    {
        $overdueStatus = $this->orderStatusRepository->getStatusByName('Overdue');

        if (!$overdueStatus) {
            throw new Exception('Overdue order status is not configured', 422);
        }

        $terminalStatuses = [
            'Returned in Transit',
            'Completed',
            'Cancelled',
            'Dispute',
            'Dispute Resolved',
        ];

        $sellerOrders = SellerOrder::query()
            ->whereHas('orderItems.hiringDetail', function ($query) {
                $query->whereDate('end_date', '<', now()->toDateString());
            })
            ->whereHas('statuses', function ($query) {
                $query->where('name', 'Delivered');
            })
            ->whereDoesntHave('statuses', function ($query) use ($terminalStatuses) {
                $query->whereIn('name', $terminalStatuses);
            })
            ->with('statuses')
            ->get();

        $updated = 0;

        foreach ($sellerOrders as $sellerOrder) {
            if (!$sellerOrder->statuses()->where('order_status_id', $overdueStatus->id)->exists()) {
                $sellerOrder->statuses()->attach($overdueStatus->id);
                $updated++;
            }
        }

        return $updated;
    }

    public function releaseSellerOrderFunds($sellerOrderId)
    {
        DB::beginTransaction();

        try {
            $sellerOrder = SellerOrder::with(['seller', 'statuses', 'order.stripeIntent', 'orderItems.hiringDetail'])
                ->where('id', $sellerOrderId)
                ->lockForUpdate()
                ->first();

            if (!$sellerOrder) {
                throw new Exception('Seller order not found.', 404);
            }

            if ((int) $sellerOrder->order->user_id !== (int) Auth::id()) {
                throw new Exception('Only the buyer can approve release for this seller order.', 403);
            }

            if ($sellerOrder->transferred_at) {
                throw new Exception('Funds have already been released for this seller order.', 422);
            }

            if (!$sellerOrder->seller?->stripe_seller_id) {
                throw new Exception('Seller has not set up Stripe payouts.', 422);
            }

            if ($sellerOrder->order?->stripeIntent?->status !== 'succeeded') {
                throw new Exception('Payment must be confirmed before funds can be released.', 422);
            }

            $statusNames = $sellerOrder->statuses->pluck('name');
            $releaseStatus = $this->isHireSellerOrder($sellerOrder) ? 'Completed' : 'Delivered';
            if (
                !$statusNames->contains('Payment Confirmed') ||
                !$statusNames->contains($releaseStatus)
            ) {
                throw new Exception("Seller order must be payment confirmed and {$releaseStatus} before release.", 422);
            }

            $payout = $this->calculateSellerOrderPayout($sellerOrder);

            if ($payout['payout_amount'] <= 0) {
                throw new Exception('Payout amount must be greater than zero.', 422);
            }

            $transfer = Transfer::create([
                'amount' => $payout['payout_amount'],
                'currency' => env('CASHIER_CURRENCY', 'gbp'),
                'destination' => $sellerOrder->seller->stripe_seller_id,
                'description' => "Payout for seller order #{$sellerOrder->id}",
                'metadata' => [
                    'seller_order_id' => $sellerOrder->id,
                    'order_id' => $sellerOrder->order_id,
                    'seller_id' => $sellerOrder->seller_id,
                    'gross_amount' => $payout['gross_amount'],
                    'platform_fee' => $payout['platform_fee'],
                    'stripe_fee' => $payout['stripe_fee'],
                ],
            ]);

            $sellerOrder->update(['transferred_at' => now()]);

            $transaction = $this->transactionRepository->create([
                'stripe_payment_id' => $transfer->id,
                'amount' => $payout['payout_amount'] / 100,
                'type' => 'payout',
            ]);

            $this->payoutTransactionRepository->create([
                'transaction_id' => $transaction->id,
                'seller_id' => $sellerOrder->seller_id,
                'commission' => $payout['platform_fee'] / 100,
            ]);

            DB::commit();

            return $this->sellerOrderRepository->findById($sellerOrderId);
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage(), $ex->getCode() ?: 422);
        }
    }

    private function validateSellerOrderStatusTransition(SellerOrder $sellerOrder, string $requestedStatus): void
    {
        $latestStatus = $this->latestSellerOrderStatusName($sellerOrder);
        $isHireOrder = $this->isHireSellerOrder($sellerOrder);
        $isSeller = (int) Auth::id() === (int) $sellerOrder->seller_id;
        $isBuyer = (int) Auth::id() === (int) $sellerOrder->order->user_id;

        if (!$isSeller && !$isBuyer) {
            throw new Exception('You cannot update this seller order.', 403);
        }

        if ($sellerOrder->statuses->contains('name', $requestedStatus)) {
            return;
        }

        if ($requestedStatus === 'Cancelled') {
            if (!$isSeller && !$isBuyer) {
                throw new Exception('Only the buyer or seller can cancel this order.', 403);
            }

            if ($sellerOrder->statuses->contains('name', 'Shipped')) {
                throw new Exception('Orders can only be cancelled before they are shipped.', 422);
            }

            return;
        }

        if ($requestedStatus === 'Dispute') {
            if (!$isSeller && !$isBuyer) {
                throw new Exception('Only the buyer or seller can dispute this order.', 403);
            }

            if (!in_array($latestStatus, ['Shipped', 'Delivered', 'Returned in Transit', 'Overdue'], true)) {
                throw new Exception('This order cannot be disputed from its current status.', 422);
            }

            return;
        }

        $sellerTransitions = [
            'Shipped' => ['Order Confirmed', 'Payment Confirmed'],
            'Dispute Resolved' => ['Dispute'],
            'Completed' => $isHireOrder
                ? ['Returned in Transit', 'Overdue', 'Dispute Resolved']
                : ['Delivered', 'Dispute Resolved'],
        ];

        $buyerTransitions = [
            'Delivered' => ['Shipped'],
            'Returned in Transit' => ['Delivered', 'Overdue'],
        ];

        if (isset($sellerTransitions[$requestedStatus])) {
            if (!$isSeller) {
                throw new Exception('Only the seller can set this status.', 403);
            }

            if (!in_array($latestStatus, $sellerTransitions[$requestedStatus], true)) {
                throw new Exception("Cannot change status from {$latestStatus} to {$requestedStatus}.", 422);
            }

            return;
        }

        if (isset($buyerTransitions[$requestedStatus])) {
            if (!$isBuyer) {
                throw new Exception('Only the buyer can set this status.', 403);
            }

            if (!$isHireOrder && $requestedStatus === 'Returned in Transit') {
                throw new Exception('Only hire orders can be returned in transit.', 422);
            }

            if (!in_array($latestStatus, $buyerTransitions[$requestedStatus], true)) {
                throw new Exception("Cannot change status from {$latestStatus} to {$requestedStatus}.", 422);
            }

            return;
        }

        throw new Exception('This status is managed automatically and cannot be set manually.', 422);
    }

    private function latestSellerOrderStatusName(SellerOrder $sellerOrder): string
    {
        $latestStatus = $sellerOrder->statuses
            ->sortByDesc(fn ($status) => $status->pivot?->created_at)
            ->first();

        return $latestStatus?->name ?? 'Pending';
    }

    private function isHireSellerOrder(SellerOrder $sellerOrder): bool
    {
        return $sellerOrder->orderItems->contains(function ($orderItem) {
            return $orderItem->hiringDetail !== null;
        });
    }

    private function refundCancelledSellerOrder(SellerOrder $sellerOrder): void
    {
        if ($sellerOrder->order?->stripeIntent?->status !== 'succeeded') {
            return;
        }

        $refundAmount = (int) round($sellerOrder->amount * 100) + $this->heldDepositAmountInPence($sellerOrder);

        if ($refundAmount <= 0) {
            return;
        }

        Refund::create([
            'payment_intent' => $sellerOrder->order->stripeIntent->payment_intent_id,
            'amount' => $refundAmount,
            'metadata' => [
                'seller_order_id' => $sellerOrder->id,
                'order_id' => $sellerOrder->order_id,
                'reason' => 'seller_order_cancelled',
            ],
        ]);

        $this->updateDepositStatus($sellerOrder, 'released');
    }

    public function releaseSellerOrderDeposit($sellerOrderId)
    {
        DB::beginTransaction();

        try {
            $sellerOrder = $this->depositSellerOrderQuery($sellerOrderId);

            if (!in_array((int) Auth::id(), [(int) $sellerOrder->seller_id, (int) $sellerOrder->order->user_id], true)) {
                throw new Exception('Only the buyer or seller can release a deposit.', 403);
            }

            $depositAmount = $this->heldDepositAmountInPence($sellerOrder);
            if ($depositAmount <= 0) {
                throw new Exception('No held deposit is available to release.', 422);
            }

            Refund::create([
                'payment_intent' => $sellerOrder->order->stripeIntent->payment_intent_id,
                'amount' => $depositAmount,
                'metadata' => [
                    'seller_order_id' => $sellerOrder->id,
                    'order_id' => $sellerOrder->order_id,
                    'reason' => 'hire_deposit_released',
                ],
            ]);

            $this->updateDepositStatus($sellerOrder, 'released');

            DB::commit();

            return $this->sellerOrderRepository->findById($sellerOrderId);
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage(), $ex->getCode() ?: 422);
        }
    }

    public function retainSellerOrderDeposit($sellerOrderId)
    {
        DB::beginTransaction();

        try {
            $sellerOrder = $this->depositSellerOrderQuery($sellerOrderId);

            if ((int) $sellerOrder->seller_id !== (int) Auth::id()) {
                throw new Exception('Only the seller can retain a deposit.', 403);
            }

            if (!$sellerOrder->seller?->stripe_seller_id) {
                throw new Exception('Seller has not set up Stripe payouts.', 422);
            }

            $depositAmount = $this->heldDepositAmountInPence($sellerOrder);
            if ($depositAmount <= 0) {
                throw new Exception('No held deposit is available to retain.', 422);
            }

            Transfer::create([
                'amount' => $depositAmount,
                'currency' => env('CASHIER_CURRENCY', 'gbp'),
                'destination' => $sellerOrder->seller->stripe_seller_id,
                'description' => "Retained hire deposit for seller order #{$sellerOrder->id}",
                'metadata' => [
                    'seller_order_id' => $sellerOrder->id,
                    'order_id' => $sellerOrder->order_id,
                    'reason' => 'hire_deposit_retained',
                ],
            ]);

            $this->updateDepositStatus($sellerOrder, 'retained');

            DB::commit();

            return $this->sellerOrderRepository->findById($sellerOrderId);
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage(), $ex->getCode() ?: 422);
        }
    }

    public function disputeSellerOrderDeposit($sellerOrderId, ?string $reason)
    {
        DB::beginTransaction();

        try {
            $sellerOrder = $this->depositSellerOrderQuery($sellerOrderId);

            if (!in_array((int) Auth::id(), [(int) $sellerOrder->seller_id, (int) $sellerOrder->order->user_id], true)) {
                throw new Exception('Only the buyer or seller can dispute a deposit.', 403);
            }

            if ($this->heldDepositAmountInPence($sellerOrder) <= 0) {
                throw new Exception('No held deposit is available to dispute.', 422);
            }

            $sellerOrder->orderItems->each(function ($orderItem) use ($reason) {
                if ($orderItem->hiringDetail && $orderItem->hiringDetail->deposit_status === 'held') {
                    $orderItem->hiringDetail->update([
                        'deposit_status' => 'disputed',
                        'deposit_dispute_reason' => $reason,
                    ]);
                }
            });

            DB::commit();

            return $this->sellerOrderRepository->findById($sellerOrderId);
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage(), $ex->getCode() ?: 422);
        }
    }

    private function depositSellerOrderQuery($sellerOrderId): SellerOrder
    {
        $sellerOrder = SellerOrder::with(['seller', 'order.stripeIntent', 'orderItems.hiringDetail'])
            ->where('id', $sellerOrderId)
            ->lockForUpdate()
            ->first();

        if (!$sellerOrder) {
            throw new Exception('Seller order not found.', 404);
        }

        if ($sellerOrder->order?->stripeIntent?->status !== 'succeeded') {
            throw new Exception('Payment must be confirmed before deposits can be resolved.', 422);
        }

        return $sellerOrder;
    }

    private function heldDepositAmountInPence(SellerOrder $sellerOrder): int
    {
        return (int) round($sellerOrder->orderItems->sum(function ($orderItem) {
            if (!$orderItem->hiringDetail || $orderItem->hiringDetail->deposit_status !== 'held') {
                return 0;
            }

            return (float) $orderItem->hiringDetail->deposit_amount;
        }) * 100);
    }

    private function updateDepositStatus(SellerOrder $sellerOrder, string $status): void
    {
        $sellerOrder->orderItems->each(function ($orderItem) use ($status) {
            if ($orderItem->hiringDetail && $orderItem->hiringDetail->deposit_status === 'held') {
                $orderItem->hiringDetail->update([
                    'deposit_status' => $status,
                    'deposit_resolved_at' => now(),
                ]);
            }
        });
    }

    public function calculateSellerOrderPayout(SellerOrder $sellerOrder): array
    {
        $grossAmount = (int) round($sellerOrder->amount * 100);
        $platformFee = (int) round($grossAmount * (float) env('PLATFORM_FEE', 0));
        $stripeFee = $this->calculateSellerOrderStripeFeeShare($sellerOrder, $grossAmount);
        $payoutAmount = $grossAmount - $platformFee - $stripeFee;

        return [
            'gross_amount' => $grossAmount,
            'platform_fee' => $platformFee,
            'stripe_fee' => $stripeFee,
            'payout_amount' => $payoutAmount,
        ];
    }

    private function calculateSellerOrderStripeFeeShare(SellerOrder $sellerOrder, int $grossAmount): int
    {
        $order = $sellerOrder->order;
        $orderTotal = (int) round($order->amount * 100);

        if ($orderTotal <= 0) {
            throw new Exception('Order total must be greater than zero.', 422);
        }

        $totalStripeFee = $this->getStripeFeeForOrder($order);

        return (int) round($totalStripeFee * ($grossAmount / $orderTotal));
    }

    private function getStripeFeeForOrder(Order $order): int
    {
        $paymentIntentId = $order->stripeIntent?->payment_intent_id;

        if (!$paymentIntentId) {
            throw new Exception('Stripe payment intent is missing for this order.', 422);
        }

        $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
        $latestCharge = $paymentIntent->latest_charge;
        $chargeId = is_string($latestCharge) ? $latestCharge : ($latestCharge->id ?? null);

        if (!$chargeId) {
            throw new Exception('Stripe charge is not available for this order yet.', 422);
        }

        $charge = Charge::retrieve($chargeId);
        $balanceTransaction = $charge->balance_transaction;

        if (is_string($balanceTransaction)) {
            $balanceTransaction = BalanceTransaction::retrieve($balanceTransaction);
        }

        if (!$balanceTransaction || !isset($balanceTransaction->fee)) {
            throw new Exception('Stripe fee is not available for this order yet.', 422);
        }

        return (int) $balanceTransaction->fee;
    }

    /**
     * Calculate the total price for an item
     * For sale products: price * quantity
     * For hire products: daily hire price * hire days * quantity.
     * The additional_fee_per_day field is a late return fee and is not charged at checkout.
     * 
     * @param array $item Item data from request
     * @param Product $product Product model
     * @return float Total price for the item
     */
    private function calculateItemPrice($item, $product)
    {
        $singleItemPrice = $this->calculateSingleItemPrice($item, $product);
        $quantityPrice = $singleItemPrice * $item['quantity'];
        return $quantityPrice;
    }

    private function calculateHireDaysFromDates(array $item): int
    {
        if (empty($item['start_date']) || empty($item['end_date'])) {
            throw new Exception('Hire products require a start date and end date.', 422);
        }

        $startDate = Carbon::parse($item['start_date'])->startOfDay();
        $endDate = Carbon::parse($item['end_date'])->startOfDay();

        if ($endDate->lt($startDate)) {
            throw new Exception('Hire end date must be on or after the start date.', 422);
        }

        return $startDate->diffInDays($endDate) + 1;
    }

    private function calculateHireDeposit(array $item, Product $product): float
    {
        if ($product->type !== 'hire' || !$product->hiringDetail) {
            return 0;
        }

        return (float) ($product->hiringDetail->deposit_amount ?? 0) * (int) $item['quantity'];
    }

    private function calculateSellerDeliveryCharges(Collection $items, Collection $products): array
    {
        $deliveryCharges = [];

        foreach ($items as $item) {
            $product = $products[$item['product_id']];

            if (!$this->productHasDelivery($product)) {
                continue;
            }

            $sellerId = $product->user_id;
            $charge = (float) ($product->delivery_charge ?? 0);
            $deliveryCharges[$sellerId] = max($deliveryCharges[$sellerId] ?? 0, $charge);
        }

        return $deliveryCharges;
    }

    private function productHasDelivery(Product $product): bool
    {
        return $product->fulfillmentOptions->contains(function ($option) {
            return strtolower($option->name) === 'delivery';
        });
    }

    private function validateHireItem(array $item, Product $product): void
    {
        if (!$product->hiringDetail) {
            throw new Exception("Product {$product->name} is missing hiring details.", 422);
        }

        $hiringDays = $item['hiring_days'] ?? $this->calculateHireDaysFromDates($item);

        if ($hiringDays < $product->hiringDetail->min_hire_days) {
            throw new Exception(
                "Product {$product->name} requires minimum {$product->hiringDetail->min_hire_days} hire days",
                422
            );
        }

        $startDate = Carbon::parse($item['start_date'])->startOfDay();
        $endDate = Carbon::parse($item['end_date'])->startOfDay();

        foreach ($product->unavailabilityDurations as $duration) {
            $unavailableStart = Carbon::parse($duration->start_date)->startOfDay();
            $unavailableEnd = Carbon::parse($duration->end_date)->startOfDay();

            if ($startDate->lte($unavailableEnd) && $endDate->gte($unavailableStart)) {
                throw new Exception(
                    "Product {$product->name} is unavailable for the selected hire dates.",
                    422
                );
            }
        }
    }

    private function calculateSingleItemPrice($item, $product)
    {
        $basePrice = $product->price;

        if ($product->type === 'hire' && isset($item['hiring_days'])) {
            return $basePrice * $item['hiring_days'];
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
    private function validateProductVariantStock($productId, $variantId, $requestedQuantity, $productName)
    {
        // Find the product variant record
        $productVariant = ProductVariant::with(['size', 'colour'])->find($variantId);

        if (!$productVariant) {
            throw new Exception("Size not available for product: {$productName}", 404);
        }

        // Check if sufficient quantity is available
        if ($productVariant->quantity < $requestedQuantity) {
            throw new Exception(
                "Insufficient stock for {$productName}. Available: {$productVariant->quantity}, Requested: {$requestedQuantity}",
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

    private function reduceProductVariantQuantity($variantId, $quantity)
    {
        $variant = ProductVariant::find($variantId);
    
        if(!$variant) {
            throw new Exception("Product variant not found for variant ID {$variantId}", 404);
        }

        $variant->decrement('quantity', $quantity);
    }
}

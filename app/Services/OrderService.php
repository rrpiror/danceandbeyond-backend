<?php

namespace App\Services;

use App\Repositories\AddressRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserAddressRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\PaymentMethodRepository;
use App\Services\PaymentService;

class OrderService
{
    protected OrderRepository $orderRepository;
    protected ProductRepository $productRepository;
    protected UserAddressRepository $userAddressRepository;
    protected AddressRepository $addressRepository;
    protected OrderItemRepository $orderItemRepository;
    protected PaymentMethodRepository $paymentMethodRepository;
    protected PaymentService $paymentService;

    public function __construct(OrderRepository $orderRepository, OrderItemRepository $orderItemRepository, ProductRepository $productRepository, UserAddressRepository $userAddressRepository, AddressRepository $addressRepository, PaymentMethodRepository $paymentMethodRepository, PaymentService $paymentService)
    {
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->productRepository = $productRepository;
        $this->userAddressRepository = $userAddressRepository;
        $this->addressRepository = $addressRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->paymentService = $paymentService;
    }

    public function getAllPaymentMethods()
    {
        return $this->paymentMethodRepository->getAll();
    }

    public function update(array $data)
    {
        $order = $this->orderRepository->findById($data['order_id']);

        if (!$order) {
            throw new Exception('Order not found.', 404);
        }

        $order->status = $data['status'];
        $order->save();
        return $order;
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();

            $userId = Auth::id();
            $address = $this->addressRepository->create($data['billing_address']);
            $this->userAddressRepository->create([
                'user_id' => $userId,
                'address_id' => $address->id,
                'type' => 'billing'
            ]);
            $addresses = $this->userAddressRepository->findByUserId($userId);
            $data['user_id'] = $userId;
            $data['status'] = 'pending';
            $data['addresses'] = json_encode($addresses);
            $order = $this->orderRepository->create($data);

            $orderAmount = 0;
            foreach ($data['items'] as $item) {
                $item['order_id'] = $order->id;
                $item['product_snapshot'] = json_encode($this->productRepository->findById($item['product_id']));
                $orderItem = $this->orderItemRepository->create($item);
                $orderAmount += $item['price'];

                if (isset($item['size_id'])) {
                    $orderItem->sizes()->sync($item['size_id']);
                }
            }

            DB::commit();

            $paymentIntent = $this->paymentService->createStripePaymentIntent($order);

            return $paymentIntent;
        } catch (Exception $ex) {
            DB::rollBack();
            throw new Exception($ex->getMessage(), 422);
        }
    }
}

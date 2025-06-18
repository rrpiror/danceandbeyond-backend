<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollectionResource;

class OrderRepository
{
    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function findById($id)
    {
        $orderData = $this->order->with('sellerOrders.statuses', 'orderItems.product')->where('user_id', Auth::user()->id)->find($id);
        
        return new OrderResource($orderData);
    }

    public function create(array $data)
    {
        return $this->order->create($data);
    }

    public function getAll()
    {
        $ordersData = $this->order->where('user_id', Auth::user()->id)->latest()->get()
        ->load(['sellerOrders.statuses', 'sellerOrders.orderItems']);
        
        return new OrderCollectionResource($ordersData);
    }
}
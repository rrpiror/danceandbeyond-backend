<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
        $orderData = formatOrderData($orderData);
        return $orderData;
    }

    public function create(array $data)
    {
        return $this->order->create($data);
    }

    public function getAll()
    {
        $ordersData = $this->order->where('user_id', Auth::user()->id)->latest()->get()
        ->load(['sellerOrders.statuses', 'sellerOrders.orderItems',]);
        $ordersData = formatOrderListData($ordersData);
        return $ordersData;
    }
}

function formatOrderData($orderData)
{
    $orderData->addresses = json_decode($orderData->addresses);
    $orderData->sellerOrders->each(function ($sellerOrder) {
        $seller = User::find($sellerOrder->seller_id);
        $sellerOrder->seller = $seller;
        $sellerOrder->orderItems->each(function ($orderItem) {
            $orderItem->product_snapshot = json_decode($orderItem->product_snapshot);
        });
    });
    
    return $orderData;
}

function formatOrderListData($ordersData)
{
    //extract all seller ids
    $sellerIds = $ordersData->map(function ($order) {
        return $order->sellerOrders->pluck('seller_id')->unique()->toArray();
    })->flatten()->unique()->toArray();
    $sellers = User::whereIn('id', $sellerIds)->select('id', 'name')->get();
    $ordersData->each(function ($order) use ($sellers) {
        $order->addresses = json_decode($order->addresses);
        $order->sellerOrders->each(function ($sellerOrder) use ($sellers) {
            $seller = $sellers->firstWhere('id', '=',  $sellerOrder->seller_id);
            $sellerOrder->seller = $seller;
            $sellerOrder->orderItems->each(function ($orderItem) use ($sellers) {
                $orderItem->product_snapshot = json_decode($orderItem->product_snapshot);
            });
        });
    });
    return $ordersData;
}
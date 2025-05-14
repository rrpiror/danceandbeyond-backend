<?php

namespace App\Repositories;

use App\Models\SellerOrder;

class SellerOrderRepository
{
    protected SellerOrder $sellerOrder;

    public function __construct(SellerOrder $sellerOrder)
    {
        $this->sellerOrder = $sellerOrder;
    }

    public function create(array $data)
    {
        return $this->sellerOrder->create($data);
    }

    public function findOldOrders()
    {
        return $this->sellerOrder->with('seller')->where('created_at', '<=', now()->subDays(14))->whereNull('transferred_at')->where('status', 'completed')->get();
    }

    public function findById($id)
    {
        return $this->sellerOrder->find($id);
    }

    public function findByOrderId($orderId)
    {
        return $this->sellerOrder->with('statuses')->where('order_id', $orderId)->latest()->get();
    }

    public function findSalesProductsBySeller($sellerId)
    {
        return $this->sellerOrder
            ->where('seller_id', $sellerId)
            ->whereHas('orderItems.product', function ($query) {
                $query->where('type', 'sale');
            })
            ->latest()
            ->get();
    }

    public function findHireProductsBySeller($sellerId)
    {
        return $this->sellerOrder
            ->where('seller_id', $sellerId)
            ->whereHas('orderItems.product', function ($query) {
                $query->where('type', 'hire');
            })
            ->latest()
            ->get();
    }

    public function findAllSellerOrders($sellerId)
    {
        return $this->sellerOrder->where('seller_id', $sellerId)->latest()->get();
    }
}

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
        return $this->sellerOrder->with('statuses')->where('order_id', $orderId)->get();
    }
}

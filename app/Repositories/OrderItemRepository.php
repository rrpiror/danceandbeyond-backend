<?php

namespace App\Repositories;

use App\Models\OrderItem;

class OrderItemRepository
{
    protected OrderItem $orderItem;

    public function __construct(OrderItem $orderItem)
    {
        $this->orderItem = $orderItem;
    }

    public function create(array $data)
    {
        return $this->orderItem->create($data);
    }
}

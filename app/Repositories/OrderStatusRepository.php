<?php

namespace App\Repositories;

use App\Models\OrderStatus;

class OrderStatusRepository
{
    protected OrderStatus $orderStatus;

    public function __construct(OrderStatus $orderStatus)
    {
        $this->orderStatus = $orderStatus;
    }

    public function getStatusByName($name)
    {
        return $this->orderStatus->where('name', $name)->first();
    }

    public function create(array $data)
    {
        return $this->orderStatus->create($data);
    }
}

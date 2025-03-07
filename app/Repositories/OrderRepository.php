<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function findById($id)
    {
        return $this->order->find($id);
    }

    public function create(array $data)
    {
        return $this->order->create($data);
    }
}

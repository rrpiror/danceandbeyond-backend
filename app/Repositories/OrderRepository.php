<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderRepository
{
    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function findById($id)
    {
        return $this->order->where('user_id', Auth::user()->id)->find($id);
    }

    public function create(array $data)
    {
        return $this->order->create($data);
    }

    public function getAll()
    {
        return $this->order->where('user_id', Auth::user()->id)->latest()->get();
    }
}

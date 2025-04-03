<?php

namespace App\Repositories;

use App\Models\OrderTransaction;

class OrderTransactionRepository
{
    protected OrderTransaction $orderTransaction;

    public function __construct(OrderTransaction $orderTransaction)
    {
        $this->orderTransaction = $orderTransaction;
    }

    public function create(array $data)
    {
        return $this->orderTransaction->create($data);
    }
}

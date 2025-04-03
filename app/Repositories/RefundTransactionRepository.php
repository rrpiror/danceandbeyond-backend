<?php

namespace App\Repositories;

use App\Models\RefundTransaction;

class RefundTransactionRepository
{
    protected RefundTransaction $refundTransaction;

    public function __construct(RefundTransaction $refundTransaction)
    {
        $this->refundTransaction = $refundTransaction;
    }

    public function create(array $data)
    {
        return $this->refundTransaction->create($data);
    }
}

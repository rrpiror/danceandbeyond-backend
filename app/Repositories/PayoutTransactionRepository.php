<?php

namespace App\Repositories;

use App\Models\PayoutTransaction;

class PayoutTransactionRepository
{
    protected PayoutTransaction $payoutTransaction;

    public function __construct(PayoutTransaction $payoutTransaction)
    {
        $this->payoutTransaction = $payoutTransaction;
    }

    public function create(array $data)
    {
        return $this->payoutTransaction->create($data);
    }

    public function getSellerTransactions($sellerId)
    {
        return $this->payoutTransaction->where('seller_id', $sellerId)->with('transaction')->latest()->get();
    }
}

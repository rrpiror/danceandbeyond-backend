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
}

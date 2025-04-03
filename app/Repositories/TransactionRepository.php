<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    protected Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function create(array $data)
    {
        return $this->transaction->create($data);
    }
}

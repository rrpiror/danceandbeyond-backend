<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stripe_payment_id',
        'type',
        'amount',
    ];

    public function orderTransactions()
    {
        return $this->hasMany(OrderTransaction::class);
    }

    public function payoutTransaction()
    {
        return $this->hasOne(PayoutTransaction::class);
    }

    public function refundTransaction()
    {
        return $this->hasOne(RefundTransaction::class);
    }
}

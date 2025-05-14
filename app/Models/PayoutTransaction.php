<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayoutTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'seller_id',
        'commission',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class);
    }
}

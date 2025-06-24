<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeIntent extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_intent_id',
        'client_secret',
        'amount',
        'currency',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the order that owns the stripe intent.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemHiringDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'hiring_days',
        'start_date',
        'end_date',
        'deposit_amount',
        'deposit_status',
        'deposit_resolved_at',
        'deposit_dispute_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'deposit_amount' => 'decimal:2',
        'deposit_resolved_at' => 'datetime',
    ];

    /**
     * Get the order item that owns the hiring detail.
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}

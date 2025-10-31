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
    ];

    /**
     * Get the order item that owns the hiring detail.
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}

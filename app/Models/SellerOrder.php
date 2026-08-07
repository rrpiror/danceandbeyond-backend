<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'seller_id',
        'order_id',
        'amount',
        'delivery_charge',
        'status',
        'transferred_at'
    ];

    protected $casts = [
        'amount' => 'float',
        'delivery_charge' => 'float',
        'transferred_at' => 'datetime',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }

    public function statuses()
    {
        return $this->belongsToMany(OrderStatus::class, 'seller_order_statuses', 'seller_order_id', 'order_status_id')->withTimestamps();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

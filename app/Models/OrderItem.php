<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'seller_order_id',
        'product_id',
        'quantity',
        'price',
        'product_snapshot'
    ];

    public function sellerOrder()
    {
        return $this->belongsTo(SellerOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItemSizes()
    {
        return $this->hasOne(OrderItemSize::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'order_item_sizes', 'order_item_id', 'size_id')
            ->withPivot(['quantity', 'price', 'product_snapshot'])
            ->withTimestamps();
    }
}

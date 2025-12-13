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
        'variant_id',
        'quantity',
        'price',
        'product_snapshot'
    ];

    protected $casts = [
        'product_snapshot' => 'json',
    ];

    public function sellerOrder()
    {
        return $this->belongsTo(SellerOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function hiringDetail()
    {
        return $this->hasOne(OrderItemHiringDetail::class);
    }
}

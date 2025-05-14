<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductShippingServiceProvider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['product_id', 'service_provider_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function shippingServiceProvider()
    {
        return $this->belongsTo(ShippingServiceProvider::class, 'service_provider_id');
    }
}

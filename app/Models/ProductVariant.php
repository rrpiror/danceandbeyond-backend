<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'colour_id',
        'size_id',
        'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function colour()
    {
        return $this->belongsTo(ProductColour::class, 'colour_id');
    }

    public function size()
    {
        return $this->belongsTo(ProductSize::class, 'size_id');
    }
}

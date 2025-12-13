<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSize extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_sizes';

    protected $fillable = [
        'name',
        'description'
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'size_id');
    }
}

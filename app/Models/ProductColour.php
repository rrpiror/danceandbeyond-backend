<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductColour extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_colours';

    protected $fillable = [
        'name',
        'hexcode',
        'description'
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'colour_id');
    }
}

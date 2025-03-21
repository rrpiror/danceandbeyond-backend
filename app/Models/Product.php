<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'condition_id',
        'brand_id',
        'name',
        'description',
        'price',
        'is_featured',
        'type'
    ];

    protected $casts = [
        'is_featured' => 'boolean'
    ];

    public function fulfillmentOptions()
    {
        return $this->belongsToMany(FulfillmentOption::class, 'product_fulfillment_options', 'product_id', 'fulfillment_option_id')
            ->withTimestamps();
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes', 'product_id', 'size_id')->withPivot(['quantity'])->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function productSizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function colours()
    {
        return $this->belongsToMany(Colour::class, 'product_colours', 'product_id', 'colour_id');
    }

    public function productColours()
    {
        return $this->hasMany(ProductColour::class);
    }

    public function hiringDetail()
    {
        return $this->hasOne(HiringDetail::class);
    }

		public function reviews() {
			return $this->hasMany(ProductReview::class);
		}
}

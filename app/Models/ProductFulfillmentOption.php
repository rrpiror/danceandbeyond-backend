<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductFulfillmentOption extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'fulfillment_option_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
		}
		public function fulfillmentOption()
		{
				return $this->belongsTo(FulfillmentOption::class);
		}
}

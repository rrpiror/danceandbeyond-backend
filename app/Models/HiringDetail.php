<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HiringDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'min_hire_days',
        'additional_fee_per_day',
        'deposit_amount',
    ];

    protected $casts = [
        'additional_fee_per_day' => 'float',
        'deposit_amount' => 'float',
    ];
}

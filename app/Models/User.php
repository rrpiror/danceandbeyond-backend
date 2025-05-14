<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, SoftDeletes, Notifiable, HasApiTokens, InteractsWithMedia, Billable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified',
        'remember_me',
        'remember_me_at',
        'reset_password_token',
        'reset_password_token_at',
        'type',
        'status',
        'stripe_seller_id',
        'stripe_customer_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function address()
    {
        return $this->belongsToMany(Address::class, 'user_addresses', 'user_id', 'address_id')->withPivot(['type'])->withTimestamps();
    }

    public function school()
    {
        return $this->hasOne(UserSchool::class);
    }

    public function favouriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favourite_products')->withTimestamps();
    }

    public function getProfilePictureAttribute()
    {
        return $this->getFirstMediaUrl('profile_picture');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(UserReview::class);
    }

    public function reviewsReceived()
    {
        return $this->hasManyThrough(ProductReview::class, Product::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_picture')->singleFile();
    }
}

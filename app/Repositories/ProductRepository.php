<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    protected Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getAll()
    {
        return $this->product->latest()
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->with('media')
            ->get();
    }

    public function findById($id)
    {
        return $this->product->with('brand', 'category', 'condition', 'productSizes.size', 'colours', 'fulfillmentOptions', 'hiringDetail.unavailabilityDays', 'user:id,name', 'reviews.user:id,name', 'media')->find($id);
    }

    public function create(array $data)
    {
        return $this->product->create($data);
    }

    public function findByIds(array $ids)
    {
        return $this->product->with('hiringDetail')->whereIn('id', $ids)->get();
    }

    public function findProductsByUserId($userId)
    {
        return $this->product->where('user_id', $userId)->with('media', 'brand')->latest()->get();
    }
}

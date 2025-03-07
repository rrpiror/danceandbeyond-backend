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
        return $this->product->latest()->get();
    }

    public function findById($id)
    {
        return $this->product->with('productSizes.size', 'category', 'condition', 'brand', 'hiringDetail.unavailabilityDays')->find($id);
    }

    public function create(array $data)
    {
        return $this->product->create($data);
    }
}

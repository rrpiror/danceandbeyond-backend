<?php

namespace App\Repositories;

use App\Models\ProductVariant;

class ProductVariantRepository
{
    protected ProductVariant $productVariant;

    public function __construct(ProductVariant $productVariant)
    {
        $this->productVariant = $productVariant;
    }

    public function create(array $data)
    {
        return $this->productVariant->create($data);
    }

    public function deleteByProductId($productId)
    {
        return $this->productVariant->where('product_id', $productId)->delete();
    }

    public function findByProductId($productId)
    {
        return $this->productVariant->where('product_id', $productId)->with(['colour', 'size'])->get();
    }
}

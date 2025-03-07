<?php

namespace App\Repositories;

use App\Models\ProductSize;

class ProductSizeRepository
{
    protected ProductSize $productSize;

    public function __construct(ProductSize $productSize)
    {
        $this->productSize = $productSize;
    }

    public function create(array $data)
    {
        return $this->productSize->create($data);
    }

    public function deleteByProductId($id)
    {
        return $this->productSize->where('product_id', $id)->delete();
    }
}

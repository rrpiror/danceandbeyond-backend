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

    public function getAll()
    {
        return $this->productSize->all();
    }
}

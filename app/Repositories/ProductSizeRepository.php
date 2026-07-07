<?php

namespace App\Repositories;

use App\Models\ProductSize;

class ProductSizeRepository
{
    protected ProductSize $size;

    public function __construct(ProductSize $size)
    {
        $this->size = $size;
    }

    public function getAll()
    {
        return $this->size->latest()->get();
    }
}

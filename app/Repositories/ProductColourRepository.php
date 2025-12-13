<?php

namespace App\Repositories;

use App\Models\ProductColour;

class ProductColourRepository
{
    protected ProductColour $productColour;

    public function __construct(ProductColour $productColour)
    {
        $this->productColour = $productColour;
    }

    public function getAll()
    {
        return $this->productColour->latest()->get();
    }
}

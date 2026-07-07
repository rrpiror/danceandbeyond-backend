<?php

namespace App\Repositories;

use App\Models\ProductColour;

class ProductColourRepository
{
    protected ProductColour $colour;

    public function __construct(ProductColour $colour)
    {
        $this->colour = $colour;
    }

    public function getAll()
    {
        return $this->colour->latest()->get();
    }
}

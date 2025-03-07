<?php

namespace App\Repositories;

use App\Models\Brand;

class BrandRepository
{
    protected Brand $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    public function getAll()
    {
        return $this->brand->all();
    }
}

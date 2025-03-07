<?php

namespace App\Services;

use App\Repositories\BrandRepository;

class BrandService
{
    protected BrandRepository $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function getAll()
    {
        return $this->brandRepository->getAll();
    }
}

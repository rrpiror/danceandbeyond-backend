<?php

namespace App\Services;

use App\Repositories\SizeRepository;

class SizeService
{
    protected SizeRepository $sizeRepository;

    public function __construct(SizeRepository $sizeRepository)
    {
        $this->sizeRepository = $sizeRepository;
    }

    public function getAll()
    {
        return $this->sizeRepository->getAll();
    }
}

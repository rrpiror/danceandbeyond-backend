<?php

namespace App\Services;

use App\Repositories\FulfillmentOptionRepository;

class FulfillmentOptionService
{
    protected FulfillmentOptionRepository $fulfillmentOptionRepository;

    public function __construct(FulfillmentOptionRepository $fulfillmentOptionRepository)
    {
        $this->fulfillmentOptionRepository = $fulfillmentOptionRepository;
    }

    public function getAll()
    {
        return $this->fulfillmentOptionRepository->getAll();
    }
}

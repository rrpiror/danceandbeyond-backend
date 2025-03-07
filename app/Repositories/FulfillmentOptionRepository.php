<?php

namespace App\Repositories;

use App\Models\FulfillmentOption;

class FulfillmentOptionRepository
{
    protected FulfillmentOption $fulfillmentOption;

    public function __construct(FulfillmentOption $fulfillmentOption)
    {
        $this->fulfillmentOption = $fulfillmentOption;
    }

    public function getAll()
    {
        return $this->fulfillmentOption->all();
    }
}

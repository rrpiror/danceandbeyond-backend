<?php

namespace App\Repositories;

use App\Models\UnavailabilityDuration;

class UnavailabilityDurationRepository
{
    protected UnavailabilityDuration $unavailabilityDuration;

    public function __construct(UnavailabilityDuration $unavailabilityDuration)
    {
        $this->unavailabilityDuration = $unavailabilityDuration;
    }

    public function findByProductId($productId)
    {
        return $this->unavailabilityDuration->where('product_id', $productId)->get();
    }

    public function create(array $data)
    {
        return $this->unavailabilityDuration->create($data);
    }

    public function deleteByProductId($productId)
    {
        return $this->unavailabilityDuration->where('product_id', $productId)->delete();
    }
}

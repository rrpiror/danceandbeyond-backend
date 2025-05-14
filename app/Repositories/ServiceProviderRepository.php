<?php

namespace App\Repositories;

use App\Models\ShippingServiceProvider;

class ServiceProviderRepository
{
    protected $shippingServiceProvider;

    public function __construct(ShippingServiceProvider $shippingServiceProvider)
    {
        $this->shippingServiceProvider = $shippingServiceProvider;
    }

    public function getAll()
    {
        return $this->shippingServiceProvider->all();
    }

    public function create(array $data)
    {
        return $this->shippingServiceProvider->create($data);
    }
}

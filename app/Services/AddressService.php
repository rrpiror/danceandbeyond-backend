<?php

namespace App\Services;

use App\Repositories\AddressRepository;

class AddressService
{
    protected AddressRepository $addressRepository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    public function create(array $data)
    {
        return $this->addressRepository->create($data);
    }
}

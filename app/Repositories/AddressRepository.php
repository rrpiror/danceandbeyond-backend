<?php

namespace App\Repositories;

use App\Models\Address;

class AddressRepository
{
    protected Address $address;

    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    public function create(array $data)
    {
        return $this->address->create($data);
    }

    public function findById($id)
    {
        return $this->address->find($id);
    }

    public function findAddressesByIds(array $ids)
    {
        return $this->address->whereIn('id', $ids)->get();
    }
}

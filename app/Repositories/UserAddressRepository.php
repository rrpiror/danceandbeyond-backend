<?php

namespace App\Repositories;

use App\Models\UserAddress;

class UserAddressRepository
{
    protected UserAddress $userAddress;

    public function __construct(UserAddress $userAddress)
    {
        $this->userAddress = $userAddress;
    }

    public function findByUserId($userId)
    {
        return $this->userAddress->where('user_id', $userId)->with('address')->get();
    }

    public function create(array $data)
    {
        return $this->userAddress->updateOrCreate(['user_id' => $data['user_id'], 'type' => $data['type']], $data);
    }
}

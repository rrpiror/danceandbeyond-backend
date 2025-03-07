<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function findById($id)
    {
        return $this->user->find($id);
    }

    public function findByEmail($email)
    {
        return $this->user->where('email', $email)->first();
    }

    public function create(array $data)
    {
        return $this->user->create($data);
    }
}

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

    public function findByResetPasswordToken($token)
    {
        return $this->user->where('reset_password_token', $token)->first();
    }

    public function findByPhoneNumber($phoneNumber)
    {
        return $this->user->where('phone_number', $phoneNumber)->first();
    }

    public function findByUsername($username)
    {
        return $this->user->where('username', $username)->first();
    }
}

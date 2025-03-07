<?php

namespace App\Repositories;

use App\Models\UserSchool;

class UserSchoolRepository
{
    protected UserSchool $userSchool;

    public function __construct(UserSchool $userSchool)
    {
        $this->userSchool = $userSchool;
    }

    public function findById($id)
    {
        return $this->userSchool->find($id);
    }

    public function findByUserId($userId)
    {
        return $this->userSchool->where('user_id', $userId)->first();
    }

    public function create(array $data)
    {
        return $this->userSchool->create($data);
    }

    public function updateOrCreate(array $data)
    {
        return $this->userSchool->updateOrCreate(['user_id' => $data['user_id']], $data);
    }
}

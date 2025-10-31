<?php

namespace App\Repositories;

use App\Models\Organisation;

class OrganisationRepository
{
    protected Organisation $organisation;

    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }

    public function findById($id)
    {
        return $this->organisation->find($id);
    }

    public function findByUserId($userId)
    {
        return $this->organisation->where('user_id', $userId)->first();
    }

    public function create(array $data)
    {
        return $this->organisation->create($data);
    }

    public function updateOrCreate(array $data)
    {
        return $this->organisation->updateOrCreate(['user_id' => $data['user_id']], $data);
    }
}

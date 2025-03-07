<?php

namespace App\Repositories;

use App\Models\HiringUnavailabilityDay;

class HiringUnavailabilityDayRepository
{
    protected HiringUnavailabilityDay $hiringUnavailabilityDay;

    public function __construct(HiringUnavailabilityDay $hiringUnavailabilityDay)
    {
        $this->hiringUnavailabilityDay = $hiringUnavailabilityDay;
    }

    public function findByHiringDetailId($hiringId)
    {
        return $this->hiringUnavailabilityDay->where('hiring_detail_id', $hiringId)->get();
    }

    public function create(array $data)
    {
        return $this->hiringUnavailabilityDay->create($data);
    }

    public function deleteByHiringDetailId($hiringId)
    {
        return $this->hiringUnavailabilityDay->where('hiring_detail_id', $hiringId)->delete();
    }
}

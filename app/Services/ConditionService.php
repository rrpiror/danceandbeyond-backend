<?php

namespace App\Services;

use App\Repositories\ConditionRepository;

class ConditionService
{
    protected ConditionRepository $conditionRepository;

    public function __construct(ConditionRepository $conditionRepository)
    {
        $this->conditionRepository = $conditionRepository;
    }

    public function getAll()
    {
        return $this->conditionRepository->getAll();
    }
}

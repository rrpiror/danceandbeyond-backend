<?php

namespace App\Repositories;

use App\Models\Condition;

class ConditionRepository
{
    protected Condition $condition;

    public function __construct(Condition $condition)
    {
        $this->condition = $condition;
    }

    public function getAll()
    {
        return $this->condition->all();
    }
}

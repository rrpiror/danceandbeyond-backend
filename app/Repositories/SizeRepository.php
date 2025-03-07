<?php

namespace App\Repositories;

use App\Models\Size;

class SizeRepository
{
    protected Size $size;

    public function __construct(Size $size)
    {
        $this->size = $size;
    }

    public function getAll()
    {
        return $this->size->all();
    }
}

<?php

namespace App\Repositories;

use App\Models\Colour;

class ColourRepository
{
    protected $colour;

    public function __construct(Colour $colour)
    {
        $this->colour = $colour;
    }

    public function getAll()
    {
        return $this->colour->latest()->get();
    }
}

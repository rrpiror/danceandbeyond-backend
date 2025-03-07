<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    protected Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getAll()
    {
        return $this->category->all();
    }
}

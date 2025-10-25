<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends Repository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        parent::__construct(new Category);
    }

    public function getOnlyWithProducts()
    {
        return $this->query()->select('id', 'name', 'slug')->whereHas('products')->get();
    }
}

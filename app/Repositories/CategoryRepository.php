<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

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
        return Cache::rememberForever('categories_with_products', function () {
            return $this->query()->select('id', 'name', 'slug')->whereHas('products')->get();
        });
    }
}

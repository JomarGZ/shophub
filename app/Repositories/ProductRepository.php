<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends Repository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        parent::__construct(new Product);
    }

    public function getFeaturedProducts($relation = [], $limit = 8, $column = ['*'])
    {
        return $this->query()
            ->with($relation)
            ->select($column)
            ->inRandomOrder()
            ->where('stock', '>', 0)
            ->take($limit)
            ->get();
    }
}

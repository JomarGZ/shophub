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



    public function getFeaturedProducts(array $relation = [], array $column = ['*'], $limit = 8)
    {
        return $this->query()
            ->with($relation)
            ->select($column)
            ->inRandomOrder()
            ->where('stock', '>', 0)
            ->take($limit)
            ->get();
    }

    public function getRelatedProducts(int|string $catId, array $relation = [], array $column = ['*'], $limit = 8)
    {
        return $this->query()
            ->with($relation)
            ->select($column)
            ->inRandomOrder()
            ->where('stock', '>', 0)
            ->where('category_id', $catId)
            ->take($limit)
            ->get();
    }
}

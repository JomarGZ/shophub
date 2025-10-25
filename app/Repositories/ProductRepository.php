<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ProductRepository extends Repository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        parent::__construct(new Product);
    }

    public function getFeaturedProducts(array|string $relation = [], array $columns = ['*'], int $limit = 8): Collection
    {
        return $this->query()->with($relation)->select($columns)->inRandomOrder()->inStock()->take($limit)->get();
    }

    public function getRelatedProducts(int|string $catId, array|string $relation = [], array $columns = ['*'], int $limit = 8): Collection
    {
        return $this->query()->with($relation)->select($columns)->inRandomOrder()->inStock()->where('category_id', $catId)->take($limit)->get();
    }

    public function getPaginatedProducts(int $perPage = 15, array $columns = ['*'], ?array $filters = [], array|string $relations = []): LengthAwarePaginator
    {
        return $this->query()->with($relations)->filter($filters)->paginate($perPage, $columns)->withQueryString();
    }

    public function getPriceRange()
    {
        return Cache::rememberForever('product_price_range', function () {
            $min = (float) $this->model->min('price') ?? 0;
            $max = (float) $this->model->max('price') ?? 250;

            return [
                'min' => floor($min / 10) * 10,
                'max' => ceil($max / 10) * 10,
            ];
        });
    }
}

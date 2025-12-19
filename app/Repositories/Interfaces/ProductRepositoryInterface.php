<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function getFeaturedProducts(
        array|string $relations = [],
        array $columns = ['*'],
        int $limit = 8,
        bool $skipFavorited = false
    ): Collection;

    public function getRelatedProducts(
        int|string $categoryId,
        array|string $relations = [],
        array $columns = ['*'],
        int $limit = 8
    ): Collection;

    public function getPaginatedProducts(
        int $perPage = 15,
        array $columns = ['*'],
        ?array $filters = [],
        array|string $relations = []
    ): LengthAwarePaginator;

    public function getPriceRange(): array;
}

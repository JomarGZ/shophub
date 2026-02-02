<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function getFeaturedProducts(
        array|string $relations = [],
        array $columns = ['*'],
        int $limit = 8
    ): Collection;

    public function getRelatedProducts(
        int|string $catId,
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

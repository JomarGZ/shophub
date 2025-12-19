<?php

namespace App\Repositories\Decorators;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

abstract class ProductRepositoryDecorator implements ProductRepositoryInterface
{
    public function __construct(
        protected ProductRepositoryInterface $repository
    ) {}

    public function getFeaturedProducts(
        array|string $relations = [],
        array $columns = ['*'],
        int $limit = 8,
        bool $skipFavorited = false
    ): Collection {
        return $this->repository->getFeaturedProducts($relations, $columns, $limit, $skipFavorited);
    }

    public function getRelatedProducts(
        int|string $categoryId,
        array|string $relations = [],
        array $columns = ['*'],
        int $limit = 8
    ): Collection {
        return $this->repository->getRelatedProducts($categoryId, $relations, $columns, $limit);
    }

    public function getPaginatedProducts(
        int $perPage = 15,
        array $columns = ['*'],
        ?array $filters = [],
        array|string $relations = []
    ): LengthAwarePaginator {
        return $this->repository->getPaginatedProducts($perPage, $columns, $filters, $relations);
    }

    public function getPriceRange(): array
    {
        return $this->repository->getPriceRange();
    }
}

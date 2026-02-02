<?php

namespace App\Repositories\Decorators;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class ProductRepositoryDecorator implements ProductRepositoryInterface
{
    public function __construct(
        protected ProductRepositoryInterface $repository
    ) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function find(int $id): ?Model
    {
        return $this->repository->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    public function update(Model $model, array $data): bool
    {
        return $this->repository->update($model, $data);
    }

    public function delete(Model $model): bool
    {
        return $this->repository->delete($model);
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], array|string $relations = []): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $columns, $relations);
    }

    public function where(string $column, $value): Collection
    {
        return $this->repository->where($column, $value);
    }

    public function getFeaturedProducts(
        array|string $relations = [],
        array $columns = ['*'],
        int $limit = 8,
    ): Collection {
        return $this->repository->getFeaturedProducts($relations, $columns, $limit);
    }

    public function getRelatedProducts(
        int|string $catId,
        array|string $relations = [],
        array $columns = ['*'],
        int $limit = 8
    ): Collection {
        return $this->repository->getRelatedProducts($catId, $relations, $columns, $limit);
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

    public function paginateWithWishlist(int $perPage, int $userId)
    {
        $products = $this->repository
            ->paginateWithWishlist($perPage, $userId);

        return $products;
    }
}

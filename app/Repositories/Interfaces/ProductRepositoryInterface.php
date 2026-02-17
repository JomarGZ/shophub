<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface extends RepositoryInterface
{
    public function getFeaturedProducts(
        int $userId,
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

    public function getPriceRange(): array;

    public function paginateWithWishlist(int $perPage, int $userId, array $columns = ['*'], array|string $relations = []): LengthAwarePaginator;

    public function findWithWishlistBySlug(string $slug, int $userId, array $columns = ['*']);

    public function paginateWishlistProducts(int $userId, int $perPage = 12, array $columns = ['*'], array|string $relations = []): LengthAwarePaginator;
}

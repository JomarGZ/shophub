<?php

namespace App\Repositories\Contracts;

use App\Models\Wishlist;

interface WishlistRepositoryInterface
{
    public function exists(int $userId, int $productId): bool;

    public function create(array $data): Wishlist;

    public function deleteByUserAndProduct(int $userId, int $productId): void;
}

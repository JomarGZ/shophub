<?php

namespace App\Repositories\Eloquent;

use App\Models\Wishlist;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\WishlistRepositoryInterface;

class WishlistRepository extends BaseRepository implements WishlistRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(Wishlist $model)
    {
        parent::__construct($model);
    }

    public function exists(int $userId, int $productId): bool
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();
    }

    public function create(array $data): Wishlist
    {
        return $this->model->create($data);
    }

    public function deleteByUserAndProduct(int $userId, int $productId): void
    {
        $this->model
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();
    }
}

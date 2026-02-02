<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\WishlistRepositoryInterface;

class WishlistService
{
     public function __construct(
        private WishlistRepositoryInterface $wishlistRepository
    ) {}
    // public function toggle(User $user, Product $product)
    // {
    //     return $user->wishlist()->toggle($product->id);
    // }

    public function toggle(int $userId, int $productId): bool
    {
        if ($this->wishlistRepository->exists($userId, $productId)) {

            $this->wishlistRepository
                ->deleteByUserAndProduct($userId, $productId);

            return false;
        }

        $this->wishlistRepository->create([
            'user_id' => $userId,
            'product_id' => $productId
        ]);

        return true;
    }
    public function getSimplePaginatedWishlistProducts(User $user, $perPage = 15, array $columns = ['*'], array|string $relations = [])
    {
        return $user->wishlist()->with($relations)->simplePaginate($perPage, $columns)->withQueryString();
    }
}

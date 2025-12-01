<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;

class WishlistService
{
    public function toggle(User $user, Product $product)
    {
        return $user->wishlist()->toggle($product->id);
    }

    public function getSimplePaginatedWishlistProducts(User $user, $perPage = 15, array $columns = ['*'], array|string $relations = [])
    {
        return $user->wishlist()->with($relations)->simplePaginate($perPage, $columns)->withQueryString();
    }
}

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
}

<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function create(User $user, Product $product): bool
    {
        if (! $user->hasPurchased($product)) {
            return false;
        }

        return ! $user->hasRated($product);
    }

    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }
}

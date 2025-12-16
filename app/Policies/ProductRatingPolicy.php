<?php

namespace App\Policies;

use App\Models\ProductRating;
use App\Models\User;

class ProductRatingPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductRating $productRating): bool
    {
        // if (! $user->hasPurchased($productRating->product)) {
        //     return false;
        // }
        return $user->id === $productRating->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductRating $productRating): bool
    {

        return $user->id === $productRating->user_id;
    }

    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }
}

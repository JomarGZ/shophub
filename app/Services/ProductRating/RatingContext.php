<?php

namespace App\Services\ProductRating;

use App\Models\Product;
use App\Models\User;

class RatingContext
{
    public function __construct(
        public User $user,
        public Product $product,
        public int $rating,
        public ?string $comment = null
    ) {}
}

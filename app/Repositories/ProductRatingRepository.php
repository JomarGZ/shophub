<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductRating;
use App\Models\User;
use App\Services\ProductRating\RatingContext;

class ProductRatingRepository extends Repository
{
    public function __construct()
    {
        return parent::__construct(new ProductRating);
    }

    public function saveRating(RatingContext $context)
    {
        $user = $context->user;
        $product = $context->product;
        $rating = $context->rating;
        $comment = $context->comment;

        return $user->hasRated($product)
            ? $this->updateRating($user->ratings()->firstWhere('product_id', $product->id), $product, $rating, $comment)
            : $this->addRating($user, $product, $rating, $comment);
    }

    public function addRating(User $user, Product $product, int $rating, ?string $comment = null): ProductRating
    {
        return $this->transaction(function () use ($user, $product, $rating, $comment) {
            $productRating = $user->ratings()->create([
                'product_id' => $product->id,
                'rating' => $rating,
                'comment' => $comment,
            ]);

            $newRatingsSum = $product->ratings_sum + $rating;
            $newRatingsCount = $product->ratings_count + 1;
            $product->update([
                'ratings_sum' => $newRatingsSum,
                'ratings_count' => $newRatingsCount,
                'average_rating' => round($newRatingsSum / $newRatingsCount, 2),
            ]);

            return $productRating;
        });

    }

    public function updateRating(ProductRating $existing, Product $product, int $newRating, ?string $comment = null): ProductRating
    {
        return $this->transaction(function () use ($existing, $product, $newRating, $comment) {
            $oldRating = $existing->rating;

            $existing->update([
                'rating' => $newRating,
                'comment' => $comment ?? $existing->comment,
            ]);

            $newRatingsSum = $product->ratings_sum - $oldRating + $newRating;
            $ratingsCount = $product->ratings_count;

            $product->update([
                'ratings_sum' => $newRatingsSum,
                'average_rating' => $ratingsCount > 0 ? round($newRatingsSum / $ratingsCount, 2) : 0,
            ]);

            return $existing;
        });
    }
}

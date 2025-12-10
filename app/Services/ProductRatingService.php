<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductRating;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ProductRatingService
{
    public function rateProduct(User $user, Product $product, array $data): ProductRating
    {
        $rating = $this->validateRating($data['rating']);

        if ($user->hasRated($product)) {
            return $this->updateRating($user, $product, $rating, $data['comment'] ?? null);
        }
        if (!$user->hasOrdered($product)) {
            throw new AuthorizationException('User must have purchased the product to rate it.', 403);
        }

        return $this->addRating($user, $product, $rating, $data['comment'] ?? null);
    }

    public function addRating(User $user, Product $product, int $rating, ?string $comment = null): ProductRating
    {
        return DB::transaction(function () use ($user, $product, $rating, $comment) {
            $productRating = $user->ratings()->create([
                'product_id' => $product->id,
                'rating' => $rating,
                'comment' => $comment,
            ]);
            $newRatingsSum = $product->ratings_sum + $rating;
            $newRatingsCount = $product->ratings_count + 1;
            $newAverage = (float) round($newRatingsSum / $newRatingsCount, 2);

            $product->update([
                'ratings_sum' => $newRatingsSum,
                'ratings_count' => $newRatingsCount,
                'average_rating' => $newAverage,
            ]);

            return $productRating;
        });
    }

    public function updateRating(User $user, Product $product, int $newRating, ?string $comment = null): ProductRating
    {
        return DB::transaction(function () use ($user, $product, $newRating, $comment) {
            $productRating = $user->ratings()->firstWhere('product_id', $product->id);
            $oldRating = $productRating->rating;

            $productRating->update([
                'rating' => $newRating,
                'comment' => $comment ?? $productRating->comment,
            ]);

            $newRatingsSum = $product->ratings_sum - $oldRating + $newRating;
            $ratingsCount = $product->ratings_count;
            $newAverage =  $ratingsCount > 0 ? (float) round($newRatingsSum / $ratingsCount, 2) : 0.0;

            $product->update([
                'ratings_sum' => $newRatingsSum,
                'average_rating' => $newAverage,
            ]);

            return $productRating;
        });

    }

    public function removeRating()
    {
        return dump('remove rating');
    }

    protected function validateRating(int $rating)
    {
        if (! isset($rating) || ! is_numeric($rating) || $rating < 1 || $rating > 5) {
            throw new InvalidArgumentException('Rating must be between 1 and 5.');
        }

        return $rating;
    }
}

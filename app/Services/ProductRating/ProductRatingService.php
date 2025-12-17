<?php

namespace App\Services\ProductRating;

use App\Models\Product;
use App\Models\User;
use App\Repositories\ProductRatingRepository;
use App\Services\ProductRating\Pipeline\EnsureProductWasPurchased;
use App\Services\ProductRating\Pipeline\EnsureRatingIsWithinRange;

class ProductRatingService
{
    public function __construct(protected ProductRatingRepository $ratingRepository) {}

    public function rateProduct(User $user, Product $product, int $rating, ?string $comment = null)
    {
        $context = new RatingContext(
            user: $user,
            product: $product,
            rating: $rating,
            comment: $comment
        );

        $pipeline = $this->getValidationPipeline();
        $pipeline->handle($context);

        return $this->ratingRepository->saveRating($context);
    }

    private function getValidationPipeline()
    {
        $pipeline = new EnsureRatingIsWithinRange;
        $pipeline->then(new EnsureProductWasPurchased);

        return $pipeline;
    }
}

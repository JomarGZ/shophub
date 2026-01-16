<?php

namespace App\Services\ProductRating\Pipeline;

use App\Services\ProductRating\RatingContext;
use InvalidArgumentException;

class EnsureRatingIsWithinRange extends RatingRule
{
    public function handle(RatingContext $context): void
    {
        if ($context->rating < 1 || $context->rating > 5) {
            throw new InvalidArgumentException('Rating must be between 1 and 5.');
        }

        parent::handle($context);
    }
}

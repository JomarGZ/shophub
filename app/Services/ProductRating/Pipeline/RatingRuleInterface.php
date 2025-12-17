<?php

namespace App\Services\ProductRating\Pipeline;

use App\Services\ProductRating\RatingContext;

interface RatingRuleInterface
{
    public function then(RatingRuleInterface $next): RatingRuleInterface;

    public function handle(RatingContext $context): void;
}

<?php

namespace App\Services\ProductRating\Pipeline;

use App\Services\ProductRating\RatingContext;
use Illuminate\Auth\Access\AuthorizationException;

class EnsureProductWasPurchased extends RatingRule
{
    public function handle(RatingContext $context): void
    {
        if (! $context->user->hasOrdered($context->product)) {
            throw new AuthorizationException('User can only rate ordered and received product.');
        }

        parent::handle($context);
    }
}

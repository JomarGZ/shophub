<?php

namespace App\Services\ProductRating\Pipeline;

use App\Services\ProductRating\RatingContext;

abstract class RatingRule implements RatingRuleInterface
{
    protected ?RatingRule $next = null;

    public function then(RatingRuleInterface $next): RatingRuleInterface
    {
        $this->next = $next;

        return $next;
    }

    public function handle(RatingContext $context): void
    {
        if ($this->next) {
            $this->next->handle($context);
        }
    }
}

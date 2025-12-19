<?php

namespace App\Repositories\Decorators;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CachedProductRepository extends ProductRepositoryDecorator
{
    public function getPriceRange(): array
    {
        return Cache::remember(
            'product_price_range',
            Carbon::now()->addHours(6),
            fn () => parent::getPriceRange()
        );
    }
}

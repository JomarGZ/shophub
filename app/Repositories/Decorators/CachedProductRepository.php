<?php

namespace App\Repositories\Decorators;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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

    public function getFeaturedProducts(int $userId, array|string $relations = [], array $columns = ['*'], int $limit = 8): Collection
    {
        $products = Cache::remember(
            "featured_products_{$limit}_user_{$userId}",
            Carbon::now()->addMinutes(5),
            fn () => parent::getFeaturedProducts($userId, $relations, $columns, $limit)
        );

        if ($user = request()->user()) {
            $products->loadCount([
                'wishlistedBy as is_favorited' => fn ($q) => $q->where('user_id', $user->id),
            ]);
        }

        return $products;
    }
}

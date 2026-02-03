<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    private function addIsFavoritedCount($query, ?int $userId = null)
    {
        if ($userId) {
            return $query->withCount([
                'wishlistedBy as is_favorited' => fn ($q) => $q->where('user_id', $userId),
            ]);
        }

        return $query;
    }

    public function getFeaturedProducts(array|string $relations = [], array $columns = ['*'], int $limit = 8): Collection
    {
        $query = $this->model->query()
            ->select($columns)
            ->with($relations)
            ->inStock()
            ->orderByDesc('average_rating');

        return $query->take($limit)->get();
    }

    public function getRelatedProducts(int|string $catId, array|string $relation = [], array $columns = ['*'], int $limit = 8): Collection
    {
        $userId = request()->user()?->id;
        $query = $this->model->query()->select($columns)->with($relation)->orderByDesc('average_rating')->inRandomOrder()->inStock()->where('category_id', $catId);
        $query = $this->addIsFavoritedCount($query, $userId);

        return $query->take($limit)->get();
    }

    public function getPaginatedProducts(int $perPage = 15, array $columns = ['*'], ?array $filters = [], array|string $relations = []): LengthAwarePaginator
    {
        $userId = request()->user()?->id;
        $query = $this->model->query()->orderByDesc('average_rating')->with($relations)->filter($filters);
        $query = $this->addIsFavoritedCount($query, $userId);

        return $query->paginate($perPage, $columns)->withQueryString();
    }

    public function getPriceRange(array $filters = []): array
    {
       
        $range = $this->model
            ->query()
            ->where('stock', '>', 0)
            ->filter($filters)
            ->selectRaw('MIN(price) as min, max(price) as max')
            ->first();
        $min = (float) ($range->min ?? 0);
        $max = (float) ($range->max ?? 250);

        return [
            'min' => floor($min / 10) * 10,
            'max' => ceil($max / 10) * 10,
        ];
    }

    public function paginateWithWishlist(int $perPage, int $userId, array $columns = ['*'], array|string $relations = [])
    {
        return $this->model
            ->select($columns)
            ->with($relations)
            ->withExists([
                'wishlistedBy as is_favorited' => fn ($q) =>
                    $q->where('user_id', $userId)
            ])
            ->paginate($perPage);
    }

    public function paginateWishlistProducts(int $userId, int $perPage = 12, array $columns = ['*'], array|string $relations = []): LengthAwarePaginator
    {
        return $this->model
            ->select($columns)
            ->with($relations)
            ->whereHas('wishlistedBy', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->withExists([
                'wishlistedBy as is_favorited' => fn ($q) => $q->where('user_id', $userId)
            ])
            ->paginate($perPage);
    }

    public function findWithWishlistBySlug(string $slug, int $userId, array $columns = ['*'])
    {
        return $this->model
            ->select($columns)
            ->withExists([
                'wishlistedBy as is_favorited' => fn ($q) => $q->where('user_id', $userId) 
            ]) 
            ->where('slug', $slug)
            ->firstOrFail();
    }
}

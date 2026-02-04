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

    public function getFeaturedProducts(int $userId, array|string $relations = [], array $columns = ['*'], int $limit = 8): Collection
    {
        $query = $this->model->query()
            ->select($columns)
            ->with($relations)
            ->withWishlistFlag($userId)
            ->inStock()
            ->orderByDesc('average_rating');

        return $query->take($limit)->get();
    }

    public function getRelatedProducts(int|string $catId, array|string $relation = [], array $columns = ['*'], int $limit = 8): Collection
    {
        $userId = request()->user()?->id;
        $query = $this->model->query()->select($columns)->with($relation)->orderByDesc('average_rating')->withWishlistFlag($userId)->inStock()->where('category_id', $catId);
        return $query->take($limit)->get();
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

    public function paginateWithWishlist(int $perPage, int $userId, array $columns = ['*'], array|string $relations = []): LengthAwarePaginator
    {
        return $this->model
            ->select($columns)
            ->with($relations)
            ->withWishlistFlag($userId)
            ->paginate($perPage);
    }

    public function paginateWishlistProducts(int $userId, int $perPage = 12, array $columns = ['*'], array|string $relations = []): LengthAwarePaginator
    {
        return $this->model
            ->select($columns)
            ->with($relations)
            ->wishlistedByUser($userId)
            ->withWishlistFlag($userId)
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

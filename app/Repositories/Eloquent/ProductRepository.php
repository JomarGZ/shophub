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
            ->orderByDesc('ratings_sum');

        return $query->take($limit)->get();
    }

    public function getRelatedProducts(int|string $catId, array|string $relation = [], array $columns = ['*'], int $limit = 8): Collection
    {
        $userId = request()->user()?->id;
        $query = $this->model->query()->select($columns)->with($relation)->inRandomOrder()->inStock()->where('category_id', $catId);
        $query = $this->addIsFavoritedCount($query, $userId);

        return $query->take($limit)->get();
    }

    public function getPaginatedProducts(int $perPage = 15, array $columns = ['*'], ?array $filters = [], array|string $relations = []): LengthAwarePaginator
    {
        $userId = request()->user()?->id;
        $query = $this->model->query()->with($relations)->filter($filters);
        $query = $this->addIsFavoritedCount($query, $userId);

        return $query->paginate($perPage, $columns)->withQueryString();
    }

    public function getPriceRange(): array
    {
        $min = (float) $this->model->min('price') ?? 0;
        $max = (float) $this->model->max('price') ?? 250;

        return [
            'min' => floor($min / 10) * 10,
            'max' => ceil($max / 10) * 10,
        ];
    }
}

<?php

namespace App\Models;

use App\Observers\ProductObserver;
use App\Policies\ProductPolicy;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

#[ObservedBy([ProductObserver::class])]
#[UsePolicy(ProductPolicy::class)]
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    use HasSlug;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'ratings_count', // total of users who rated
        'ratings_sum', // total of stars
        'average_rating',
        'image_url',
    ];

    protected static function booted()
    {
        static::deleting(function ($product) {
            if ($product->image_url) {
                if (file_exists(storage_path('app/public/'.$product->image_url))) {
                    Storage::disk('public')->delete($product->image_url);
                }
            }
        });

        static::updated(function ($product) {
            if ($product->isDirty('image_url')) {
                $originalImage = $product->getOriginal('image_url');
                if (file_exists(storage_path('app/public/'.$originalImage))) {
                    Storage::disk('public')->delete($originalImage);
                }

            }
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(ProductRating::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    #[Scope]
    protected function inStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    #[Scope]
    protected function outOfStock(Builder $query): Builder
    {
        return $query->where('stock', '=', 0);
    }

    #[Scope]
    protected function withWishlistFlag(Builder $query, int $userId): Builder
    {
        return $query->withExists([
            'wishlistedBy as is_favorited' => fn ($q) => $q->where('user_id', $userId)
        ]);
    }
    #[Scope]
    protected function wishlistedByUser(Builder $query, int $userId): Builder
    {   
        return $query->whereHas('wishlistedBy', fn ($q) => $q->where('user_id', $userId));
    }

    #[Scope]
    protected function filter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        })
            ->when($filters['categories'] ?? null, function ($query, $categories) {
                $query->whereHas('category', function ($q) use ($categories) {
                    $q->whereIn('slug', is_array($categories) ? $categories : [$categories]);
                });
            })
            ->when(isset($filters['min_price']), function ($query) use ($filters) {
                $query->where('price', '>=', $filters['min_price']);
            })
            ->when(isset($filters['max_price']), function ($query) use ($filters) {
                $query->where('price', '<=', $filters['max_price']);
            });
    }
}

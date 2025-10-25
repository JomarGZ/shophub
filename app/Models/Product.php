<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

#[ObservedBy([ProductObserver::class])]
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    #[Scope]
    protected function inStock(Builder $query)
    {
        return $query->where('stock', '>', 0);
    }

    #[Scope]
    protected function outOfStock(Builder $query)
    {
        return $query->where('stock', '=', 0);
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

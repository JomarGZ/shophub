<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

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
}

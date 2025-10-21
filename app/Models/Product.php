<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

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
                Storage::disk('public')->delete($product->image_url);
            }
        });
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

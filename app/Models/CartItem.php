<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    /** @use HasFactory<\Database\Factories\CartItemFactory> */
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    #[Scope]
    protected function filters(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query ,$search) {
            $query->whereHas('product', function($q) use ($search) {
                $term = trim($search);
                $q->where('name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
            });
        });
    }
}

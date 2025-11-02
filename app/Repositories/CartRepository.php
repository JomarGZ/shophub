<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CartRepository extends Repository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        parent::__construct(new CartItem);
    }

    public function save(CartItem $item): CartItem
    {
        $item->save();

        return $item;
    }

    public function findOrCreateItem(int $cartId, int $productId): CartItem
    {
        return $this->model()->firstOrNew([
            'cart_id' => $cartId,
            'product_id' => $productId,
        ]);
    }

    public function getItemsInStock(Cart $cart, array $columns = ['*'], array|string $relations = []): Collection
    {
        return $this->query()->where('cart_id', $cart->id)->whereHas('product', fn ($q) => $q->inStock())->with($relations)->get($columns);
    }

    public function getPaginatedItems(int $userId, int $perPage = 15, array $columns = ['*'], array $filters = [], array|string $relations = []): Collection|LengthAwarePaginator
    {
        $cartId = Cart::where('user_id', $userId)->value('id');
        if (! $cartId) {
            return collect();
        }

        return $this->query()->where('cart_id', $cartId)->with($relations)->filters($filters)->paginate($perPage, $columns)->withQueryString();
    }
}

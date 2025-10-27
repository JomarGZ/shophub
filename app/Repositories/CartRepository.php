<?php

namespace App\Repositories;

use App\Models\CartItem;

class CartRepository extends Repository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        parent::__construct(new CartItem());
    }

    public function findOrCreateItem(int $cartId, int $productId)
    {
        return $this->model()->firstOrNew([
            'cart_id' => $cartId,
            'product_id' => $productId,
        ]);
    }

    public function save(CartItem $item)
    {
        $item->save();
        return $item;
    }
}

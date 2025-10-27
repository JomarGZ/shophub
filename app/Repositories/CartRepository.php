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

   
}

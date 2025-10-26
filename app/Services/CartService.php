<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function addItem(User $user, Product $product, int $quantity)
    {
        if ($product->stock < $quantity) {
            throw ValidationException::withMessages(['product' => 'Not enough stock available']);
        }

        $cart = $user->cart ?? $user->cart()->create();

        $item = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $item->quantity += $quantity;
        $item->save();
        return $item;
    }
}

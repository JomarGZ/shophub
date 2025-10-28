<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Repositories\CartRepository;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function __construct(protected CartRepository $cartRepo) {}

    public function addItem(User $user, Product $product, int $quantity): CartItem
    {
        if ($product->stock < $quantity) {
            throw ValidationException::withMessages(['product' => 'Not enough stock available']);
        }

        $cart = $user->cart ?? $user->cart()->create();

        $item = $this->cartRepo->findOrCreateItem(cartId: $cart->id, productId: $product->id);

        $item->quantity += $quantity;

        return $this->cartRepo->save($item);
    }

    public function removeItem(User $user, CartItem $item)
    {
        $item->load('cart');
        if (! $item->cart || $item->cart->user_id !== $user->id) {
            abort(403);
        }

        return $this->cartRepo->delete($item);
    }

    public function updateQuantity(User $user, CartItem $cartItem, int $quantity)
    {
        $cartItem->load('cart');
        if ($cartItem->cart->user_id !== $user->id) {
            abort(403);
        }
        $this->cartRepo->update(model: $cartItem, data: ['quantity' => $quantity]);
    }
}

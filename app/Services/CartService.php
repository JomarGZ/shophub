<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Repositories\CartRepository;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function __construct(protected CartRepository $cartRepo) {}

    public function getOrCreateCart(User $user)
    {
        return $user->cart ?? $user->cart()->create();
    }

    public function addItem(User $user, Product $product, int $quantity): CartItem
    {
        if ($product->stock < $quantity) {
            throw ValidationException::withMessages(['product' => 'Not enough stock available']);
        }

        $cart = $this->getOrCreateCart($user);

        $item = $this->cartRepo->findOrCreateItem(cartId: $cart->id, productId: $product->id);

        $item->quantity += $quantity;

        return $this->cartRepo->save($item);
    }

    public function syncQuantitiesWithStock(Cart $cart)
    {
        // $cart->loadMissing(['cartItems.product', 'cartItems']);

        if ($cart->cartItems->isEmpty()) {
            return $cart;
        }

        foreach ($cart->cartItems as $item) {
            if (! $item->product) {
                $item->delete();

                continue;
            }

            $availableStock = $item->product->stock;

            if ($availableStock <= 0) {
                $item->delete();

                continue;
            }
            if ($item->quantity > $availableStock) {
                $item->update(['quantity' => $availableStock]);
            }
        }

        return $cart->fresh();
    }

    public function removeItem(CartItem $item)
    {
        return $this->cartRepo->delete($item);
    }

    public function removePurchaseItem(int $userId, array $productIds)
    {
        return CartItem::whereHas('cart', fn ($q) => $q->where('user_id', $userId))
            ->whereIn('product_id', $productIds)
            ->delete();
    }

    public function updateQuantity(CartItem $cartItem, int $quantity)
    {
        $this->cartRepo->update(model: $cartItem, data: ['quantity' => $quantity]);
    }
}

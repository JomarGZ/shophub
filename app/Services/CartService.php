<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Repositories\CartRepository;
use Illuminate\Support\Facades\DB;
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

    public function syncQuantitiesWithStock(Cart $cart)
    {
        $cart->loadMissing('cartItems.product');

        if ($cart->cartItems->isEmpty()) {
            return $cart;
        }

        foreach($cart->cartItems as $item) {
            if (!$item->product) {
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

        return $cart->fresh(['cartItems.product']);
    }
    public function removeItem(CartItem $item)
    {
        return $this->cartRepo->delete($item);
    }

    public function updateQuantity(CartItem $cartItem, int $quantity)
    {
        $this->cartRepo->update(model: $cartItem, data: ['quantity' => $quantity]);
    }

    public function calculateTotals(Cart $cart)
    {
        $subTotal = DB::table('cart_items')
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            ->where('cart_items.cart_id', $cart->id)
            ->where('products.stock', '>', 0)
            ->sum(DB::raw('LEAST(cart_items.quantity, products.stock) * products.price'));

        $shippingFee = config('cart.shipping_fee', 20);

        $total = $shippingFee + $subTotal;

        return [
            'subtotal' => $subTotal,
            'shipping_fee' => $shippingFee,
            'total' => $total,
        ];
    }
}

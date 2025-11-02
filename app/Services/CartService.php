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
            ->sum(DB::raw('cart_items.quantity * products.price'));

        $shippingFee = config('cart.shipping_fee', 20);

        $total = $shippingFee + $subTotal;

        return [
            'subtotal' => $subTotal,
            'shipping_fee' => $shippingFee,
            'total' => $total,
        ];
    }
}

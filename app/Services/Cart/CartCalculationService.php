<?php

namespace App\Services\Cart;

use App\Models\Cart;

class CartCalculationService
{
    public function calculate(Cart $cart)
    {
        $cart->loadMissing(['cartItems.product']);
        $items = $cart->cartItems->map(function ($item) {
            $product = $item->product;
            $price = $product->price;
            $quantity = min($item->quantity, $product->stock);
            $lineTotal = $price * $quantity;

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'quantity' => $item->quantity,
                'line_total' => $lineTotal,
            ];
        });

        $subtotal = $items->sum('line_total');
        $shippingFee = config('cart.shipping_fee', 20);
        $total = $subtotal + $shippingFee;

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'total' => $total,
        ];
    }
}

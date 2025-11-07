<?php

namespace App\Services\Cart;

use App\Models\Cart;

class CartCalculationService
{
    public function calculateTotals(Cart $cart)
    {
        $calculatedData = [
            'subtotal' => 0,
            'shipping_fee' => 0,
            'total' => 0,
        ];

        $cart->loadMissing(['cartItems.product']);

        if ($cart->cartItems->isEmpty()) {
            return $calculatedData;
        }   

        $subTotal = $cart->cartItems->sum(function ($item) {
            $quantity = min($item->quantity, $item->product->stock);
            return $quantity * $item->product->price;
        });

        $shippingFee = config('cart.shipping_fee', 20);
        $total = $subTotal + $shippingFee;

        $calculatedData['subtotal'] = $subTotal ?? 0;
        $calculatedData['shipping_fee'] = $shippingFee ?? 0;
        $calculatedData['total'] = $total ?? 0;

        return $calculatedData;
    }
}

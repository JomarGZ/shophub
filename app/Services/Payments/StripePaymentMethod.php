<?php

namespace App\Services\Payments;

use App\Models\Order;
use Laravel\Cashier\Cashier;

class StripePaymentMethod implements PaymentMethodInterface
{
    public function pay(Order $order)
    {
        $order->load('orderItems');
        $lineItems = $order->orderItems->map(function ($item) {
            return [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product_name,
                    ],
                    'unit_amount' => (int) ($item->product_price * 100),
                ],
                'quantity' => (int) $item->quantity,
            ];
        })->toArray();
        $checkout_session = Cashier::stripe()->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.store.success'),
        ]);

        return $checkout_session->url;
    }
}

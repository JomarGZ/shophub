<?php

namespace App\Services\Payments;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Stripe\Exception\ApiErrorException;

class StripePaymentMethod implements PaymentMethodInterface
{
    public function pay(Order $order)
    {
        $this->validateCheckoutRoutes();
        $order->load('orderItems');
        try {
            $lineItems = $this->prepareLineItems($order);
            $session = $this->createCheckoutSession($order, $lineItems);

            return $session->url;
        } catch (ApiErrorException $e) {
            Log::error('Stripe Checkout error', [
                'order_id' => $order->id,
                'exception' => $e,
            ]);

            return route('checkout.store.cancelled');
        } catch (Exception $e) {
            Log::error('General error during checkout', [
                'order_id' => $order->id,
                'exception' => $e,
            ]);

            return route('checkout.store.cancelled');
        }

    }

    private function validateCheckoutRoutes()
    {
        if (!Route::has('checkout.store.success') || !Route::has('checkout.store.cancelled')) {
            throw  new Exception('Checkout routes are missing.');
        }
    }

    private function prepareLineItems(Order $order)
    {
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

        $lineItems[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Shipping Fee',
                ],
                'unit_amount' => (int) ($order->shipping_fee * 100),
            ],
            'quantity' => 1,
        ];

        return $lineItems;
    }

    private function createCheckoutSession(Order $order, array $lineItems)
    {
        return request()->user()->checkout(
            $lineItems,
            [
                'mode' => 'payment',
                'metadata' => [
                    'order_id' => $order->id,
                ],
                'success_url' => route('checkout.store.success', [], true),
                'cancel_url' => route('checkout.store.cancelled', [], true),
            ]
        );
    }
}

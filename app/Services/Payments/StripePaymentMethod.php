<?php

namespace App\Services\Payments;

use App\Enums\PaymentMethod;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Checkout;
use RuntimeException;
use Stripe\Exception\ApiErrorException;

class StripePaymentMethod implements PaymentMethodInterface
{
    public function __construct(protected OrderRepository $orderRepository) {}
    public function pay(Order $order)
    {
        $this->validateCheckoutRoutes();
        $order->load('orderItems');
        try {
            $lineItems = $this->prepareLineItems($order);
            $session = $this->createCheckoutSession($order, $lineItems);
            
            if (!$session || !($session instanceof Checkout)) {
                throw new RuntimeException('Failed to create stripe checkout session.');
            }
            if ($order->external_reference !== $session->id) {
                $paymentStatus = StripePaymentMapper::toPaymentStatus($session->payment_status ?? null);
                $order->update([
                    'external_reference' => $session->id,
                    'payment_status' => $paymentStatus
                ]);
            }
           
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

    public function handleSuccess(Request $request)
    {
        $user = $request->user();
        $sessionId = $request->get('session_id');
        if (!$sessionId) {
            throw new \Exception('Missing session_id in request.');
        }
        try {
            $checkoutSession = $user->stripe()->checkout->sessions->retrieve($sessionId);
        } catch (Exception $e) {
            Log::error('Failed to retrieve Stripe session', [
                'session_id' => $sessionId,
                'exception' => $e,
            ]);
            throw new \Exception('Unable to retrieve Stripe session.');
        }
        $orderId = $checkoutSession->metadata->order_id ?? null;
        if (!$orderId) {

            Log::error('Checkout session missing order_id', [
                'session_id' => $checkoutSession->id
            ]);
            throw new \Exception('Order ID is missing from payment session.');
        }
        $order = $this->orderRepository->model()->with('orderItems')->find($orderId);

        if (!$order) {
            Log::error('Order not found for checkout session', [
                'session_id' => $checkoutSession->id,
                'order_id' => $orderId
            ]);

            throw new Exception('Order not found');
        }

        return [
            'order' => [
                'id' => $order->id,
                'total' => $order->total,
                'subtotal' => $order->subtotal,
                'shipping_fee' => $order->shipping_fee,
                'payment_status' => $order->payment_status,
                'created_at' => $order->created_at,
            ],
            'items' => $order->orderItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->product_name,
                    'product_price' => $item->product_price,
                    'quantity' => $item->quantity,
                    'total' => $item->line_total
                ];
            })
        
        ];
    }

    private function validateCheckoutRoutes()
    {
        if (! Route::has('checkout.store.success') || ! Route::has('checkout.store.cancelled')) {
            throw new Exception('Checkout routes are missing.');
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
                'success_url' => route('checkout.store.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}&type=' . PaymentMethod::STRIPE->value,
                'cancel_url' => route('checkout.store.cancelled', [], true),
            ]
        );
    }
}

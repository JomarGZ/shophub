<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Repositories\OrderRepository;
use App\Services\Payments\StripePaymentMapper;
use Illuminate\Support\Facades\Log;

class StripeWebHookService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected StockService $stockService,
        protected CartService $cartService,
        protected OrderService $orderService
    ) {}

    public function handleCheckoutCompleted(array $session)
    {
        $orderId = $session['metadata']['order_id'] ?? null;
        $sessionId = $session['session_id'] ?? null;

        if (! $orderId) {
            Log::error('Stripe webhook missing order_id in metadata', [
                'session_id' => $sessionId,
                'payload_metadata' => $session['metadata'] ?? null,
            ]);

            return;
        }

        $order = $this->orderRepository->model()->find($orderId);

        if (! $order) {
            Log::error('Order not found for stripe session', [
                'order_id' => $orderId,
                'session_id' => $sessionId,
            ]);

            return;
        }
        if ($order->payment_status == PaymentStatus::PAID->value) {
            Log::info('Order already processed', ['order_id' => $order->id]);

            return;
        }

        $paymentStatus = StripePaymentMapper::toPaymentStatus($session['payment_status'] ?? null);

        if ($paymentStatus === PaymentStatus::PAID) {
            $this->orderService->completeOrder(order: $order, data: $session, status: OrderStatus::PROCESSING, method: $order->payment_method);
        } else {

            $order->update([
                'status' => OrderStatus::AWAITING_PAYMENT,
                'payment_provider' => 'stripe',
                'transaction_id' => $session['payment_intent'] ?? null,
                'external_reference' => $session['id'],
                'payment_metadata' => json_encode([
                    'payment_method_types' => $session['payment_method_types'] ?? [],
                    'customer_email' => $session['customer_details']['email'] ?? null,
                ]),
            ]);

            Log::info('Order awaiting async payment', ['order_id' => $order->id]);
        }

    }

    public function handleAsyncPaymentFailed(array $session)
    {
        $this->logAsyncPaymentFailed($session);
        $orderId = $session['metadata']['order_id'] ?? null;
        if (! $orderId) {
            return;
        }
        $order = $this->orderRepository->model()->where('transaction_id', $session['session_id']);
        $order->update(['status' => OrderStatus::FAILED]);
        Log::warning('Order payment failed', ['order_id' => $order->id]);
    }

    public function handleAsyncPaymentSucceeded(array $session)
    {
        $this->logAsyncPaymentSucceeded($session);

        $orderId = $session['metadata']['order_id'] ?? null;
        if (! $orderId) {
            Log::error('Order ID not found in async payment session');

            return;
        }
        $order = $this->orderRepository->model()->find($orderId);

        if (! $order) {
            Log::error('Order not found for async payment', ['order_id' => $orderId]);

            return;
        }
        $this->orderService->completeOrder(order: $order, data: $session, status: OrderStatus::PROCESSING, method: $order->payment_method);
    }

    private function logAsyncPaymentSucceeded(array $session): void
    {
        Log::info('✅ ASYNC PAYMENT SUCCEEDED', [
            'session_id' => $session['id'] ?? null,
            'payment_intent' => $session['payment_intent'] ?? null,
            'customer_email' => $session['customer_details']['email'] ?? null,
            'amount_total' => ($session['amount_total'] ?? 0) / 100,
            'currency' => $session['currency'] ?? 'usd',
            'payment_method_types' => $session['payment_method_types'] ?? [],
            'message' => 'Delayed payment (bank transfer/Klarna/etc.) has been confirmed',
            'action_needed' => 'Update order status to PAID, reduce inventory, send success email, start fulfillment',
        ]);
    }

    private function logAsyncPaymentFailed(array $session): void
    {
        Log::warning('❌ ASYNC PAYMENT FAILED', [
            'session_id' => $session['id'] ?? null,
            'payment_intent' => $session['payment_intent'] ?? null,
            'customer_email' => $session['customer_details']['email'] ?? null,
            'amount_total' => ($session['amount_total'] ?? 0) / 100,
            'currency' => $session['currency'] ?? 'usd',
            'message' => 'Delayed payment was not completed or failed',
            'action_needed' => 'Mark order as FAILED, release reserved inventory, send payment failed email',
        ]);
    }
}

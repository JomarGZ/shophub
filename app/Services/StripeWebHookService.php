<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Services\Payments\StripePaymentMapper;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StripeWebHookService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected OrderRepository $orderRepository, 
        protected StockService $stockService,
        protected CartService $cartService
    ){}

    public function handleSuccessfulSession(array $session)
    {
        $orderId = $session['metadata']['order_id'] ?? null;
        $sessionId = $session['session_id'] ?? null;

        if (!$orderId) {
            Log::error('Stripe webhook missing order_id in metadata', [
                'session_id' => $sessionId,
                'payload_metadata' => $session['metadata'] ?? null
            ]);
            return;
        }

        $order = $this->orderRepository->model()->find($orderId);

        if (! $order) {
            Log::error('Order not found for stripe session', [
                'order_id' => $orderId,
                'session_id' => $sessionId
            ]);
            return;
        }
        if ($order->payment_status == PaymentStatus::PAID->value) {
            Log::info('Order already processed', ['order_id' => $order->id]);
            return;
        }

        $paymentStatus = StripePaymentMapper::toPaymentStatus($session['payment_status'] ?? null);

        if ($paymentStatus === PaymentStatus::PAID) {
            $this->completeOrder($order, $session);
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

    public function handleFailedSession(Object $data)
    {
        $order = $this->orderRepository->model()->where('transaction_id', $data['session_id']);
        info('failed transaction', [
            'order' => $order
        ]);
    }

    private function completeOrder(Order $order, array $session)
    {
        $status = StripePaymentMapper::toPaymentStatus($session['payment_status'] ?? null);
        try {
            DB::transaction(function () use ($order, $session, $status) {
                $order->update([
                    'payment_status' => $status->value,
                    'status' => OrderStatus::PROCESSING,
                    'transaction_id' => $session['payment_intent'] ?? null,
                    'external_reference' => $session['id'] ?? null,
                    'paid_at' => now()
                ]);
                $this->stockService->decrementOrderStock($order);
                $this->cartService->removePurchaseItem($order->user_id, $order->orderItems->pluck('product_id')->toArray());

                Log::info('Order Completed', [
                    'order_id' => $order->id,
                    'amount' => $order->total,
                    'provider' => 'stripe'
                ]);
            });
        } catch (Exception $e) {
            Log::error('Failed to complete order', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }

    }
}

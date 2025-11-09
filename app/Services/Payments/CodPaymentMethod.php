<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Repositories\PaymentRepository;

class CodPaymentMethod implements PaymentMethodInterface
{
    public function __construct(protected PaymentRepository $paymentRepository) {}

    public function pay(Order $order)
    {
        $this->paymentRepository->create([
            'order_id' => $order->id,
            'provider' => $order->payment_method,
            'amount' => $order->total,
        ]);
    }
}

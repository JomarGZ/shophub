<?php

namespace App\Services\Payments;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class CodPaymentMethod implements PaymentMethodInterface
{
    public function __construct(protected OrderService $orderService) {}

    public function pay(Order $order)
    {
        $data = [
            'payment_status' => PaymentStatus::UNPAID->value,
        ];
        $this->orderService->completeOrder(order: $order, data: $data, status: OrderStatus::PENDING);
    }

    public function handleSuccess(Request $request)
    {
        throw new \Exception('Not implemented');
    }
}

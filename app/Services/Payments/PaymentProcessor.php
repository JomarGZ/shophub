<?php

namespace App\Services\Payments;

use App\Models\Order;

class PaymentProcessor
{
    public function __construct(protected PaymentMethodInterface $method) {}

    public function handle(Order $order): void
    {
        $this->method->pay($order);
    }
}

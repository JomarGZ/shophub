<?php

namespace App\Services\Payments;

use App\Models\Order;

class PaymentProcessor
{
    protected PaymentMethodInterface $method;

    public function setMethod(PaymentMethodInterface $method): void
    {
        $this->method = $method;
    }

    public function handle(Order $order)
    {
        $this->method->pay($order);
    }
}

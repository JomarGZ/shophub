<?php

namespace App\Services\Payments;

use App\Enums\PaymentMethod;
use App\Models\Order;
use InvalidArgumentException;

class PaymentProcessor
{
    public function __construct(protected PaymentMethodInterface $method){}

    public function handle(Order $order): void
    {
        $this->method->pay($order);
    }
}

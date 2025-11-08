<?php

namespace App\Services\Payments;

use App\Enums\PaymentMethod;
use App\Models\Order;
use InvalidArgumentException;

class PaymentProcessor
{
    protected PaymentMethodInterface $method;

    public function setMethod(PaymentMethodInterface $method): void
    {
        $this->method = $method;
    }

    public function make(PaymentMethod $method): static
    {
        $this->method = match ($method) {
            PaymentMethod::COD => new CodPaymentMethod(),
            default => throw new InvalidArgumentException('Unsupported payment method')
        };

        return $this;
    }

    public function handle(Order $order): void
    {
        if (! isset($this->method)) {
            throw new InvalidArgumentException('Payment method not set');
        }

        $this->method->pay($order);
    }
}

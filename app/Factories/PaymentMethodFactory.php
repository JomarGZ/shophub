<?php

namespace App\Factories;

use App\Enums\PaymentMethod;
use App\Services\Payments\CodPaymentMethod;
use App\Services\Payments\PaymentMethodInterface;
use InvalidArgumentException;

class PaymentMethodFactory
{
    public static function make(PaymentMethod $method): PaymentMethodInterface
    {
        return match ($method) {
            PaymentMethod::COD => new CodPaymentMethod(),
            default => throw new InvalidArgumentException('Unsupported payment method')
        };
    }
}

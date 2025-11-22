<?php

namespace App\Factories;

use App\Enums\PaymentMethod;
use App\Services\Payments\CodPaymentMethod;
use App\Services\Payments\PaymentMethodInterface;
use App\Services\Payments\StripePaymentMethod;
use InvalidArgumentException;

class PaymentMethodFactory
{
    public static function make(PaymentMethod $method): PaymentMethodInterface
    {
        return match ($method) {
            PaymentMethod::COD => app(CodPaymentMethod::class),
            PaymentMethod::STRIPE => app(StripePaymentMethod::class),
            default => throw new InvalidArgumentException('Unsupported payment method')
        };
    }
}

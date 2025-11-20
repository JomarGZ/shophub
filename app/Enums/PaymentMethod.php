<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case COD = 'cod';
    case STRIPE = 'stripe';

    public function isOnline(): bool
    {
        return in_array($this, [self::STRIPE]);
    }
    public function label(): string
    {
        return match ($this) {
            self::COD => 'Cash On Delivery',
            self::STRIPE => 'Stripe (Credit/Debit Card)',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::COD => 'Pay when you receive your order',
            self::STRIPE => 'Secure card payment processed via Stripe',
        };
    }
}

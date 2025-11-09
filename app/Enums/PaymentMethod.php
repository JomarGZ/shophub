<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case COD = 'cod';

    public function label(): string
    {
        return match ($this) {
            self::COD => 'Cash On Delivery',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::COD => 'Pay when you receive your order',
        };
    }
}

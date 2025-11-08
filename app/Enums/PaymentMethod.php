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
}

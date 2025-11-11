<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case UNPAID = 'unpaid';
    case PAID = 'paid';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';

    public function color()
    {
        return match ($this) {
            self::UNPAID => 'gray',
            self::PAID => 'success',
            self::FAILED => 'danger',
            self::REFUNDED => 'yellow',
            self::REJECTED => 'danger',
            self::CANCELLED => 'gray',
            default => 'gray'
        };
    }
}

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
            self::UNPAID => 'muted',         // Gray
            self::PAID => 'success',         // Green
            self::FAILED => 'destructive',   // Red
            self::REFUNDED => 'warning',     // Amber
            self::REJECTED => 'destructive', // Red
            self::CANCELLED => 'neutral',    // Slate
            default => 'muted',              // Gray (fallback)
        };
    }
}

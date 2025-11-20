<?php

namespace App\Services\Payments;

use App\Enums\PaymentStatus;

class StripePaymentMapper
{
   public static function toPaymentStatus(?string $status)
   {
        return match ($status) {
            'paid' => PaymentStatus::PAID,
            'unpaid' => PaymentStatus::UNPAID,
            default => PaymentStatus::PENDING
        };
   }
}

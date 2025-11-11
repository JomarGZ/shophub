<?php

namespace App\Services;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Payment;

class PaymentService
{
    public function all()
    {
        return collect(PaymentMethod::cases())->map(fn ($method) => [
            'value' => $method->value,
            'label' => $method->label(),
            'description' => $method->description(),
            'is_default' => $method->value === PaymentMethod::COD->value,
        ]);
    }

    public function markAsPaid(Payment $payment): bool
    {
        if ($payment->status === PaymentStatus::PAID || $payment->order->payment_method !== PaymentMethod::COD) {
            return true;
        }
        $payment->status = PaymentStatus::PAID;
        return $payment->save();
    }
}

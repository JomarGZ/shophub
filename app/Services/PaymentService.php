<?php

namespace App\Services;

use App\Enums\PaymentMethod;

class PaymentService
{
    protected array $drivers = [];

    public function all()
    {
        return collect(PaymentMethod::cases())->map(fn ($method) => [
            'value' => $method->value,
            'label' => $method->label(),
            'description' => $method->description(),
            'is_default' => $method->value === PaymentMethod::COD->value,
        ]);
    }
}

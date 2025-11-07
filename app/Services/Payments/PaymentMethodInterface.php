<?php

namespace App\Services\Payments;

use App\Models\Order;

interface PaymentMethodInterface
{
    public function pay(Order $order): void;
}

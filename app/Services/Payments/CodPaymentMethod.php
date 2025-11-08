<?php

namespace App\Services\Payments;

use App\Models\Order;

class CodPaymentMethod implements PaymentMethodInterface
{
    public function pay(Order $order): void
    {
       echo 'Place order successfully';
    }
}

<?php

namespace App\Services\Payments;

use App\Models\Order;

interface PaymentMethodInterface
{
    /**
     * Process the payment.
     *
     * @return mixed
     */
    public function pay(Order $order);
}

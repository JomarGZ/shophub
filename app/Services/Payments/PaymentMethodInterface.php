<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Http\Request;

interface PaymentMethodInterface
{
    public function pay(Order $order);

    public function handleSuccess(Request $request);
}

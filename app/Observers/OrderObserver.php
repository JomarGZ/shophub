<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{
    public function creating(Order $order)
    {
        if (! $order->user_id && Auth::check()) {
            $order->user_id = Auth::id();
        }
    }
}

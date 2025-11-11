<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderPlacedCOD;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        User::admin()->notify(new OrderPlacedCOD($order));
    }

    public function creating(Order $order)
    {
        if (!$order->user_id && Auth::check()) {
            $order->user_id = Auth::id();
        }
    }
}

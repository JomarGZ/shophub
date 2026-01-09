<?php

namespace App\Domain\Orders\States;

use App\Enums\OrderStatus;

class PendingOrderState extends BaseOrderState
{
    public function cancel(): void
    {
        $this->order->setState(new CancelledOrderState($this->order));
    }

    public function status(): OrderStatus
    {
        return OrderStatus::PENDING;
    }
}

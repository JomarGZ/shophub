<?php

namespace App\Domain\Orders\States;

use App\Enums\OrderStatus;

class PendingOrderState extends BaseOrderState
{
    public function cancel(): void
    {
        $this->order->setState(new CancelledOrderState($this->order));
    }

    public function getName(): string
    {
        return OrderStatus::PENDING->value;
    }
}

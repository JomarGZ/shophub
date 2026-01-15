<?php

namespace App\Domain\Orders\States;

use App\Enums\OrderStatus;

class OutForDeliveryOrderState extends BaseOrderState
{
    public function deliver(): void
    {
        $this->order->setState(new DeliveredOrderState($this->order));
    }

    public function status(): OrderStatus
    {
        return OrderStatus::OUT_FOR_DELIVERY;
    }
}

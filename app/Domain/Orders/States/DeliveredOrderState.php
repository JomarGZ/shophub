<?php

namespace App\Domain\Orders\States;

use App\Enums\OrderStatus;

class DeliveredOrderState extends BaseOrderState
{
    public function status(): OrderStatus
    {
        return OrderStatus::DELIVERED;
    }
}

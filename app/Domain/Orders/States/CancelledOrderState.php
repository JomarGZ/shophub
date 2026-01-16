<?php

namespace App\Domain\Orders\States;

use App\Enums\OrderStatus;

class CancelledOrderState extends BaseOrderState
{
    public function status(): OrderStatus
    {
        return OrderStatus::CANCELLED;
    }
}

<?php

namespace App\Domain\Orders\States;

use App\Enums\OrderStatus;

class DeliveredOrderState extends BaseOrderState
{
    public function getName(): string
    {
        return OrderStatus::DELIVERED->value;
    }
}

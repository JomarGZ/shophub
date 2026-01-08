<?php

namespace App\Domain\Orders\States;

use App\Enums\OrderStatus;

class CancelledOrderState extends BaseOrderState
{
    public function getName(): string
    {
        return OrderStatus::CANCELLED->value;
    }
}

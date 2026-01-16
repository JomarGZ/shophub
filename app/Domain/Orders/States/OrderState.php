<?php

namespace App\Domain\Orders\States;

use App\Enums\OrderStatus;

interface OrderState
{
    public function cancel(): void;

    public function deliver(): void;

    public function status(): OrderStatus;
}

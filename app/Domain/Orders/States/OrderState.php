<?php

namespace App\Domain\Orders\States;

interface OrderState
{
    public function cancel(): void;
    public function deliver(): void;
    public function getName(): string;
}

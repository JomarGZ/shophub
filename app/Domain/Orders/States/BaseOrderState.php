<?php

namespace App\Domain\Orders\States;

use App\Models\Order;
use DomainException;

abstract class BaseOrderState implements OrderState
{
   protected Order $order;

   public function __construct(Order $order)
   {
        $this->order = $order;
   }

   public function cancel(): void
   {
        throw new DomainException(
            "Cannot cancel an order in the '{$this->getName()}' state."
        );
   }

   public function deliver(): void
   {
        throw new DomainException(
            "Cannot deliver an order in the '{$this->getName()}' state."
        );
   }

}

<?php

namespace App\Domain\Orders\Factories;

use App\Domain\Orders\States\CancelledOrderState;
use App\Domain\Orders\States\DeliveredOrderState;
use App\Domain\Orders\States\OrderState;
use App\Domain\Orders\States\OutForDeliveryOrderState;
use App\Domain\Orders\States\PendingOrderState;
use App\Enums\OrderStatus;
use App\Models\Order;

class OrderStateFactory
{
    public static function from(Order $order, OrderStatus $status)
    {
        return match($status) {
            OrderStatus::PENDING => new PendingOrderState($order),
            OrderStatus::OUT_FOR_DELIVERY => new OutForDeliveryOrderState($order),
            OrderStatus::DELIVERED => new DeliveredOrderState($order),
            OrderStatus::CANCELLED => new CancelledOrderState($order),
        };
    }
}

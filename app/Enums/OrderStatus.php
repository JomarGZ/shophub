<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case REJECTED = 'rejected';
    case PROCESSING = 'processing';
    case PREPARING_FOR_SHIPMENT = 'preparing_for_shipment';
    case SHIPPED = 'shipped';
    case OUT_FOR_DELIVERY = 'out_for_delivery';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
    case FAILED = 'failed';
    case AWAITING_PAYMENT = 'awaiting_payment';

    public function label()
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::SHIPPED => 'Shipped',
            self::DELIVERED => 'Delivered',
            self::CANCELLED => 'Cancelled',
            self::FAILED => 'Failed',
            self::REJECTED => 'Rejected',
            self::PREPARING_FOR_SHIPMENT => 'Preparing for Shipment',
            self::AWAITING_PAYMENT => 'Awaiting Payment',
            self::OUT_FOR_DELIVERY => 'Out For Delivery'
        };
    }

    public function color()
    {
        return match ($this) {
            self::PENDING => 'warning',          // Amber
            self::PROCESSING => 'info',          // Blue
            self::PREPARING_FOR_SHIPMENT => 'secondary', // Cyan
            self::SHIPPED => 'accent',           // Cyan (alternate tone)
            self::OUT_FOR_DELIVERY => 'neutral', // Slate
            self::DELIVERED => 'success',        // Green
            self::CANCELLED => 'primary',        // Orange
            self::FAILED => 'destructive',        // Orange
            self::REJECTED => 'destructive',     // Red
            self::AWAITING_PAYMENT => 'warning',
            default => 'muted',                  // Gray (fallback)
        };
    }

    public static function options()
    {
        return array_combine(
            array_map(fn ($status) => $status->value, self::cases()),
            array_map(fn ($status) => $status->label(), self::cases())
        );
    }

    public static function fullOptions()
    {
        return array_map(
            fn (self $status) => [
                'value' => $status->value,
                'label' => $status->label(),
                'color' => $status->color(),
            ],
            self::cases()
        );
    }
}

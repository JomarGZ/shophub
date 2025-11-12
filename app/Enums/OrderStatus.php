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

    public function label()
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::SHIPPED => 'Shipped',
            self::DELIVERED => 'Delivered',
            self::CANCELLED => 'Cancelled',
            self::REJECTED => 'Rejected',
            self::PREPARING_FOR_SHIPMENT => 'Preparing for Shipment',
            self::OUT_FOR_DELIVERY => 'Out For Delivery'
        };
    }

    public function color()
    {
        return match ($this) {
            self::PENDING => 'amber',
            self::PROCESSING => 'blue',
            self::PREPARING_FOR_SHIPMENT => 'cyan',
            self::SHIPPED => 'slate',
            self::OUT_FOR_DELIVERY => 'sky',
            self::DELIVERED => 'green',
            self::CANCELLED => 'red',
            self::REJECTED => 'rose',
            default => 'gray'
        };
    }

    public static function options()
    {
        return array_combine(
            array_map(fn ($status) => $status->value, self::cases()),
            array_map(fn ($status) => $status->label(), self::cases())
        );
    }
}

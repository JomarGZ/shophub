<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case REJECTED = 'rejected';
    case PROCESSING = 'processing';
    case PREPARING_FOR_SHIPMENT = 'preparing_for_shipment';
    case SHIPPED = 'shipped';
    case OUT_FOR_DELIVERY = 'out_for_Delivery';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
}

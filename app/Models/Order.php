<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'status',
        'subtotal',
        'shipping_fee',
        'shipping_phone',
        'discount',
        'total',
        'rejection_reason',
        'payment_method',
        'shipping_full_name',
        'shipping_city',
        'shipping_country',
        'shipping_street_address',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

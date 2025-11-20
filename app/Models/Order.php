<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

#[ObservedBy(OrderObserver::class)]
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
        'transaction_id',
        'external_reference',
        'payment_metadata',
        'paid_at',
        'refund_amount',
        'payment_status',
        'refund_at',
    ];

    protected function casts()
    {
        return [
            'status' => OrderStatus::class,
            'payment_status' => PaymentStatus::class,
            'payment_method' => PaymentMethod::class,
            'payment_metadata' => 'array',
        ];
    }

    public static function booted()
    {
        static::addGlobalScope('user_orders', function (Builder $query) {
            if (Auth::check()) {
                $query->where('user_id', Auth::id());
            }
        });
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isCOD(): bool
    {
        return $this->payment_method === PaymentMethod::COD;
    }
}

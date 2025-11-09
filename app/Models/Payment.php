<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'provider',
        'status',
        'amount',
        'currency',
        'transaction_id',
        'payment_reference',
        'response_payload',
        'paid_at',
    ];

    protected $attributes = [
        'status' => PaymentStatus::UNPAID,
    ];

    protected $casts = [
        'provider' => PaymentMethod::class,
        'status' => PaymentStatus::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

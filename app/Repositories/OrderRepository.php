<?php

namespace App\Repositories;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Pagination\Paginator;

class OrderRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(new Order);
    }

    public function simplePaginate(int $perPage = 15, array $columns = ['*'], array|string $relations = []): Paginator
    {
        auth()->user()->load('ratings');
        return $this->query()->with($relations)->latest()->simplePaginate($perPage, $columns);
    }

    public function updateStatus(Order $order, OrderStatus $newStatus)
    {
        $rules = [
            OrderStatus::CANCELLED->value => fn () => $order->status === OrderStatus::PENDING,
            OrderStatus::DELIVERED->value => fn () => $order->status === OrderStatus::OUT_FOR_DELIVERY,
        ];

        if (! isset($rules[$newStatus->value]) || ! $rules[$newStatus->value]()) {
            abort(403, 'Invalid status transition.');
        }

        $order->update(['status' => $newStatus->value]);
    }
}

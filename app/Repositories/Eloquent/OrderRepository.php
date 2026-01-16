<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function save(Order $order): void
    {
        if (! $order->save()) {
            throw new \DomainException('Failed to save the order.');
        }
    }

    public function getUserOrdersWithRatings(int $perPage = 15, array $columns = ['*'], ?User $user = null)
    {
        return $this->model->query()
            ->with([
                'orderItems:id,order_id,product_id,product_name,product_price,line_total,quantity',
                'orderItems.product:id,slug',
                'orderItems.product.ratings' => function ($query) use ($user) {
                    $query->where('user_id', $user ? $user->id : null)
                        ->select('id', 'product_id', 'user_id', 'rating');
                },
            ])
            ->latest()
            ->paginate($perPage, $columns);
    }

    public function find(int $id): ?Order
    {
        return $this->model->with(['orderItems'])->find($id);
    }
}

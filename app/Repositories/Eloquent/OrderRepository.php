<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\OrderRepositoryInterface;
use DomainException;

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
        if (!$order->save()) {
            throw new \DomainException('Failed to save the order.');
        }
    }
    public function getUserOrdersWithRatings(int $perPage = 15, array $columns = ['*'])
    {
        return $this->model->query()
            ->with([
                'orderItems:id,order_id,product_id,product_name,product_price,line_total,quantity',
                'orderItems.product:id,slug',
                'orderItems.product.ratings' => function ($query) {
                    $query->where('user_id', auth()->check() ? auth()->id() : null)
                          ->select('id', 'product_id', 'user_id');
                }
            ])
            ->latest()
            ->paginate($perPage, $columns);
    }
    
}

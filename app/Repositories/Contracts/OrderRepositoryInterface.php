<?php

namespace App\Repositories\Contracts;

use App\Enums\OrderStatus;
use App\Models\Order;

interface OrderRepositoryInterface extends RepositoryInterface
{
    // Define any additional methods specific to OrderRepository here
    public function updateStatus(Order $order, OrderStatus $newStatus);
    public function getUserOrdersWithRatings(int $perPage = 15, array $columns = ['*']);
}

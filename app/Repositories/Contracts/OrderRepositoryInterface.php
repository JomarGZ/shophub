<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use App\Models\User;

interface OrderRepositoryInterface extends RepositoryInterface
{
    // Define any additional methods specific to OrderRepository here
    public function getUserOrdersWithRatings(int $perPage = 15, array $columns = ['*'], ?User $user = null);

    public function save(Order $order): void;
}

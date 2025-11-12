<?php

namespace App\Repositories;

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
        return $this->query()->with($relations)->simplePaginate($perPage, $columns);
    }
}

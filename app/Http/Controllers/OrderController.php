<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Inertia\Inertia;

class OrderController extends Controller
{

    public function __construct(protected OrderService $orderService)
    {
        
    }
    public function index()
    {
        return Inertia::render('orders/index', [
            'orders' => [
                [
                    'id' => 'ORD-001',
                    'customer' => 'John Smith',
                    'date' => '2024-01-15',
                    'total' => 289.97,
                    'status' => 'delivered',
                    'items' => [
                        ['productName' => 'Wireless Headphones', 'quantity' => 2, 'price' => 79.99],
                        ['productName' => 'Yoga Mat', 'quantity' => 1, 'price' => 39.99],
                        ['productName' => 'Water Bottle', 'quantity' => 4, 'price' => 24.99],
                    ],
                ],
                [
                    'id' => 'ORD-002',
                    'customer' => 'Emma Wilson',
                    'date' => '2024-01-14',
                    'total' => 79.99,
                    'status' => 'pending',
                    'items' => [
                        ['productName' => 'Wireless Headphones', 'quantity' => 1, 'price' => 79.99],
                    ],
                ],
                [
                    'id' => 'ORD-003',
                    'customer' => 'Michael Brown',
                    'date' => '2024-01-14',
                    'total' => 159.98,
                    'status' => 'shipped',
                    'items' => [
                        ['productName' => 'Smart Watch', 'quantity' => 1, 'price' => 199.99],
                    ],
                ],
                [
                    'id' => 'ORD-004',
                    'customer' => 'Sarah Davis',
                    'date' => '2024-01-13',
                    'total' => 449.95,
                    'status' => 'pending',
                    'items' => [
                        ['productName' => 'Running Shoes', 'quantity' => 2, 'price' => 129.99],
                        ['productName' => 'Coffee Maker', 'quantity' => 1, 'price' => 149.99],
                        ['productName' => 'Desk Lamp', 'quantity' => 1, 'price' => 49.99],
                    ],
                ],
                [
                    'id' => 'ORD-005',
                    'customer' => 'James Johnson',
                    'date' => '2024-01-12',
                    'total' => 199.99,
                    'status' => 'cancelled',
                    'items' => [
                        ['productName' => 'Smart Watch', 'quantity' => 1, 'price' => 199.99],
                    ],
                ],
            ],
        ]);
    }

    public function store(StoreOrderRequest $request)
    {
        $this->orderService->execute(request()->user(), $request->validated());

        return redirect()->route('orders.index')->with('message', 'Order is placed successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected OrderRepository $orderRepository
    ) {}

    public function index()
    {
        $orders2 = $this->orderRepository->simplePaginate(
            perPage: 3,
            columns: ['id', 'shipping_full_name', 'payment_method', 'created_at', 'shipping_fee', 'total', 'shipping_city', 'shipping_country', 'shipping_street_address'],
            relations: ['orderItems:product_name,product_price,line_total,quantity', 'payment:status']
        );
        $orders = [
            [
                'id' => 'ORD-001',
                'customer' => 'John Smith',
                'date' => '2024-01-15',
                'total' => 289.97,
                'status' => 'delivered',
                'paymentStatus' => 'paid',
                'paymentMethod' => 'Credit Card',
                'shippingFee' => 10.00,
                'address' => [
                    'street' => '123 Main Street',
                    'city' => 'New York',
                    'zipCode' => '10001',
                ],
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
                'paymentStatus' => 'unpaid',
                'paymentMethod' => 'PayPal',
                'shippingFee' => 5.00,
                'address' => [
                    'street' => '456 Oak Avenue',
                    'city' => 'Los Angeles',
                    'zipCode' => '90001',
                ],
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
                'paymentStatus' => 'paid',
                'paymentMethod' => 'Debit Card',
                'shippingFee' => 8.00,
                'address' => [
                    'street' => '789 Pine Road',
                    'city' => 'Chicago',
                    'zipCode' => '60601',
                ],
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
                'paymentStatus' => 'paid',
                'paymentMethod' => 'Credit Card',
                'shippingFee' => 12.00,
                'address' => [
                    'street' => '321 Elm Street',
                    'city' => 'Houston',
                    'zipCode' => '77001',
                ],
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
                'paymentStatus' => 'rejected',
                'paymentMethod' => 'Credit Card',
                'shippingFee' => 7.00,
                'address' => [
                    'street' => '555 Maple Drive',
                    'city' => 'Phoenix',
                    'zipCode' => '85001',
                ],
                'items' => [
                    ['productName' => 'Smart Watch', 'quantity' => 1, 'price' => 199.99],
                ],
            ],
            [
                'id' => 'ORD-006',
                'customer' => 'Lisa Anderson',
                'date' => '2024-01-11',
                'total' => 269.96,
                'status' => 'delivered',
                'paymentStatus' => 'paid',
                'paymentMethod' => 'Credit Card',
                'shippingFee' => 9.00,
                'address' => [
                    'street' => '888 Cedar Lane',
                    'city' => 'Miami',
                    'zipCode' => '33101',
                ],
                'items' => [
                    ['productName' => 'Running Shoes', 'quantity' => 1, 'price' => 129.99],
                    ['productName' => 'Coffee Maker', 'quantity' => 1, 'price' => 149.99],
                ],
            ],
            [
                'id' => 'ORD-007',
                'customer' => 'David Martinez',
                'date' => '2024-01-10',
                'total' => 139.98,
                'status' => 'shipped',
                'paymentStatus' => 'paid',
                'paymentMethod' => 'PayPal',
                'shippingFee' => 6.00,
                'address' => [
                    'street' => '222 Birch Street',
                    'city' => 'Seattle',
                    'zipCode' => '98101',
                ],
                'items' => [
                    ['productName' => 'Wireless Headphones', 'quantity' => 1, 'price' => 79.99],
                    ['productName' => 'Desk Lamp', 'quantity' => 1, 'price' => 49.99],
                ],
            ],
            [
                'id' => 'ORD-008',
                'customer' => 'Amanda White',
                'date' => '2024-01-09',
                'total' => 164.98,
                'status' => 'pending',
                'paymentStatus' => 'paid',
                'paymentMethod' => 'Debit Card',
                'shippingFee' => 8.00,
                'address' => [
                    'street' => '999 Willow Way',
                    'city' => 'Denver',
                    'zipCode' => '80201',
                ],
                'items' => [
                    ['productName' => 'Yoga Mat', 'quantity' => 2, 'price' => 39.99],
                    ['productName' => 'Water Bottle', 'quantity' => 3, 'price' => 24.99],
                ],
            ],
            [
                'id' => 'ORD-009',
                'customer' => 'Chris Taylor',
                'date' => '2024-01-08',
                'total' => 319.97,
                'status' => 'delivered',
                'paymentStatus' => 'paid',
                'paymentMethod' => 'Credit Card',
                'shippingFee' => 11.00,
                'address' => [
                    'street' => '444 Spruce Avenue',
                    'city' => 'Boston',
                    'zipCode' => '02101',
                ],
                'items' => [
                    ['productName' => 'Smart Watch', 'quantity' => 1, 'price' => 199.99],
                    ['productName' => 'Leather Backpack', 'quantity' => 1, 'price' => 89.99],
                ],
            ],
            [
                'id' => 'ORD-010',
                'customer' => 'Rachel Green',
                'date' => '2024-01-07',
                'total' => 104.97,
                'status' => 'shipped',
                'paymentStatus' => 'paid',
                'paymentMethod' => 'PayPal',
                'shippingFee' => 5.00,
                'address' => [
                    'street' => '777 Ash Road',
                    'city' => 'San Francisco',
                    'zipCode' => '94101',
                ],
                'items' => [
                    ['productName' => 'Yoga Mat', 'quantity' => 1, 'price' => 39.99],
                    ['productName' => 'Water Bottle', 'quantity' => 2, 'price' => 24.99],
                ],
            ],
        ];

        return Inertia::render('orders/index', [
            'orders' => $orders,
            'orders2' => fn () => OrderResource::collection($orders2),
        ]);
    }

    public function store(StoreOrderRequest $request)
    {
        $this->orderService->execute(request()->user(), $request->validated());

        return redirect()->route('orders.index')->with('message', 'Order is placed successfully');
    }
}

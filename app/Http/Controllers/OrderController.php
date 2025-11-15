<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected OrderRepository $orderRepository
    ) {}

    public function index()
    {
        $orders = $this->orderRepository->simplePaginate(
            perPage: 10,
            columns: ['id', 'shipping_full_name', 'status', 'payment_method', 'created_at', 'shipping_fee', 'total', 'shipping_city', 'shipping_country', 'shipping_street_address'],
            relations: [
                'orderItems:id,order_id,product_name,product_price,line_total,quantity',
                'payment:id,order_id,status',
            ]
        );

        return Inertia::render('orders/index', [
            'orders' => [
                'data' => fn () => OrderResource::collection($orders)->resolve(),
                'next_page_url' => $orders->nextPageUrl(),
                'has_more' => $orders->hasMorePages(),
            ],
            'order_statuses' => array_column(
                OrderStatus::cases(),
                'value',
                'name'
            )
        ]);
    }

    public function store(StoreOrderRequest $request)
    {
        $this->orderService->execute(request()->user(), $request->validated());

        return redirect()->route('orders.index')->with('success', 'Order is placed successfully');
    }

    public function update(Order $order, Request $request)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([OrderStatus::CANCELLED, OrderStatus::DELIVERED])]
        ]);

        $newStatus = OrderStatus::from($validated['status']);
        
        $this->orderRepository->updateStatus($order, $newStatus);

        return redirect()->back()->with('success', 'Order status updated successfully');
    }
}

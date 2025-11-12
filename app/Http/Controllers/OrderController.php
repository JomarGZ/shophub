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
        $orders = $this->orderRepository->simplePaginate(
            columns: ['id', 'shipping_full_name', 'status', 'payment_method', 'created_at', 'shipping_fee', 'total', 'shipping_city', 'shipping_country', 'shipping_street_address'],
            relations: [
                'orderItems:id,order_id,product_name,product_price,line_total,quantity', 
                'payment:id,order_id,status'
            ]
        );

        return Inertia::render('orders/index', [
            'orders' => fn () => OrderResource::collection($orders),
        ]);
    }

    public function store(StoreOrderRequest $request)
    {
        $this->orderService->execute(request()->user(), $request->validated());

        return redirect()->route('orders.index')->with('message', 'Order is placed successfully');
    }
}

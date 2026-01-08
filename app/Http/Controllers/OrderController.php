<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Eloquent\OrderRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected OrderRepositoryInterface $orderRepository
    ) {}

    public function index()
    {
        $orders = $this->orderRepository->getUserOrdersWithRatings(
            perPage: 10,
            columns: ['id', 'shipping_full_name', 'status', 'payment_status', 'payment_method', 'created_at', 'shipping_fee', 'total', 'shipping_city', 'shipping_country', 'shipping_street_address']
        );
      
        return Inertia::render('orders/index', [
            'orders' => fn () => OrderResource::collection($orders),
            'order_statuses' => OrderStatus::fullOptions(),
        ]);
    }

    public function store(StoreOrderRequest $request)
    {
        $result = $this->orderService->execute(request()->user(), $request->validated());
        $paymentUrl = isset($result['payment_url']) ? $result['payment_url'] : null;
        $isValidPaymentUrl = $paymentUrl && Str::isUrl($paymentUrl, ['https']);
        if ($isValidPaymentUrl) {
            return Inertia::location($paymentUrl);
        }

        return redirect()->route('orders.index')->with('success', 'Order is placed successfully');
    }

    public function update(Order $order, Request $request)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([OrderStatus::CANCELLED, OrderStatus::DELIVERED])],
        ]);

        $newStatus = OrderStatus::from($validated['status']);

        $this->orderRepository->updateStatus($order, $newStatus);

        return redirect()->back()->with('success', 'Order status updated successfully');
    }
}

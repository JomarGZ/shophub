<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Factories\PaymentMethodFactory;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\AddressResource;
use App\Repositories\AddressRepository;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Services\Cart\CartCalculationService;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Nnjeim\World\World;

class CheckoutController extends Controller
{
    public function __construct(
        protected AddressRepository $addressRepository,
        protected CartRepository $cartRepository,
        protected CartService $cartService,
        protected OrderService $orderService,
        protected OrderRepository $orderRepository,
        protected CartCalculationService $cartCalculationService
    ) {}

    public function index()
    {
        $countries = World::countries();
        $cart = $this->cartService->getOrCreateCart(request()->user());
        $this->cartService->syncQuantitiesWithStock($cart);
        $cartItems = $this->cartRepository->getItemsInStock($cart, relations: ['product']);
        $cartTotals = $this->cartCalculationService->calculate($cart);
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index');
        }
        $orderSummary = [
            'items' => $cartItems,
            'subtotal' => (int) $cartTotals['subtotal'],
            'shipping_fee' => (int) $cartTotals['shipping_fee'],
            'total' => (int) $cartTotals['total'],
        ];

        $paymentMethods = app(PaymentService::class)->all();

        return Inertia::render('checkout/index', [
            'addresses' => fn () => AddressResource::collection($this->addressRepository->getAllForUser(auth()->id())),
            'countries' => $countries->success ? $countries->data : [],
            'order_summary' => $orderSummary,
            'payment_methods' => $paymentMethods,
        ]);
    }

    public function store(StoreOrderRequest $request)
    {
        $result = $this->orderService->execute(request()->user(), $request->validated());
        $paymentUrl = isset($result['payment_url']) ? $result['payment_url'] : null;
        $protocols = config('payment.url_protocols', ['https']);
        $isValidPaymentUrl = $paymentUrl && Str::isUrl($paymentUrl, $protocols);
        if ($isValidPaymentUrl) {
            return Inertia::location($paymentUrl);
        }

        return redirect()->route('orders.index')->with('success', 'Order is placed successfully');
    }

    public function success(Request $request)
    {
        if (! $request->has('type')) {
            abort(400, 'Missing payment type');
        }

        if (! PaymentMethod::tryFrom($request->get('type'))->isOnline()) {
            abort(400, 'Online payment is only allowed');
        }

        $type = $request->get('type');
        $paymentHandler = PaymentMethodFactory::make(PaymentMethod::from($type));
        $result = $paymentHandler->handleSuccess($request);
        $order = $result['order'];
        if (! isset($order['id'], $order['shipping_fee'], $order['subtotal'], $order['total'])) {
            abort(500, 'Incomplete order data.');
        }

        return Inertia::render('checkout/processing', [
            'order' => [
                'id' => $order['id'],
                'shipping_fee' => (int) $order['shipping_fee'],
                'subtotal' => (int) $order['subtotal'],
                'total' => (int) $order['total'],
            ],
            'items' => collect($result['items'])->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'product_name' => $item['product_name'] ?? 'Unkown',
                    'quantity' => $item['quantity'] ?? 0,
                    'line_total' => (int) $item['total'] ?? 0,
                    'product_price' => (int) $item['product_price'] ?? 0,
                ];
            }),
        ]);
    }

    public function cancel()
    {
        return Inertia::render('checkout/cancelled');
    }
}

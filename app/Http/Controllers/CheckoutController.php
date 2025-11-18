<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\AddressResource;
use App\Repositories\AddressRepository;
use App\Repositories\CartRepository;
use App\Services\Cart\CartCalculationService;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
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
        protected CartCalculationService $cartCalculationService
    ) {}

    public function index()
    {
        $countries = World::countries();
        $cart = $this->cartService->getOrCreateCart(request()->user());
        $this->cartService->syncQuantitiesWithStock($cart);
        $cartItems = $this->cartRepository->getItemsInStock($cart, relations: ['product']);
        $cartTotals = $this->cartCalculationService->calculate($cart);

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

    public function success()
    {
        return Inertia::render('checkout/processing');
    }

    public function cancel()
    {
        return Inertia::render('checkout/cancelled');
    }
}

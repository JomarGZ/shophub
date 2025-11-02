<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddressResource;
use App\Repositories\AddressRepository;
use App\Repositories\CartRepository;
use App\Services\CartService;
use Inertia\Inertia;
use Nnjeim\World\World;

class CheckoutController extends Controller
{
    public function __construct(
        protected AddressRepository $addressRepository, 
        protected CartRepository $cartRepository, 
        protected CartService $cartService
    ) {}

    public function index()
    {

        $countries = World::countries();
        $cart = auth()->user()->cart;
        $this->cartService->syncQuantitiesWithStock($cart);
        $cartItems = $this->cartRepository->getItemsInStock($cart, relations: ['product']);
        $cartTotals = $cart 
            ? $this->cartService->calculateTotals($cart) 
            : ['subtotal' => 0, 'shipping_fee' => 0, 'total' => 0];

        $orderSummary = [
            'items' =>$cartItems,
            'subtotal' => (int) $cartTotals['subtotal'],
            'shipping_fee' => (int) $cartTotals['shipping_fee'],
            'total' => (int) $cartTotals['total'],
        ];

        $paymentMethods = [
            ['id' => 'cod', 'name' => 'Cash on Delivery'],
            ['id' => 'paypal', 'name' => 'PayPal'],
        ];

        return Inertia::render('checkout/index', [
            'addresses' => fn () => AddressResource::collection($this->addressRepository->getAllForUser(auth()->id())),
            'countries' => $countries->success ? $countries->data : [],
            'order_summary' => $orderSummary,
            'paymentMethods' => $paymentMethods,
        ]);
    }
}

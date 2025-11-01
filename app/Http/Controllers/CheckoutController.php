<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddressResource;
use App\Repositories\AddressRepository;
use Inertia\Inertia;
use Nnjeim\World\World;

class CheckoutController extends Controller
{
    public function __construct(protected AddressRepository $addressRepository) {}

    public function index()
    {

        $countries = World::countries();

        // Mock cart summary data
        $cart = [
            'items' => [
                ['id' => 101, 'name' => 'Wireless Mouse', 'quantity' => 1, 'price' => 599],
                ['id' => 102, 'name' => 'Mechanical Keyboard', 'quantity' => 1, 'price' => 2999],
            ],
            'subtotal' => 3598,
            'shipping_fee' => 150,
            'total' => 3748,
        ];

        // Mock available payment methods
        $paymentMethods = [
            ['id' => 'cod', 'name' => 'Cash on Delivery'],
            ['id' => 'paypal', 'name' => 'PayPal'],
        ];

        return Inertia::render('checkout/index', [
            'addresses' => fn () => AddressResource::collection($this->addressRepository->getAllForUser(auth()->id())),
            'countries' => $countries->success ? $countries->data : [],
            'cart' => $cart,
            'paymentMethods' => $paymentMethods,
        ]);
    }
}

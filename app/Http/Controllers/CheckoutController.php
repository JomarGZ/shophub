<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index()
    {
        // Mock customer addresses
        $addresses = [
            [
                'id' => 'addr-1',
                'firstName' => 'John',
                'lastName' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+1 (555) 123-4567',
                'street' => '123 Main Street',
                'city' => 'New York',
                'zipCode' => '10001',
                'isDefault' => true,
            ],
            [
                'id' => 'addr-2',
                'firstName' => 'John',
                'lastName' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+1 (555) 123-4567',
                'street' => '456 Park Avenue',
                'city' => 'Brooklyn',
                'zipCode' => '11201',
                'isDefault' => false,
            ], ];

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
            'addresses' => $addresses,
            'cart' => $cart,
            'paymentMethods' => $paymentMethods,
        ]);
    }
}

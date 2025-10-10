<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class ShoppingCartController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('shoppingcart', [
            'cart_items' => [
                  [
                    'id' => '1',
                    'name' => 'Wireless Headphones',
                    'price' => 79.99,
                    'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop',
                    'category' => 'Electronics',
                    'description' => 'Premium wireless headphones with noise cancellation and 30-hour battery life.',
                    'stock' => 45,
                    'rating' => 4.5,
                    'quantity' => 4
                ],
                [
                    'id' => '2',
                    'name' => 'Smart Watch',
                    'price' => 199.99,
                    'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&h=500&fit=crop',
                    'category' => 'Electronics',
                    'description' => 'Feature-rich smartwatch with health tracking and GPS.',
                    'stock' => 28,
                    'rating' => 4.7,
                    'quantity' => 4
                ],
                [
                    'id' => '3',
                    'name' => 'Leather Backpack',
                    'price' => 89.99,
                    'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500&h=500&fit=crop',
                    'category' => 'Fashion',
                    'description' => 'Stylish leather backpack perfect for work or travel.',
                    'stock' => 15,
                    'rating' => 4.3,
                    'quantity' => 4
                ],
            ]
        ]);
    }
}

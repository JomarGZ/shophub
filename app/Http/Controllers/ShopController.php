<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class ShopController extends Controller
{
    public function index()
    {
        return Inertia::render('shop/index', [
            'products' => [
                [
                    'id' => '1',
                    'name' => 'Wireless Headphones',
                    'price' => 79.99,
                    'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop',
                    'category' => 'Electronics',
                    'description' => 'Premium wireless headphones with noise cancellation and 30-hour battery life.',
                    'stock' => 45,
                    'rating' => 4.5,
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
                ],
                [
                    'id' => '4',
                    'name' => 'Running Shoes',
                    'price' => 129.99,
                    'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&h=500&fit=crop',
                    'category' => 'Fashion',
                    'description' => 'Comfortable running shoes with advanced cushioning technology.',
                    'stock' => 62,
                    'rating' => 4.8,
                ],
                [
                    'id' => '5',
                    'name' => 'Coffee Maker',
                    'price' => 149.99,
                    'image' => 'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?w=500&h=500&fit=crop',
                    'category' => 'Home',
                    'description' => 'Programmable coffee maker with thermal carafe.',
                    'stock' => 33,
                    'rating' => 4.4,
                ],
                [
                    'id' => '6',
                    'name' => 'Desk Lamp',
                    'price' => 49.99,
                    'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=500&h=500&fit=crop',
                    'category' => 'Home',
                    'description' => 'Modern LED desk lamp with adjustable brightness.',
                    'stock' => 58,
                    'rating' => 4.2,
                ],
                [
                    'id' => '7',
                    'name' => 'Yoga Mat',
                    'price' => 39.99,
                    'image' => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=500&h=500&fit=crop',
                    'category' => 'Sports',
                    'description' => 'Non-slip yoga mat with extra cushioning.',
                    'stock' => 71,
                    'rating' => 4.6,
                ],
                [
                    'id' => '8',
                    'name' => 'Water Bottle',
                    'price' => 24.99,
                    'image' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=500&h=500&fit=crop',
                    'category' => 'Sports',
                    'description' => 'Insulated stainless steel water bottle keeps drinks cold for 24 hours.',
                    'stock' => 94,
                    'rating' => 4.7,
                ],
            ],
            'categories' => ['Electronics', 'Fashion', 'Home', 'Sports'],
        ]);
    }

    public function show()
    {
        return Inertia::render('shop/show', [
            'product' => [
                'id' => '8',
                'name' => 'Water Bottle',
                'price' => 24.99,
                'image' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=500&h=500&fit=crop',
                'category' => 'Sports',
                'description' => 'Insulated stainless steel water bottle keeps drinks cold for 24 hours.',
                'stock' => 94,
                'rating' => 4.7,
            ],
            'related_products' => [
                [
                    'id' => '6',
                    'name' => 'Desk Lamp',
                    'price' => 49.99,
                    'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=500&h=500&fit=crop',
                    'category' => 'Home',
                    'description' => 'Modern LED desk lamp with adjustable brightness.',
                    'stock' => 58,
                    'rating' => 4.2,
                ],
                [
                    'id' => '7',
                    'name' => 'Yoga Mat',
                    'price' => 39.99,
                    'image' => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=500&h=500&fit=crop',
                    'category' => 'Sports',
                    'description' => 'Non-slip yoga mat with extra cushioning.',
                    'stock' => 71,
                    'rating' => 4.6,
                ],
            ],
        ]);
    }
}

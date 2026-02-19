<?php

use App\Models\Category;

use function Pest\Laravel\get;

describe('Shop filtering - business logic', function () {
    it('returns only products from selected category', function () {
        $categoryA = Category::factory()->create();
        $categoryB = Category::factory()->create();

        $productA = createProduct(attributes: ['category_id' => $categoryA->id]);
        createProduct(attributes: ['category_id' => $categoryB->id]);
        get(route('shop.index', [
            'categories' => [$categoryA->slug],
        ]))
            ->assertInertia(fn ($page) => $page
                ->component('shop/index')
                ->has('products.data', 1)
                ->where('products.data.0.id', $productA->id)
            );
    });

    it('returns products matching search term', function () {
        createProduct(count: 3);
        $product = createProduct(attributes: ['name' => 'unique product']);
        get(route('shop.index', [
            'search' => 'unique product',
        ]))
            ->assertInertia(fn ($page) => $page
                ->component('shop/index')
                ->has('products.data', 1)
                ->where('products.data.0.id', $product->id)
            );
    });

    it('returns products within price range', function () {
        createProduct(count: 3, attributes: ['price' => 200]);
        $product = createProduct(attributes: ['price' => 20]);
        get(route('shop.index', [
            'min_price' => 10,
            'max_price' => 20,
        ]))
            ->assertInertia(fn ($page) => $page
                ->component('shop/index')
                ->has('products.data', 1)
                ->where('products.data.0.id', $product->id)
            );
    });
});

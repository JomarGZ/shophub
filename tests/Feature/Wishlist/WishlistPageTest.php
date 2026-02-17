<?php

use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows favorited products in wishlist page', function () {
    $user = createUser();
    $product = createProduct();

    Wishlist::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $this->actingAs($user)
        ->get(route('wishlist.index'))
        ->assertInertia(fn ($page) => $page->component('favorites/index')
            ->where('wishlist_products.data.0.id', $product->id)
        );
});

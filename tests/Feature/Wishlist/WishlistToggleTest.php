<?php

use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('adds a product to wishlist', function () {
    $user = createUser();
    $product = createProduct();

    $this->actingAs($user)
        ->post(route('wishlist.toggle', $product))
        ->assertRedirect();

    $this->assertDatabaseHas('wishlists', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});

it('removes a product from wishlist', function () {
    $user = createUser();
    $product = createProduct();

    Wishlist::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);

    $this->actingAs($user)
        ->post(route('wishlist.toggle', $product))
        ->assertRedirect();

    $this->assertDatabaseMissing('wishlists', [
        'user_id' => $user->id,
        'product_id' => $product->id,
    ]);
});

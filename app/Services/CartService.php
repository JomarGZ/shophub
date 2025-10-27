<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Repositories\CartRepository;
use Illuminate\Validation\ValidationException;

class CartService
{

    public function __construct(protected CartRepository $cartRepo)
    {
        
    }
    public function addItem(User $user, Product $product, int $quantity)
    {
        if ($product->stock < $quantity) {
            throw ValidationException::withMessages(['product' => 'Not enough stock available']);
        }

        $cart = $user->cart ?? $user->cart()->create();

        $item = $this->cartRepo->findOrCreateItem(cartId: $cart->id, productId: $product->id);

        $item->quantity += $quantity;
        
        return $this->cartRepo->save($item);
    }
}

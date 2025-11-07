<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;

class PlaceOrderService
{

    public function execute(User $user, array $data)
    {
        $user->loadMissing(['cart.cartItems.prodct']);
        if (!$user->cart) {
            throw new \Exception('User does not have a cart');
        } 
        if ($user->cart->cartItems->isEmpty()) {
            throw new \Exception('Cart is empty');
        }
        if (empty($data['payment_method'])) {
            throw new \InvalidArgumentException('Payment method is required to process order');
        }

        if (empty($data['address_id'])) {
            throw new \InvalidArgumentException('Address is required to process order');
        }



    }

}

<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function decrementOrderStock(Order $order)
    {
        DB::transaction(function () use ($order) {
            $order->loadMissing('orderItems.product');

            $order->orderItems->each(function ($item) {
                $product = $item->product;
                if ($product->stock < $item->quantity) {
                    throw new \Exception("Not enough stock for product {$product->name}");
                }
                $product->decrement('stock', $item->quantity);
            });
        });
    }
}

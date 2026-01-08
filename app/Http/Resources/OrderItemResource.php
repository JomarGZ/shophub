<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        Log::info('OrderItem rating check', [
    'order_item_id' => $this->id,
    'product_id' => $this->product?->id,
    'ratings_count' => $this->product?->ratings?->count(),
]);
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'product_price' => $this->product_price,
            'total' => $this->line_total,
            'quantity' => $this->quantity,
            'product' => ProductResource::make($this->whenLoaded('product')),
            'has_rated' => $this->when(
                    $this->relationLoaded('product') && $this->product->relationLoaded('ratings'),
                    $this->product->ratings->isNotEmpty(),
                    false // Default value when not loaded
                )
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'product_price' => $this->product_price,
            'total' => $this->line_total,
            'quantity' => $this->quantity,
            'product' => ProductResource::make($this->whenLoaded('product')),
            'has_rated' => $this->whenLoaded('product', function () use ($request) {
                return (bool) $request->user()?->hasRated($this->product);
            })
        ];
    }
}

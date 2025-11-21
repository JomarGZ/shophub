<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'customer' => $this->shipping_full_name,
            'status' => [
                'label' => $this->status->label(),
                'color' => $this->status->color(),
                'value' => $this->status,
            ],
            'payment_status' => $this->payment_status,
            'date_ordered' => $this->created_at,
            'payment_method' => $this->payment_method,
            'shipping_fee' => $this->shipping_fee,
            'total' => $this->total,
            'address' => [
                'city' => $this->shipping_city,
                'country' => $this->shipping_country,
                'street' => $this->shipping_street_address,
            ],
            'order_items' => OrderItemResource::collection($this->whenLoaded('orderItems')),
        ];
    }
}

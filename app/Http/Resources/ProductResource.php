<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'price' => number_format($this->price, 2),
            'image_url' => $this->image_url,
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'description' => $this->description,
            'stock' => $this->stock,
        ];
    }
}

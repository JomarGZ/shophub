<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'id' => $this->whenHas('id', $this->id),
            'name' => $this->whenHas('name', $this->name),
            'slug' => $this->whenHas('slug',$this->slug),
            'price' => $this->whenHas('price', number_format($this->price, 2)),
            'image_url' => $this->when($this->image_url, Storage::url($this->image_url), asset('images/defaults/product.png')),
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'description' => $this->whenHas('description', $this->description),
            'stock' => $this->whenHas('stock', $this->stock),
            'is_favorited' => (bool) $this->is_favorited
        ];
    }
}

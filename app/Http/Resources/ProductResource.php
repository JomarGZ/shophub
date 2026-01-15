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
            'slug' => $this->whenHas('slug', $this->slug),
            'price' => $this->whenHas('price', number_format($this->price, 2)),
            'image_url' => $this->when($this->image_url, Storage::url($this->image_url), asset('images/defaults/product.png')),
            'product_ratings' => ProductRatingResource::collection($this->whenLoaded('ratings')),
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'description' => $this->whenHas('description', $this->description),
            'stock' => $this->whenHas('stock', $this->stock),
            'average_rating' => $this->whenHas('average_rating', $this->average_rating),
            'ratings_count' => $this->whenHas('ratings_count', $this->ratings_count),
            'is_favorited' => (bool) $this->is_favorited,
        ];
    }
}

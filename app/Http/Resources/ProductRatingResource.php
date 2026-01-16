<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductRatingResource extends JsonResource
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
            'user_id' => $this->whenHas('user_id', $this->user_id),
            'product_id' => $this->whenHas('product_id', $this->product_id),
            'rating' => $this->whenHas('rating', $this->rating),
            'comment' => $this->whenHas('comment', $this->comment),
        ];
    }
}

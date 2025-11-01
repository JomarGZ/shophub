<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'is_default' => $this->is_default,
            'street_address' => $this->street_address,
            'city' => CityResource::make($this->whenLoaded('city')),
            'country' => CountryResource::make($this->whenLoaded('country')),
        ];
    }
}

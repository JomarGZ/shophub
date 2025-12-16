<?php

namespace App\Http\Requests;

use App\Models\ProductRating;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $productRating = ProductRating::find($this->route('productRating'));
        if ($productRating instanceof Collection) {
            $productRating = $productRating->first();
        }

        return $productRating && $this->user()->can('update', $productRating);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ];
    }
}

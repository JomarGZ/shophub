<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Nnjeim\World\Models\Country;

class StoreAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $countryId = (int) $this->get('country_id', null);
        $countryCode = $this->getCountryCode($countryId);
        info($countryCode);
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'street_address' => 'required|string|max:255',
            'phone' => ['required', "phone:{$countryCode}"]
        ];
    }

    public function messages(): array
    {
        return [
            'phone.phone' => 'The phone number is not valid for the selected country.'
        ];
    }

    public function getCountryCode(int $countryId)
    {
        $country = DB::table('countries')->find($countryId);

        return $country?->iso2 ?? 'PH';
    }
}

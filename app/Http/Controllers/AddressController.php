<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Services\AddressService;

class AddressController extends Controller
{
    public function __construct(protected AddressService $addressService)
    {
        
    }
    public function store(StoreAddressRequest $request)
    {
        $this->addressService->create(auth()->user(), $request->validated());
        return redirect()->route('checkout.index')->with('message', 'Address address added successfully.');
    }
}

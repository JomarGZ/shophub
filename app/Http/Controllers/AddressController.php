<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Models\Address;
use App\Services\AddressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class AddressController extends Controller
{
    public function __construct(protected AddressService $addressService) {}

    public function store(StoreAddressRequest $request): RedirectResponse
    {
        $this->addressService->create(auth()->user(), $request->validated());

        return redirect()->route('checkout.index')->with('message', 'Address added successfully.');
    }

    public function update(Address $address, StoreAddressRequest $request)
    {
        Gate::authorize('update', $address);
        $this->addressService->update($address, $request->validated());

        return redirect()->back()->with('message', 'Address updated successfully');
    }
    
    public function destroy(Address $address): RedirectResponse
    {
        Gate::authorize('delete', $address);
        $this->addressService->delete($address);

        return redirect()->back()->with('message', 'Address deleted successfully.');
    }

    public function updateDefault(Address $address): RedirectResponse
    {
        Gate::authorize('update', $address);
        $this->addressService->setDefault(auth()->user(), $address);

        return redirect()->back()->with('message', 'Address set as default successfully');
    }
}

<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use App\Repositories\AddressReposiory;
use Illuminate\Support\Facades\DB;

class AddressService
{
    public function __construct(protected AddressReposiory $addressRepository) {}

    public function create(User $user, array $data): Address
    {
        $data['user_id'] = $user->id;
        if (! $user->hasDefaultAddress()) {
            $data['is_default'] = true;
        }

        return $this->addressRepository->create($data);
    }

    public function update(Address $address, array $data)
    {
        return $this->addressRepository->update($address, $data);
    }

    public function delete(Address $address): bool
    {
        return $this->addressRepository->delete($address);
    }

    public function setDefault(User $user, Address $address)
    {
        return DB::transaction(function () use ($user, $address) {
            $user->addresses()->update(['is_default' => false]);

            $address->is_default = true;
            $address->save();

            return $address->fresh();
        });

    }
}

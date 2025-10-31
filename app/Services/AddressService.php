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

    public function delete(User $user, Address $address): bool
    {
        if ($address->user_id !== $user->id || $address->is_default) {
            throw new \Exception('Unauthorized');
        }

        return $this->addressRepository->delete($address);
    }

    public function setDefault(User $user, Address $address)
    {
        if ($address->user_id !== $user->id || $address->is_default) {
            abort(403, 'Unauthorized');
        }

        return DB::transaction(function () use ($user, $address) {
            $user->addresses()->update(['is_default' => false]);

            $address->is_default = true;
            $address->save();

            return $address->fresh();
        });

    }
}

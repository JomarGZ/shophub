<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class AddressRepository extends Repository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        parent::__construct(new Address);
    }

    public function getAllForUser(int $userId): Collection
    {
        return Cache::rememberForever("user:addresses:{$userId}", function () use ($userId) {
            return $this->model->with(['country:id,name', 'city:id,name'])->where('user_id', $userId)->get(['id',
                'first_name',
                'last_name',
                'phone',
                'street_address',
                'is_default',
                'country_id',
                'city_id']);
        });
    }

    public function getAddress(User $user, $default = false)
    {
        $user->loadMissing('addresses');

        return $this->query()->where('user_id', $user->id)->firstWhere('is_default', $default);
    }

    public function clearCache(int $userId)
    {
        Cache::forget("user:addresses:{$userId}");
    }
}

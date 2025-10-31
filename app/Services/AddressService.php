<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use App\Repositories\AddressReposiory;

class AddressService
{

   public function __construct(protected AddressReposiory $addressRepository)
   {
    
   }


   public function create(User $user, array $data): Address
   {
        $data['user_id'] = $user->id;
        if (!$user->hasDefaultAddress()) {
            $data['is_default'] = true;
        }
        return $this->addressRepository->create($data);
   }

}

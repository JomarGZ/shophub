<?php

namespace App\Repositories;

use App\Models\Address;

class AddressReposiory extends Repository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        parent::__construct(new Address);
    }
}

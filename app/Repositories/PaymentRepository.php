<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(new Payment);
    }
}

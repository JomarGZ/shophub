<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('checkout');
    }
}

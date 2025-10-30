<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Nnjeim\World\WorldHelper;

class CityController extends Controller
{
    
     public function __construct(protected WorldHelper $world)
     {
     }
    public function index()
    {
        $countryId = Request::only('country_id');

        $cities = $this->world->cities([
            'filters' => [
                'country_id' => 174,
            ],
        ]);
        info('trigger');
        if ($cities->success) {
            return response()->json(['data' => $cities->data, 'success' => true], 200);
        } else {
            return response()->json(['data' => [], 'success' => false], 500);
        }
    }
}

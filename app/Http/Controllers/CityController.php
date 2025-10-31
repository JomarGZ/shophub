<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Nnjeim\World\Models\City;
use Nnjeim\World\WorldHelper;

class CityController extends Controller
{
    
     public function __construct(protected WorldHelper $world)
     {
     }
    public function index()
    {
        $filters = Request::only('country_id', 'search', 'limit');
        $limit = isset($filters['limit']) && is_numeric($filters['limit']) ? (int)$filters['limit'] : 100;
        $query = City::query();
        if (!isset($filters['country_id']) || !$filters['country_id']) {
            return response()->json(['data' => [], 'success' => false], 400);
        }
        $query->where('country_id', $filters['country_id']);
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $search = trim($search);
            $q->where('name', 'LIKE', "%{$search}%");
        });
        $query->orderBy('name', 'ASC');
        $cities = $query->limit($limit)->get(['id', 'name']);

        if ($cities) {
            return response()->json(['data' => $cities, 'success' => true], 200);
        } else {
            return response()->json(['data' => [], 'success' => false], 500);
        }
    }
}

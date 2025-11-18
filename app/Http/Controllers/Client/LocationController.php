<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->get('q', '');

        $query = Location::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('city', 'like', "%{$keyword}%")
                    ->orWhere('province', 'like', "%{$keyword}%")
                    ->orWhere('name', 'like', "%{$keyword}%");
            });
        }

        $locations = $query
            ->select('city', 'province')
            ->distinct()
            ->limit(10)
            ->get();

        return response()->json($locations);
    }
}

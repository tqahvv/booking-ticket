<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Operator;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteAdminController extends Controller
{
    public function index()
    {
        $routes = Route::with(['origin', 'destination', 'operator'])->get();
        $locations = Location::all();
        $operators = Operator::all();
        return view('admin.pages.routes', compact('routes', 'locations', 'operators'));
    }

    public function update(Request $request, $id)
    {
        $route = Route::findOrFail($id);

        $request->validate([
            'origin_location_id' => 'required|exists:locations,id',
            'destination_location_id' => 'required|exists:locations,id',
            'operator_id' => 'required|exists:operators,id',
            'distance' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $route->update($request->all());

        $route->load(['origin', 'destination', 'operator']);

        return response()->json([
            'success' => true,
            'message' => 'Tuyến đường được cập nhật thành công.',
            'data' => [
                'origin_name' => $route->origin->name ?? '---',
                'destination_name' => $route->destination->name ?? '---',
                'operator_name' => $route->operator->name ?? '---',
                'distance' => $route->distance,
                'description' => $route->description,
            ]
        ]);
    }

    public function delete($id)
    {
        $route = Route::findOrFail($id);

        try {
            $route->delete();
            return response()->json([
                'success' => true,
                'message' => 'Tuyến đường đã được xóa thành công.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xóa tuyến đường thất bại.'
            ]);
        }
    }

    public function showFormAdd()
    {
        $locations = Location::all();
        $operators = Operator::all();
        return view('admin.pages.route-add', compact('locations', 'operators'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'origin_location_id' => 'required|exists:locations,id',
            'destination_location_id' => 'required|exists:locations,id',
            'operator_id' => 'required|exists:operators,id',
            'distance' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $route = Route::create($request->all());

        $route->load(['origin', 'destination', 'operator']);

        return response()->json([
            'success' => true,
            'message' => 'Tuyến đường được thêm thành công.',
        ]);
    }
}

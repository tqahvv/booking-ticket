<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Operator;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RouteAdminController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        $routes = Route::with(['origin', 'destination'])
            ->where('operator_id', $admin->operator_id)
            ->orderBy('created_at', 'DESC')
            ->get();

        $locations = Location::all();

        return view('admin.pages.routes', compact('routes', 'locations'));
    }

    public function update(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();

        $route = Route::where('operator_id', $admin->operator_id)
            ->findOrFail($id);

        $request->validate([
            'origin_location_id' => 'required|exists:locations,id|different:destination_location_id',
            'destination_location_id' => 'required|exists:locations,id',
            'distance' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ], [
            'origin_location_id.different' => 'Điểm đi và điểm đến không được trùng nhau',
            'distance.min' => 'Khoảng cách phải lớn hơn 0',
        ]);

        $route->update([
            'origin_location_id' => $request->origin_location_id,
            'destination_location_id' => $request->destination_location_id,
            'distance' => $request->distance,
            'description' => $request->description,
        ]);

        $route->load(['origin', 'destination']);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật tuyến đường thành công',
            'data' => [
                'origin_name' => $route->origin->name,
                'destination_name' => $route->destination->name,
                'distance' => $route->distance,
                'description' => $route->description,
            ]
        ]);
    }

    public function delete($id)
    {
        $admin = Auth::guard('admin')->user();

        $route = Route::where('operator_id', $admin->operator_id)
            ->findOrFail($id);

        $route->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa tuyến đường thành công'
        ]);
    }

    public function showFormAdd()
    {
        $admin = Auth::guard('admin')->user();

        $locations = Location::all();

        return view('admin.pages.route-add', compact('locations', 'admin'));
    }

    public function add(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'origin_location_id' => 'required|exists:locations,id|different:destination_location_id',
            'destination_location_id' => 'required|exists:locations,id',
            'distance' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ], [
            'origin_location_id.different' => 'Điểm đi và điểm đến không được trùng nhau',
            'distance.min' => 'Khoảng cách phải lớn hơn 0',
        ]);

        Route::create([
            'origin_location_id' => $validated['origin_location_id'],
            'destination_location_id' => $validated['destination_location_id'],
            'operator_id' => $admin->operator_id,
            'distance' => $validated['distance'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm tuyến đường thành công'
        ]);
    }
}

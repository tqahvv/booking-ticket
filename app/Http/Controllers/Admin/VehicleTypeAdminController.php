<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeAdminController extends Controller
{
    public function index()
    {
        $vehicleTypes = VehicleType::orderBy('created_at', 'DESC')->get();
        return view('admin.pages.vehicle_types', compact('vehicleTypes'));
    }

    public function showFormAdd()
    {
        return view('admin.pages.vehicle_type-add');
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity_total' => 'required|integer|min:1',
            'number_of_floors' => 'nullable|integer|min:1',
            'seat_template_type' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        $vehicleType = VehicleType::create([
            'name' => $request->name,
            'capacity_total' => $request->capacity_total,
            'number_of_floors' => $request->number_of_floors ?? 1,
            'seat_template_type' => $request->seat_template_type ?? 'standard',
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm loại xe thành công!',
            'data' => $vehicleType
        ]);
    }

    public function update(Request $request, $id)
    {
        $vehicleType = VehicleType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'capacity_total' => 'required|integer|min:1',
            'number_of_floors' => 'nullable|integer|min:1',
            'seat_template_type' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        $vehicleType->update([
            'name' => $request->name,
            'capacity_total' => $request->capacity_total,
            'number_of_floors' => $request->number_of_floors ?? 1,
            'seat_template_type' => $request->seat_template_type ?? 'standard',
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật loại xe thành công!',
            'data' => $vehicleType
        ]);
    }

    public function delete($id)
    {
        $vehicleType = VehicleType::find($id);
        if (!$vehicleType) {
            return response()->json([
                'success' => false,
                'message' => 'Loại xe không tồn tại!'
            ]);
        }

        $vehicleType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa loại xe thành công!'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationAdminController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('id', 'DESC')->get();
        return view('admin.pages.locations', compact('locations'));
    }

    public function showFormAdd()
    {
        return view('admin.pages.location-add');
    }

    public function add(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'city'     => 'required|string|max:255',
            'address'  => 'required|string|max:255',
            'province' => 'required|string|max:255',
        ]);

        $location = Location::create([
            'name'     => $request->name,
            'city'     => $request->city,
            'address'  => $request->address,
            'province' => $request->province,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm điểm đón – trả thành công!',
            'data' => $location
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'city'     => 'required|string|max:255',
            'address'  => 'required|string|max:255',
            'province' => 'required|string|max:255',
        ]);

        $location = Location::findOrFail($id);

        $location->update([
            'name'     => $request->name,
            'city'     => $request->city,
            'address'  => $request->address,
            'province' => $request->province,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công!',
            'data' => $location
        ]);
    }

    public function delete($id)
    {
        $location = Location::find($id);

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy địa điểm!'
            ]);
        }

        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa địa điểm thành công!'
        ]);
    }
}

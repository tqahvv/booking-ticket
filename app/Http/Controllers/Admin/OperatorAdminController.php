<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use Illuminate\Http\Request;

class OperatorAdminController extends Controller
{
    public function index()
    {
        $operators = Operator::all();
        return view('admin.pages.operators', compact('operators'));
    }

    public function showFormAdd()
    {
        return view('admin.pages.operator-add');
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rating' => 'required|numeric|min:0|max:5',
            'contact_info' => 'required|string|max:255',
        ]);
        $logoId = null;

        Operator::create([
            'name' => $request->name,
            'description' => $request->description,
            'rating' => $request->rating,
            'contact_info' => $request->contact_info,
            'logo_image_id' => $logoId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm nhà xe thành công!'
        ]);
    }

    public function update(Request $request, $id)
    {
        $op = Operator::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rating' => 'required|numeric|min:0|max:5',
            'contact_info' => 'required|string|max:255',
        ]);

        $logoId = null;

        $op->update([
            'name' => $request->name,
            'description' => $request->description,
            'rating' => $request->rating,
            'contact_info' => $request->contact_info,
            'logo_image_id' => $logoId
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'name' => $op->name,
                'description' => $op->description,
                'rating' => $op->rating,
                'contact_info' => $op->contact_info
            ]
        ]);
    }

    public function delete($id)
    {
        $op = Operator::findOrFail($id);
        $op->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa nhà xe thành công!'
        ]);
    }
}

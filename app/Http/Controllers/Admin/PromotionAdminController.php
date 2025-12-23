<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PromotionAdminController extends Controller
{

    public function index()
    {
        $promotions = Promotion::orderBy('id', 'DESC')->get();
        return view('admin.pages.promotions', compact('promotions'));
    }

    public function toggleStatus(Request $request, $id)
    {
        $promo = Promotion::findOrFail($id);
        $promo->is_active = $request->input('is_active') ? 1 : 0;
        $promo->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công!',
            'data' => $promo
        ]);
    }
    public function showFormAdd()
    {
        return view('admin.pages.promotion-add');
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:promotions,code',
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'total_usage_limit' => 'nullable|integer|min:1',
            'min_booking_amount' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
        ], [
            'code.required' => 'Mã giảm giá không được để trống',
            'code.unique' => 'Mã giảm giá đã tồn tại',
            'discount_type.required' => 'Vui lòng chọn loại giảm giá',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm',
            'valid_from.required' => 'Vui lòng chọn ngày bắt đầu',
            'valid_to.required' => 'Vui lòng chọn ngày kết thúc',
            'valid_to.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
            'is_active.required' => 'Vui lòng chọn trạng thái',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ!',
                'errors' => $validator->errors()
            ], 422);
        }

        $promo = Promotion::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Tạo mã giảm giá thành công!',
            'data' => $promo
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string', 'max:50', Rule::unique('promotions')->ignore($id)],
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'total_usage_limit' => 'nullable|integer|min:1',
            'min_booking_amount' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
        ], [
            'code.required' => 'Mã giảm giá không được để trống',
            'code.unique' => 'Mã giảm giá đã tồn tại',
            'discount_type.required' => 'Vui lòng chọn loại giảm giá',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm',
            'discount_value.numeric' => 'Giá trị giảm phải là số',
            'valid_from.required' => 'Vui lòng chọn ngày bắt đầu hiệu lực',
            'valid_to.required' => 'Vui lòng chọn ngày kết thúc hiệu lực',
            'valid_to.after_or_equal' => 'Ngày kết thúc phải bằng hoặc sau ngày bắt đầu',
            'is_active.required' => 'Vui lòng chọn trạng thái',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ!',
                'errors' => $validator->errors(),
            ], 422);
        }

        $pro = Promotion::findOrFail($id);

        $pro->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật mã giảm giá thành công!',
            'data' => $pro,
        ]);
    }

    public function delete($id)
    {
        $pro = Promotion::find($id);

        if (!$pro) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy mã giảm giá!',
            ]);
        }

        $pro->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa mã giảm giá thành công!',
        ]);
    }
}

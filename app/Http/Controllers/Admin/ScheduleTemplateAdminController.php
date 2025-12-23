<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\ScheduleTemplate;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ScheduleTemplateAdminController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        $templates = ScheduleTemplate::with(['route', 'vehicleType'])
            ->where('operator_id', $admin->operator_id)
            ->orderBy('created_at', 'DESC')
            ->get();

        $routes = Route::all();
        $vehicleTypes = VehicleType::all();

        return view('admin.pages.schedule_templates', compact(
            'templates',
            'routes',
            'vehicleTypes'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'departure_time' => 'required|date_format:H:i',
            'travel_duration_minutes' => 'required|integer|min:1',
            'running_days' => 'required|array|min:1',
            'base_fare' => 'required|numeric|min:1000|max:10000000',
            'default_seats' => 'required|integer|min:1|max:60',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'departure_time.date_format' => 'Giờ xuất bến không đúng định dạng HH:mm',
            'travel_duration_minutes.integer' => 'Thời gian chạy phải là số',
            'running_days.required' => 'Vui lòng chọn ít nhất 1 ngày chạy',
            'base_fare.numeric' => 'Giá vé phải là số',
            'base_fare.min' => 'Giá vé tối thiểu là 1.000đ',
            'default_seats.max' => 'Số ghế không được vượt quá 60',
        ]);

        $admin = Auth::guard('admin')->user();

        $template = ScheduleTemplate::where('operator_id', $admin->operator_id)
            ->findOrFail($id);

        $vehicleType = $template->vehicleType;

        if ($request->default_seats > $vehicleType->capacity_total) {
            return response()->json([
                'errors' => [
                    'default_seats' => [
                        'Số ghế tối đa của loại xe "' . $vehicleType->name . '" là ' . $vehicleType->capacity_total
                    ]
                ]
            ], 422);
        }

        $runningDays = $request->running_days ?? [];

        $exists = ScheduleTemplate::where('operator_id', $admin->operator_id)
            ->where('route_id', $template->route_id)
            ->where('departure_time', $request->departure_time)
            ->where('id', '!=', $template->id)
            ->where(function ($q) use ($request) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $request->start_date);
            })
            ->where('start_date', '<=', $request->end_date ?? $request->start_date)
            ->get()
            ->filter(function ($t) use ($runningDays) {
                return count(array_intersect(
                    $t->running_days ?? [],
                    $runningDays
                )) > 0;
            })
            ->isNotEmpty();

        if ($exists) {
            return response()->json([
                'errors' => [
                    'departure_time' => [
                        'Chuyến xe bị trùng tuyến, giờ và ngày chạy'
                    ]
                ]
            ], 422);
        }

        $template->update([
            'departure_time' => $request->departure_time,
            'travel_duration_minutes' => $request->travel_duration_minutes,
            'running_days' => $runningDays,
            'base_fare' => $request->base_fare,
            'default_seats' => $request->default_seats,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'departure_time' => $template->departure_time,
                'travel_duration_minutes' => $template->travel_duration_minutes,
                'running_days' => $runningDays,
                'base_fare' => $template->base_fare,
                'default_seats' => $template->default_seats,
            ]
        ]);
    }

    public function showFormAdd()
    {
        $admin = Auth::guard('admin')->user();

        $routes = Route::with(['origin', 'destination'])->get();
        $vehicleTypes = VehicleType::all();

        return view('admin.pages.schedule_template-add', compact(
            'routes',
            'vehicleTypes',
            'admin'
        ));
    }

    public function add(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'route_id' => 'required|exists:routes,id',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'departure_time' => 'required|date_format:H:i',
            'travel_duration_minutes' => 'required|integer|min:1',
            'running_days' => 'required|array|min:1',
            'base_fare' => 'required|numeric|min:1000|max:10000000',
            'default_seats' => 'required|integer|min:1|max:60',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'departure_time.date_format' => 'Giờ xuất bến không đúng định dạng HH:mm',
            'travel_duration_minutes.integer' => 'Thời gian chạy phải là số',
            'running_days.required' => 'Vui lòng chọn ít nhất 1 ngày chạy',
            'base_fare.numeric' => 'Giá vé phải là số',
            'base_fare.min' => 'Giá vé tối thiểu là 1.000đ',
            'default_seats.max' => 'Số ghế không được vượt quá 60',
        ]);

        $vehicleType = VehicleType::findOrFail($request->vehicle_type_id);

        if ($request->default_seats > $vehicleType->capacity_total) {
            return response()->json([
                'errors' => [
                    'default_seats' => [
                        'Số ghế tối đa của loại xe "' . $vehicleType->name . '" là ' . $vehicleType->capacity_total
                    ]
                ]
            ], 422);
        }

        $runningDays = $request->running_days;

        $exists = ScheduleTemplate::where('operator_id', $admin->operator_id)
            ->where('route_id', $request->route_id)
            ->where('departure_time', $request->departure_time)
            ->get()
            ->filter(
                fn($t) =>
                count(array_intersect($t->running_days ?? [], $runningDays)) > 0
            )
            ->isNotEmpty();

        if ($exists) {
            return response()->json([
                'errors' => [
                    'departure_time' => [
                        'Chuyến xe đã tồn tại (trùng tuyến, giờ và ngày chạy)'
                    ]
                ]
            ], 422);
        }

        ScheduleTemplate::create([
            'route_id' => $request->route_id,
            'operator_id' => $admin->operator_id,
            'vehicle_type_id' => $request->vehicle_type_id,
            'departure_time' => $request->departure_time,
            'travel_duration_minutes' => $request->travel_duration_minutes,
            'running_days' => $request->running_days,
            'base_fare' => $request->base_fare,
            'default_seats' => $request->default_seats,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm chuyến xe thành công'
        ]);
    }

    public function delete($id)
    {
        $admin = Auth::guard('admin')->user();
        $template = ScheduleTemplate::where('operator_id', $admin->operator_id)
            ->findOrFail($id);
        $template->delete();

        return response()->json(['success' => true, 'message' => 'Xóa chuyến xe thành công']);
    }
}

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

class ScheduleTemplateAdminController extends Controller
{
    public function index()
    {
        $templates = ScheduleTemplate::with(['route', 'operator', 'vehicleType'])->get();
        $routes = Route::all();
        $operators = Operator::all();
        $vehicleTypes = VehicleType::all();

        return view('admin.pages.schedule_templates', compact('templates', 'routes', 'operators', 'vehicleTypes'));
    }

    public function update(Request $request, $id)
    {
        $template = ScheduleTemplate::findOrFail($id);

        $runningDays = $request->running_days ?? [];

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
        $routes = Route::with(['origin', 'destination'])->get();
        $operators = Operator::all();
        $vehicleTypes = VehicleType::all();

        return view('admin.pages.schedule_template-add', compact('routes', 'operators', 'vehicleTypes'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'route_id' => 'required|exists:routes,id',
            'operator_id' => 'required|exists:operators,id',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'departure_time' => 'required',
            'travel_duration_minutes' => 'required|integer|min:1',
            'running_days' => 'required|array',
            'base_fare' => 'required|numeric|min:0',
            'default_seats' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $template = ScheduleTemplate::create([
            'route_id' => $request->route_id,
            'operator_id' => $request->operator_id,
            'vehicle_type_id' => $request->vehicle_type_id,
            'departure_time' => $request->departure_time,
            'travel_duration_minutes' => $request->travel_duration_minutes,
            'running_days' => $request->running_days,
            'base_fare' => $request->base_fare,
            'default_seats' => $request->default_seats,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json(['message' => 'Thêm chuyến xe thành công!']);
    }

    public function delete($id)
    {
        $template = ScheduleTemplate::findOrFail($id);
        $template->delete();

        return response()->json(['success' => true, 'message' => 'Xóa template thành công']);
    }
}

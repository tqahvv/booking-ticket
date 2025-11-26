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

    public function store(Request $request)
    {
        $data = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'operator_id' => 'required|exists:operators,id',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'departure_time' => 'required',
            'travel_duration_minutes' => 'required|integer|min:0',
            'running_days' => 'required|array',
            'base_fare' => 'required|numeric|min:0',
            'default_seats' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $data['running_days'] = json_encode($data['running_days']);

        ScheduleTemplate::create($data);

        return response()->json(['success' => true, 'message' => 'Thêm template thành công']);
    }

    public function update(Request $request, $id)
    {
        $template = ScheduleTemplate::findOrFail($id);

        $data = $request->validate([
            'departure_time' => 'required',
            'travel_duration_minutes' => 'required|integer|min:0',
            'running_days' => 'required|array',
            'base_fare' => 'required|numeric|min:0',
            'default_seats' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $data['running_days'] = json_encode($data['running_days']);

        $template->update($data);

        return response()->json(['success' => true, 'message' => 'Cập nhật template thành công']);
    }

    public function delete($id)
    {
        $template = ScheduleTemplate::findOrFail($id);
        $template->delete();

        return response()->json(['success' => true, 'message' => 'Xóa template thành công']);
    }

    public function generateSchedules($id)
    {
        $template = ScheduleTemplate::findOrFail($id);
        $runningDays = json_decode($template->running_days, true);

        $start = Carbon::parse($template->start_date);
        $end = $template->end_date ? Carbon::parse($template->end_date) : $start->copy()->addDays(30);

        $generatedCount = 0;
        for ($date = $start; $date->lte($end); $date->addDay()) {
            if (in_array($date->dayOfWeekIso, $runningDays)) {
                Schedule::create([
                    'schedule_template_id' => $template->id,
                    'route_id' => $template->route_id,
                    'operator_id' => $template->operator_id,
                    'vehicle_type_id' => $template->vehicle_type_id,
                    'departure_datetime' => $date->format('Y-m-d') . ' ' . $template->departure_time,
                    'travel_duration_minutes' => $template->travel_duration_minutes,
                    'base_fare' => $template->base_fare,
                    'total_seats' => $template->default_seats,
                    'seats_available' => $template->default_seats,
                    'status' => 'scheduled',
                ]);
                $generatedCount++;
            }
        }

        return response()->json(['success' => true, 'message' => "Đã tạo $generatedCount lịch chạy từ template"]);
    }
}

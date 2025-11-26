<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use App\Models\Schedule;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class ScheduleAdminController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['route', 'vehicleType', 'operator'])
            ->orderBy('departure_datetime', 'asc')
            ->paginate(20);

        $vehicleTypes = VehicleType::all();
        $operators = Operator::all();

        return view('admin.pages.schedules', compact('schedules', 'vehicleTypes', 'operators'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'departure_datetime' => 'required|date',
            'base_fare' => 'required|numeric|min:0',
            'status' => 'required|in:scheduled,cancelled,delayed'
        ]);

        $schedule = Schedule::findOrFail($id);

        $schedule->departure_datetime = $request->input('departure_datetime');
        $schedule->base_fare = $request->input('base_fare');
        $schedule->status = $request->input('status');

        $schedule->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật lịch chuyến thành công'
        ]);
    }
}

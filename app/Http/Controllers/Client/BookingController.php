<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ScheduleTemplate;
use App\Models\VehicleSeatTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    public function choosePickup(Request $request)
    {
        $schedule = ScheduleTemplate::with([
            'operator',
            'vehicleType',
            'route.origin',
            'route.destination',
            'route.pickups.location',
            'route.dropoffs.location'
        ])->findOrFail($request->schedule_id);

        $departure = Carbon::parse($schedule->departure_time);
        $arrival = $departure->copy()->addMinutes($schedule->travel_duration_minutes);
        $durationMinutes = $schedule->travel_duration_minutes;
        $durationHours = floor($durationMinutes / 60);
        $durationRemainMinutes = $durationMinutes % 60;
        $durationText = $durationHours . 'h' . ($durationRemainMinutes > 0 ? $durationRemainMinutes . 'm' : '');
        $seatsAvailable = $schedule->default_seats;
        $seatsNeeded = $request->seats;

        return view('client.pages.pickup', compact(
            'schedule',
            'departure',
            'arrival',
            'durationText',
            'seatsAvailable',
            'seatsNeeded'
        ));
    }

    public function checkPickup(Request $request)
    {
        if (!$request->pickup_id || !$request->dropoff_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vui lòng chọn điểm đón và điểm trả.'
            ]);
        }

        // TODO: Kiểm tra ghế đặt trùng (sau)
        // TODO: Kiểm tra còn đủ ghế (sau)

        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function chooseSeat(Request $request)
    {
        $schedule = ScheduleTemplate::with(['operator', 'vehicleType'])
            ->findOrFail($request->schedule_id);

        $pickup_id = $request->pickup_id;
        $dropoff_id = $request->dropoff_id;

        // Lấy ghế của xe tương ứng
        $seats = VehicleSeatTemplate::where('vehicle_type_id', $schedule->vehicle_type_id)
            ->orderBy('deck')
            ->orderBy('row')
            ->orderBy('column')
            ->get();

        // Xác định xe giường nằm hay ghế ngồi
        $vehicleType = $schedule->vehicleType;
        $isBed = str_contains(strtolower($vehicleType->name), 'giường');

        // Chuẩn hóa busType cho JS
        if ($vehicleType->name == 'Ghế ngồi 29 chỗ') {
            $busType = 'seat_29';
        } elseif ($vehicleType->name == 'Ghế ngồi 40 chỗ') {
            $busType = 'seat_40';
        } elseif (str_contains(strtolower($vehicleType->name), 'giường')) {
            $busType = 'bed_44';
        } else {
            $busType = 'seat_29'; // default fallback
        }

        $bookedSeats = []; // Lấy từ booking khi có dữ liệu
        $maxSeats = $request->seats ?? 10;
        $basePrice = $schedule->base_fare;

        return view('client.pages.seat', compact(
            'schedule',
            'pickup_id',
            'dropoff_id',
            'seats',
            'bookedSeats',
            'maxSeats',
            'isBed',
            'basePrice',
            'busType'   // thêm biến này
        ));
    }
}

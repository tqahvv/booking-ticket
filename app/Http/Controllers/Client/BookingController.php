<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\Schedule;
use App\Models\ScheduleTemplate;
use App\Models\VehicleSeatTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $seatsNeeded = $request->query('seats');

        $seats = VehicleSeatTemplate::where('vehicle_type_id', $schedule->vehicle_type_id)
            ->orderBy('deck')
            ->orderBy('row')
            ->orderBy('column')
            ->get();

        $vehicleType = $schedule->vehicleType;
        $isBed = str_contains(strtolower($vehicleType->name), 'giường');

        if ($vehicleType->name == 'Ghế ngồi 29 chỗ') {
            $busType = 'seat_29';
        } elseif ($vehicleType->name == 'Ghế ngồi 40 chỗ') {
            $busType = 'seat_40';
        } elseif (str_contains(strtolower($vehicleType->name), 'giường')) {
            $busType = 'bed_44';
        } else {
            $busType = 'seat_29';
        }

        $bookedSeats = [];
        $maxSeats = $request->seats;
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
            'busType'
        ));
    }

    public function updateSelectedSeats(Request $request)
    {
        session()->put('selected_seats', $request->selected_seats);

        return response()->json([
            'status' => 'ok',
            'selected' => $request->selected_seats
        ]);
    }

    public function showCustomerInfo(Request $request)
    {
        $schedule = Schedule::with(['vehicleType', 'route.pickups.location', 'route.dropoffs.location', 'operator'])
            ->findOrFail($request->schedule_id);

        $selectedSeats = explode(',', $request->selected ?? '');
        $pickupId = $request->pickup_id;
        $dropoffId = $request->dropoff_id;
        $totalPrice = $request->total_price;

        return view('client.pages.customer-info', compact(
            'schedule',
            'selectedSeats',
            'pickupId',
            'dropoffId',
            'totalPrice'
        ));
    }

    public function storeCustomerInfo(Request $request)
    {
        $request->validate([
            'passenger_name' => 'required|string|max:255',
            'passenger_phone' => 'required|string|max:20',
            'passenger_email' => 'required|email|max:255',
            'schedule_id' => 'required|integer|exists:schedules,id',
            'pickup_id' => 'required|integer',
            'dropoff_id' => 'required|integer',
            'selected_seats' => 'required|string',
        ]);

        $userId = Auth::id() ?? null;

        $selectedSeats = explode(',', $request->selected_seats);
        $numSeats = count($selectedSeats);

        $schedule = Schedule::findOrFail($request->schedule_id);
        $totalPrice = $request->total_price;

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'user_id' => $userId,
                'schedule_id' => $request->schedule_id,
                'payment_method_id' => null,
                'booking_date' => Carbon::now(),
                'total_price' => $totalPrice,
                'num_passengers' => $numSeats,
                'status' => 'pending',
                'currency' => 'VND',
            ]);

            foreach ($selectedSeats as $seat) {
                BookingPassenger::create([
                    'booking_id' => $booking->id,
                    'passenger_name' => $request->passenger_name,
                    'passenger_phone' => $request->passenger_phone,
                    'identification_type' => $request->identification_type ?? null,
                    'identification_number' => $request->identification_number ?? null,
                    'seat_number' => $seat,
                    'pickup_stop_id' => $request->pickup_id,
                    'dropoff_stop_id' => $request->dropoff_id,
                ]);
            }

            DB::commit();
            return redirect()->route('booking.payment', ['booking_id' => $booking->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Đặt vé thất bại. Vui lòng thử lại!']);
        }
    }
}

<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BankTransfer;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\PaymentMethod;
use App\Models\Schedule;
use App\Models\ScheduleTemplate;
use App\Models\VehicleSeatTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function choosePickup(Request $request)
    {
        $scheduleTemplate = ScheduleTemplate::with([
            'operator',
            'vehicleType',
            'route.origin',
            'route.destination',
            'route.pickups.location',
            'route.dropoffs.location'
        ])->findOrFail($request->schedule_id);

        $selectedDate = $request->input('date');
        $schedule = Schedule::firstOrCreate(
            [
                'schedule_template_id' => $scheduleTemplate->id,
                'departure_datetime' => $selectedDate . ' ' . $scheduleTemplate->departure_time
            ],
            [
                'route_id' => $scheduleTemplate->route_id,
                'operator_id' => $scheduleTemplate->operator_id,
                'vehicle_type_id' => $scheduleTemplate->vehicle_type_id,
                'arrival_datetime' => Carbon::parse($selectedDate . ' ' . $scheduleTemplate->departure_time)
                    ->addMinutes($scheduleTemplate->travel_duration_minutes),
                'total_seats' => $scheduleTemplate->default_seats,
                'seats_available' => $scheduleTemplate->default_seats,
                'base_fare' => $scheduleTemplate->base_fare,
                'status' => 'scheduled',
            ]
        );

        $departure = Carbon::parse($scheduleTemplate->departure_time);
        $arrival = $departure->copy()->addMinutes($scheduleTemplate->travel_duration_minutes);
        $durationMinutes = $scheduleTemplate->travel_duration_minutes;
        $durationHours = floor($durationMinutes / 60);
        $durationRemainMinutes = $durationMinutes % 60;
        $durationText = $durationHours . 'h' . ($durationRemainMinutes > 0 ? $durationRemainMinutes . 'm' : '');
        $bookingDate = Carbon::parse($schedule->departure_datetime)->format('Y-m-d');

        $bookedSeatsCount = BookingPassenger::whereHas('booking', function ($q) use ($schedule, $bookingDate) {
            $q->where('schedule_id', $schedule->id)
                ->whereDate('booking_date', $bookingDate);
        })->count();

        $seatsAvailable = $schedule->total_seats - $bookedSeatsCount;

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
        $schedule = Schedule::with(['operator', 'vehicleType'])
            ->findOrFail($request->schedule_id);

        $pickup_id = $request->pickup_id;
        $dropoff_id = $request->dropoff_id;
        $seatsNeeded = $request->query('seats');

        $bookingDate = Carbon::parse($schedule->departure_datetime)->format('Y-m-d');

        $bookedSeats = BookingPassenger::whereHas('booking', function ($q) use ($schedule) {
            $q->where('schedule_id', $schedule->id)
                ->where('status', 'confirmed');
        })->pluck('seat_number')->toArray();

        $seatsAvailable = $schedule->total_seats - count($bookedSeats);

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

    public function cacheSeatSelection(Request $request)
    {
        $seats = is_array($request->selected_seats)
            ? $request->selected_seats
            : explode(',', $request->selected_seats);

        session([
            'selected_schedule_id' => $request->schedule_id,
            'selected_pickup_id'   => $request->pickup_id,
            'selected_dropoff_id'  => $request->dropoff_id,
            'selected_seats'       => $seats,
            'num_seats'            => $request->seats,
            'total_price'          => $request->total_price,
        ]);

        return response()->json(['status' => 'ok']);
    }

    public function showCustomerInfo(Request $request)
    {
        $scheduleId = session('selected_schedule_id');
        $selectedSeats = session('selected_seats', []);
        if (!is_array($selectedSeats)) {
            $selectedSeats = explode(',', $selectedSeats);
        }
        $pickupId = session('selected_pickup_id');
        $dropoffId = session('selected_dropoff_id');
        $totalPrice = session('total_price', 0);

        if (!$scheduleId || !$pickupId || !$dropoffId || empty($selectedSeats)) {
            return redirect()->route('home')->with('error', 'Thiếu dữ liệu đặt vé.');
        }

        $schedule = Schedule::with([
            'vehicleType',
            'route.pickups.location',
            'route.dropoffs.location',
            'operator'
        ])->findOrFail($scheduleId);

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
                'code' => 'BK-' . Str::upper(Str::random(6)),
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
                    'passenger_email' => $request->passenger_email,
                    'seat_number' => $seat,
                    'pickup_stop_id' => $request->pickup_id,
                    'dropoff_stop_id' => $request->dropoff_id,
                ]);
            }

            DB::commit();
            return redirect()->route('booking.payment', ['booking_id' => $booking->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function showPayment(Request $request)
    {
        $booking = Booking::with(['schedule.route.origin', 'schedule.route.destination', 'passengers'])->findOrFail($request->booking_id);

        $paymentMethods = PaymentMethod::where('active_flag', 1)->get();

        return view('client.pages.payment', compact('booking', 'paymentMethods'));
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $booking = Booking::with(['passengers'])->findOrFail($request->booking_id);
        $method = PaymentMethod::findOrFail($request->payment_method_id);

        switch ($method->type) {

            case 'cod':
                DB::beginTransaction();
                try {
                    $schedule = Schedule::lockForUpdate()->findOrFail($booking->schedule_id);
                    $bookingDate = Carbon::parse($schedule->departure_datetime)->format('Y-m-d');

                    $bookedSeatsCount = BookingPassenger::whereHas('booking', function ($q) use ($schedule, $bookingDate) {
                        $q->where('schedule_id', $schedule->id)
                            ->whereDate('booking_date', $bookingDate)
                            ->where('status', 'confirmed');
                    })->count();

                    $availableSeats = $schedule->total_seats - $bookedSeatsCount;

                    if ($availableSeats < $booking->num_passengers) {
                        DB::rollBack();
                        return back()->withErrors(['error' => 'Không còn đủ chỗ trên chuyến này. Vui lòng chọn chuyến khác.']);
                    }

                    $booking->payment_method_id = $method->id;
                    $booking->status = 'confirmed';
                    $booking->paid = false;
                    $booking->save();

                    $schedule->decrement('seats_available', $booking->num_passengers);

                    Log::info("Booking {$booking->id} confirmed as COD, seats reduced: {$booking->num_passengers}");

                    DB::commit();

                    return redirect()->route('booking.completed', ['booking_id' => $booking->id]);
                } catch (\Throwable $e) {
                    DB::rollBack();
                    Log::error('COD confirm error: ' . $e->getMessage());
                    return back()->withErrors(['error' => 'Xác nhận thất bại. Vui lòng thử lại.']);
                }

            case 'bank_transfer':
                $booking->payment_method_id = $method->id;
                $booking->status = 'waiting_transfer';
                $booking->paid = false;
                $booking->save();

                return redirect()->route('booking.bank-transfer', ['booking_id' => $booking->id]);

            case 'online':
                // return $this->startOnlinePayment($booking, $method);

            default:
                abort(400, 'Phương thức thanh toán không hợp lệ.');
        }
    }

    public function showBankTransfer(Request $request)
    {
        $booking = Booking::with(['passengers', 'schedule'])->findOrFail($request->booking_id);

        $transfer = BankTransfer::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'bank_name' => 'Ngân hàng A',
                'account_number' => '1234567890',
                'account_name' => 'Công ty XYZ',
                'amount' => $booking->total_price,
                'expires_at' => Carbon::now()->addMinutes(15),
            ]
        );

        return view('client.pages.bank-transfer', compact('booking', 'transfer'));
    }

    public function confirmBankTransfer(Request $request)
    {
        $booking = Booking::with('passengers')->findOrFail($request->booking_id);
        $transfer = BankTransfer::where('booking_id', $booking->id)->firstOrFail();

        $transfer->status = 'confirmed';
        $transfer->save();

        $booking->status = 'confirmed';
        $booking->paid = true;
        $booking->save();

        return redirect()->route('booking.completed', ['booking_id' => $booking->id])
            ->with('success', 'Thanh toán chuyển khoản đã được xác nhận.');
    }

    public function completed(Request $request)
    {
        $booking = Booking::with(['passengers', 'schedule.route.origin', 'schedule.route.destination'])->findOrFail($request->booking_id);
        return view('client.pages.completed', compact('booking'));
    }
}

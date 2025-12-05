<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BankTransfer;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\PaymentMethod;
use App\Models\Promotion;
use App\Models\Schedule;
use App\Models\ScheduleTemplate;
use App\Models\Ticket;
use App\Models\VehicleSeatTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Services\TicketService;

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
            'discount_amount'      => 0,
            'final_price'          => $request->total_price,
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
        $promotionId = session('promotion_id');

        if (!$scheduleId || !$pickupId || !$dropoffId || empty($selectedSeats)) {
            return redirect()->route('home')->with('error', 'Thiếu dữ liệu đặt vé.');
        }

        $schedule = Schedule::with([
            'vehicleType',
            'route.pickups.location',
            'route.dropoffs.location',
            'operator'
        ])->findOrFail($scheduleId);

        $discountAmount = session('discount_amount', 0);
        $finalPrice = session('final_price', $totalPrice);
        $validPromotions = Promotion::where('is_active', 1)
            ->where('valid_from', '<=', now())
            ->where('valid_to', '>=', now())
            ->get();

        $invalidPromotions = Promotion::where(function ($q) {
            $q->where('is_active', 0)
                ->orWhere('valid_from', '>', now())
                ->orWhere('valid_to', '<', now());
        })
            ->get();

        $promotions = $validPromotions->merge($invalidPromotions);


        return view('client.pages.customer-info', compact(
            'schedule',
            'selectedSeats',
            'pickupId',
            'dropoffId',
            'totalPrice',
            'discountAmount',
            'finalPrice',
            'promotionId',
            'promotions'
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
            'total_price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'final_price' => 'required|numeric|min:0',
            'promotion_id' => 'nullable|integer|exists:promotions,id',
        ]);

        $userId = Auth::id() ?? null;

        $selectedSeats = explode(',', $request->selected_seats);
        $numSeats = count($selectedSeats);

        $schedule = Schedule::findOrFail($request->schedule_id);

        $originalPrice   = (float)$request->total_price;
        $discountAmount  = (float)($request->discount_amount ?? 0);
        $finalPrice      = (float)$request->final_price;
        $promotionId     = $request->promotion_id ?? null;

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'code' => 'BK-' . Str::upper(Str::random(6)),
                'user_id' => $userId,
                'schedule_id' => $request->schedule_id,
                'payment_method_id' => null,
                'booking_date' => Carbon::now(),
                'total_price' => $originalPrice,
                'discount_amount' => $discountAmount,
                'final_price' => $finalPrice,
                'num_passengers' => $numSeats,
                'promotion_id' => $promotionId,
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

    public function applyPromo(Request $request)
    {
        $promo = Promotion::where('code', $request->promo_code)
            ->where('is_active', 1)
            ->where('valid_from', '<=', now())
            ->where('valid_to', '>=', now())
            ->first();

        if (!$promo) {
            return response()->json(['error' => 'Mã giảm giá không hợp lệ'], 200);
        }

        $total = $request->total_price;

        $discount = $promo->discount_type === 'percentage'
            ? $total * ($promo->discount_value / 100)
            : $promo->discount_value;

        $discount = min($discount, $total);

        session([
            'discount_amount' => $discount,
            'final_price' => $total - $discount,
            'promotion_id' => $promo->id
        ]);

        return response()->json([
            'discount' => $discount,
            'final' => $total - $discount
        ]);
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

                    $this->tickets->generateTickets($booking);

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

            case 'vnpay':
                return $this->startVnpayPayment($booking);

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
                'amount' => $booking->final_price,
                'expires_at' => Carbon::now()->addMinutes(15),
            ]
        );

        return view('client.pages.bank-transfer', compact('booking', 'transfer'));
    }

    public function confirmBankTransfer(Request $request)
    {
        DB::beginTransaction();
        try {
            $booking = Booking::with('passengers')->findOrFail($request->booking_id);
            $transfer = BankTransfer::where('booking_id', $booking->id)->firstOrFail();

            $transfer->status = 'confirmed';
            $transfer->save();

            $booking->status = 'confirmed';
            $booking->paid = true;
            $booking->save();

            foreach ($booking->passengers as $passenger) {
                $passenger->status = 'confirmed';
                $passenger->save();
            }

            $this->tickets->generateTickets($booking);

            DB::commit();

            return redirect()->route('booking.completed', ['booking_id' => $booking->id])
                ->with('success', 'Thanh toán chuyển khoản đã được xác nhận và vé đã được phát hành.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Bank transfer confirm error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Xác nhận thất bại. Vui lòng thử lại.']);
        }
    }

    public function startVnpayPayment($booking)
    {
        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_ReturnUrl = config('vnpay.vnp_ReturnUrl');
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $booking->final_price * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => "Thanh toan ve xe #" . $booking->code,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $booking->code,
        ];

        ksort($inputData);

        // Build query
        $query = "";
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($hashdata != "") {
                $hashdata .= '&';
            }
            $hashdata .= urlencode($key) . "=" . urlencode($value);

            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url .= "?" . $query;

        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= "vnp_SecureHash=" . $vnpSecureHash;
        }

        // Cập nhật trạng thái booking
        $booking->payment_method_id = PaymentMethod::where('type', 'vnpay')->first()->id;
        $booking->status = 'waiting_payment';
        $booking->save();

        return redirect($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $inputData = $request->all();

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? null;

        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        ksort($inputData);

        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($hashData != "") {
                $hashData .= '&';
            }
            $hashData .= urlencode($key) . "=" . urlencode($value);
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash !== $vnp_SecureHash) {
            return redirect()->route('booking.payment')
                ->withErrors(['error' => 'Sai chữ ký! Dữ liệu có thể bị thay đổi.']);
        }

        if ($request->vnp_ResponseCode == "00" && $request->vnp_TransactionStatus == "00") {

            $booking = Booking::with('passengers')->where('code', $request->vnp_TxnRef)->firstOrFail();

            $booking->status = 'confirmed';
            $booking->paid = true;
            $booking->save();

            $this->tickets->generateTickets($booking);

            return redirect()->route('booking.completed', [
                'booking_id' => $booking->id
            ])->with('success', 'Thanh toán VNPAY thành công!');
        }

        return redirect()->route('booking.payment')
            ->withErrors(['error' => 'Thanh toán không thành công. Mã lỗi: ' . $request->vnp_ResponseCode]);
    }

    public function completed(Request $request)
    {
        $booking = Booking::with([
            'passengers',
            'tickets',
            'schedule.route.origin',
            'schedule.route.destination'
        ])->findOrFail($request->booking_id);

        return view('client.pages.completed', compact('booking'));
    }

    public function __construct(private TicketService $tickets) {}
}

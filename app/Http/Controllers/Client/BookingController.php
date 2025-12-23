<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmedMail;
use App\Models\BankTransfer;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\Payment;
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
use Illuminate\Support\Facades\Mail;

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

        $seatsAvailable = $schedule->seats_available;

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

        $seatsAvailable = $schedule->seats_available;

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

        $expectedSeats = (int) $request->seats;

        if (count($seats) !== $expectedSeats) {
            return response()->json([
                'status' => 'error',
                'message' => "Bạn phải chọn đúng {$expectedSeats} ghế."
            ], 422);
        }

        session([
            'selected_schedule_id' => $request->schedule_id,
            'selected_pickup_id'   => $request->pickup_id,
            'selected_dropoff_id'  => $request->dropoff_id,
            'selected_seats'       => $seats,
            'num_seats'            => $expectedSeats,
            'total_price'          => $request->total_price,
            'discount_amount'      => 0,
            'final_price'          => $request->total_price,
            'seat_selected_at'     => now(),
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

        $selectedSeats = explode(',', $request->selected_seats);
        $expectedSeats = session('num_seats');

        if (count($selectedSeats) !== (int)$expectedSeats) {
            return back()->withErrors([
                'error' => "Số ghế đã chọn không hợp lệ. Vui lòng chọn đúng {$expectedSeats} ghế."
            ]);
        }

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
                'expires_at' => now()->addMinutes(15),
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

        $booking = Booking::with('passengers')->findOrFail($request->booking_id);
        $method  = PaymentMethod::findOrFail($request->payment_method_id);

        if ($booking->expires_at && now()->greaterThan($booking->expires_at)) {
            return redirect()->route('home')
                ->withErrors(['error' => 'Đơn đặt vé đã hết hạn. Vui lòng đặt lại.']);
        }

        switch ($method->type) {

            case 'cod':
                DB::beginTransaction();
                try {
                    $payment = Payment::create([
                        'booking_id' => $booking->id,
                        'payment_method_id' => $method->id,
                        'amount' => $booking->final_price,
                        'currency' => $booking->currency,
                        'status' => 'pending',
                    ]);

                    $schedule = Schedule::lockForUpdate()->findOrFail($booking->schedule_id);

                    $bookedSeatsCount = BookingPassenger::whereHas('booking', function ($q) use ($schedule) {
                        $q->where('schedule_id', $schedule->id)
                            ->where('status', 'confirmed');
                    })->count();

                    $availableSeats = $schedule->total_seats - $bookedSeatsCount;

                    if ($availableSeats < $booking->num_passengers) {
                        DB::rollBack();
                        return back()->withErrors(['error' => 'Không còn đủ chỗ trên chuyến này.']);
                    }

                    $booking->update([
                        'payment_method_id' => $method->id,
                        'status' => 'confirmed',
                        'paid' => false,
                    ]);

                    $booking->passengers()->update([
                        'status' => 'confirmed'
                    ]);

                    $payment->update([
                        'status' => 'pending',
                        'paid_at' => now(),
                    ]);

                    $this->tickets->generateTickets($booking);
                    $schedule->decrement('seats_available', $booking->num_passengers);

                    DB::commit();

                    $emailList = $booking->passengers
                        ->pluck('passenger_email')
                        ->filter()
                        ->unique()
                        ->toArray();

                    if (empty($emailList) && $booking->user) {
                        $emailList = [$booking->user->email];
                    }

                    Mail::to($emailList)->send(
                        new BookingConfirmedMail(
                            $booking->load([
                                'passengers',
                                'tickets',
                                'schedule.route.origin',
                                'schedule.route.destination'
                            ])
                        )
                    );

                    return redirect()->route('booking.completed', [
                        'booking_id' => $booking->id
                    ]);
                } catch (\Throwable $e) {
                    DB::rollBack();
                    Log::error('COD confirm error: ' . $e->getMessage());
                    return back()->withErrors(['error' => 'Xác nhận thất bại.']);
                }
            case 'vnpay':

                $payment = Payment::create([
                    'booking_id'        => $booking->id,
                    'payment_method_id' => $method->id,
                    'amount'            => $booking->final_price,
                    'currency'          => $booking->currency,
                    'status'            => 'pending',
                ]);

                return $this->startVnpayPayment($booking, $payment);

            default:
                abort(400, 'Phương thức thanh toán không hợp lệ.');
        }
    }

    public function startVnpayPayment(Booking $booking, Payment $payment)
    {
        $vnp_Url        = config('vnpay.vnp_Url');
        $vnp_ReturnUrl  = config('vnpay.vnp_ReturnUrl');
        $vnp_TmnCode    = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $txnRef = 'PAY-' . $payment->id;

        $payment->update([
            'transaction_code' => $txnRef
        ]);

        $inputData = [
            "vnp_Version"    => "2.1.0",
            "vnp_TmnCode"    => $vnp_TmnCode,
            "vnp_Amount"     => $booking->final_price * 100,
            "vnp_Command"    => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode"   => "VND",
            "vnp_IpAddr"     => request()->ip(),
            "vnp_Locale"     => "vn",
            "vnp_OrderInfo"  => "Thanh toan ve xe #" . $booking->code,
            "vnp_OrderType"  => "billpayment",
            "vnp_ReturnUrl"  => $vnp_ReturnUrl,
            "vnp_TxnRef"     => $txnRef,
        ];

        ksort($inputData);

        $hashdata = "";
        $query    = "";
        foreach ($inputData as $key => $value) {
            $hashdata .= urlencode($key) . "=" . urlencode($value) . '&';
            $query    .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $hashdata = rtrim($hashdata, '&');
        $query    = rtrim($query, '&');

        $vnp_Url .= "?" . $query;
        $vnp_Url .= "&vnp_SecureHash=" . hash_hmac('sha512', $hashdata, $vnp_HashSecret);

        $booking->update([
            'payment_method_id' => $payment->payment_method_id,
            'status'            => 'waiting_payment',
        ]);

        return redirect($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $inputData = $request->all();

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? null;
        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);

        ksort($inputData);

        $hashData = "";
        foreach ($inputData as $key => $value) {
            $hashData .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $hashData = rtrim($hashData, '&');

        if (hash_hmac('sha512', $hashData, $vnp_HashSecret) !== $vnp_SecureHash) {
            return redirect()->route('booking.payment')
                ->withErrors(['error' => 'Sai chữ ký!']);
        }

        $payment = Payment::where('transaction_code', $request->vnp_TxnRef)->first();

        if (!$payment) {
            return redirect()->route('home')
                ->withErrors(['error' => 'Không tìm thấy giao dịch thanh toán.']);
        }

        $booking = $payment->booking;

        if ($request->vnp_ResponseCode == "00" && $request->vnp_TransactionStatus == "00") {

            DB::transaction(function () use ($booking, $payment, $request) {

                $booking->update([
                    'status' => 'confirmed',
                    'paid'   => true,
                ]);

                $payment->update([
                    'status'   => 'success',
                    'paid_at' => now(),
                    'meta'     => $request->all(),
                ]);

                $this->tickets->generateTickets($booking);
            });

            $emailList = $booking->passengers
                ->pluck('passenger_email')
                ->filter()
                ->unique()
                ->toArray();

            if (empty($emailList) && $booking->user) {
                $emailList = [$booking->user->email];
            }

            Mail::to($emailList)->send(
                new BookingConfirmedMail(
                    $booking->load([
                        'passengers',
                        'tickets',
                        'schedule.route.origin',
                        'schedule.route.destination'
                    ])
                )
            );

            return redirect()->route('booking.completed', [
                'booking_id' => $booking->id
            ])->with('success', 'Thanh toán VNPAY thành công!');
        }

        $payment->update([
            'status' => 'failed',
            'meta'   => $request->all(),
        ]);

        return redirect()->route('booking.payment', [
            'booking_id' => $booking->id
        ])->with('warning', 'Bạn đã hủy thanh toán VNPAY.');
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

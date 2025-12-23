<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PaymentAdminController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        $payments = Payment::with([
            'booking.passengers',
            'booking.schedule.route.origin',
            'booking.schedule.route.destination',
            'paymentMethod'
        ])
            ->whereHas('booking.schedule', function ($q) use ($admin) {
                $q->where('operator_id', $admin->operator_id);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($payment) {
                return Carbon::parse($payment->booking->schedule->departure_datetime)->format('Y-m-d');
            });

        return view('admin.pages.payments', compact('payments'));
    }

    public function confirmCOD($id)
    {
        $admin = Auth::guard('admin')->user();

        $payment = Payment::with(['booking', 'paymentMethod'])
            ->whereHas('booking.schedule', function ($q) use ($admin) {
                $q->where('operator_id', $admin->operator_id);
            })
            ->findOrFail($id);

        if ($payment->paymentMethod->type !== 'cod' || $payment->status !== 'pending') {
            return response()->json(['error' => 'Không hợp lệ'], 422);
        }

        $payment->status = 'success';
        $payment->paid_at = now();
        $payment->save();

        $booking = $payment->booking;
        $booking->paid = true;
        $booking->status = 'confirmed';
        $booking->save();

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $admin = Auth::guard('admin')->user();

        $payment = Payment::with([
            'booking.passengers',
            'booking.schedule.route.origin',
            'booking.schedule.route.destination',
            'paymentMethod'
        ])
            ->whereHas('booking.schedule', function ($q) use ($admin) {
                $q->where('operator_id', $admin->operator_id);
            })
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'payment' => $payment->toArray()
        ]);
    }
}

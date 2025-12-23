<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingAdminController extends Controller
{
    public function index()
    {
        $user = auth()->guard('admin')->user();

        $query = Booking::with(['user', 'schedule.route', 'passengers'])
            ->orderBy('created_at', 'DESC');

        if ($user->role->name === 'bus') {
            $query->whereHas('schedule', function ($q) use ($user) {
                $q->where('operator_id', $user->operator_id);
            });
        }

        $bookings = $query->get()->groupBy(function ($booking) {
            return $booking->created_at->format('Y-m-d');
        });

        return view('admin.pages.bookings', compact('bookings'));
    }

    public function show($id)
    {
        $user = auth()->guard('admin')->user();

        $query = Booking::with([
            'user',
            'schedule.route',
            'passengers.pickupStop.location',
            'passengers.dropoffStop.location',
            'bankTransfer'
        ]);

        if ($user->role->name === 'bus') {
            $query->whereHas('schedule', function ($q) use ($user) {
                $q->where('operator_id', $user->operator_id);
            });
        }

        $booking = $query->findOrFail($id);

        return view('admin.pages.booking-show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,expired',
        ]);

        $user = auth()->guard('admin')->user();

        $query = Booking::query();

        if ($user->role->name === 'bus') {
            $query->whereHas('schedule', function ($q) use ($user) {
                $q->where('operator_id', $user->operator_id);
            });
        }

        $booking = $query->findOrFail($id);
        $booking->status = $request->status;
        if ($request->status === 'cancelled') {
            if ($booking->payment) {
                $booking->payment->status = 'cancelled';
                $booking->payment->save();
            }

            foreach ($booking->tickets as $ticket) {
                $ticket->status = 'cancelled';
                $ticket->save();
            }

            $booking->paid = false;
        }

        $booking->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
    }
}

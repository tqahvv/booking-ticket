<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingAdminController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'schedule.route', 'passengers'])
            ->orderBy('created_at', 'DESC')->get();

        return view('admin.pages.bookings', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with([
            'user',
            'schedule.route',
            'passengers.pickupStop.location',
            'passengers.dropoffStop.location',
            'bankTransfer'
        ])->findOrFail($id);

        return view('admin.pages.booking-show', compact('booking'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,expired,waiting_transfer',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
    }

    public function confirmTransfer($id)
    {
        DB::beginTransaction();

        try {
            $booking = Booking::with('bankTransfer')->findOrFail($id);

            if (!$booking->bankTransfer) {
                return response()->json(['success' => false, 'message' => 'Không có yêu cầu chuyển khoản!']);
            }

            $booking->status = 'confirmed';
            $booking->save();

            $booking->bankTransfer->confirmed_at = now();
            $booking->bankTransfer->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Đã xác nhận chuyển khoản']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Lỗi server']);
        }
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $booking = Booking::findOrFail($id);

            $booking->passengers()->delete();
            $booking->bankTransfer()->delete();
            $booking->delete();
        });

        return response()->json(['success' => true, 'message' => 'Xóa đặt chỗ thành công']);
    }
}

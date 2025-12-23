<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketAdminController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        $query = Ticket::with('booking.schedule');

        if ($admin->operator_id) {
            $query->whereHas('booking.schedule', function ($q) use ($admin) {
                $q->where('operator_id', $admin->operator_id);
            });
        }

        $tickets = $query
            ->orderBy('valid_from', 'DESC')
            ->get()
            ->groupBy(function ($ticket) {
                return Carbon::parse($ticket->valid_from)->format('Y-m-d');
            });

        return view('admin.pages.tickets', compact('tickets'));
    }

    public function show($id)
    {
        $admin = Auth::guard('admin')->user();

        $query = Ticket::with([
            'booking.passengers',
            'booking.paymentMethod',
            'booking.schedule'
        ]);

        if ($admin->operator_id) {
            $query->whereHas('booking.schedule', function ($q) use ($admin) {
                $q->where('operator_id', $admin->operator_id);
            });
        }

        $ticket = $query->find($id);

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy vé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $ticket
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:unused,used,expired,cancelled'
        ]);

        $admin = Auth::guard('admin')->user();

        $query = Ticket::query();

        if ($admin->operator_id) {
            $query->whereHas('booking.schedule', function ($q) use ($admin) {
                $q->where('operator_id', $admin->operator_id);
            });
        }

        $ticket = $query->findOrFail($id);

        $ticket->status = $request->status;
        $ticket->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái vé thành công'
        ]);
    }
}

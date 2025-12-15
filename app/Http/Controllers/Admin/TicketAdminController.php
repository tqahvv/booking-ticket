<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketAdminController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('booking')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.pages.tickets', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = Ticket::with([
            'booking.passengers',
            'booking.paymentMethod'
        ])->find($id);

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

        $ticket = Ticket::findOrFail($id);
        $ticket->status = $request->status;
        $ticket->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái vé thành công'
        ]);
    }

    public function delete($id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->status === 'used') {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa vé đã sử dụng'
            ], 422);
        }

        $ticket->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa vé'
        ]);
    }
}

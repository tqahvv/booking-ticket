<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $populars = Image::where('linked_type', 'route')->latest()->take(6)->get();
        $posts = Post::where('status', 'published')->latest()->take(6)->get();
        $from = $request->query('from');
        $to = $request->query('to');
        $date = $request->query('date');
        $seats = $request->query('seats', 1);
        return view('client.pages.home', compact('populars', 'posts', 'from', 'to', 'date', 'seats'));
    }

    public function booking(Request $request)
    {
        $user = Auth::user();
        $email = $request->query('email');
        $phone = $request->query('phone');

        if ($user) {
            $bookings = Booking::with([
                'schedule',
                'passengers',
                'passengers.pickupStop',
                'passengers.dropoffStop',
                'tickets'
            ])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $bookings = collect();

            if ($email || $phone) {
                $bookings = Booking::with([
                    'schedule',
                    'passengers',
                    'tickets'
                ])
                    ->whereHas('passengers', function ($query) use ($email, $phone) {
                        if ($email) $query->where('passenger_email', $email);
                        if ($phone) $query->where('passenger_phone', $phone);
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }
        return view('client.pages.bookings-index', compact('bookings', 'user'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        if ($booking->user_id) {
            if (!Auth::check() || $booking->user_id !== Auth::id()) {
                abort(403, 'Bạn không có quyền hủy vé này');
            }
        } else {
            $email = $request->input('email');
            $phone = $request->input('phone');

            if (!$email || !$phone) {
                $passenger = $booking->passengers()->first();
                if ($passenger) {
                    $email = $passenger->passenger_email;
                    $phone = $passenger->passenger_phone;
                }
            }

            $exists = $booking->passengers()
                ->where('passenger_email', $email)
                ->where('passenger_phone', $phone)
                ->exists();

            if (!$exists) {
                return response()->json(['message' => 'Bạn không có quyền hủy vé này'], 403);
            }
        }


        if ($booking->paymentMethod && $booking->paymentMethod->type !== 'cod') {
            return response()->json(['message' => 'Chỉ COD mới được hủy vé'], 403);
        }

        if (!$booking->canCancel()) {
            return response()->json(['message' => 'Vé này không thể hủy'], 400);
        }

        DB::transaction(function () use ($booking) {
            $booking->update([
                'status' => 'cancelled',
                'paid' => false
            ]);

            $booking->passengers()->update(['status' => 'cancelled']);

            $booking->tickets()->update(['status' => 'cancelled']);

            $booking->payment->update(['status' => 'cancelled']);

            $booking->schedule->increment('seats_available', $booking->num_passengers);
        });

        return response()->json(['message' => 'Hủy vé thành công']);
    }
}

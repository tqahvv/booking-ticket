<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}

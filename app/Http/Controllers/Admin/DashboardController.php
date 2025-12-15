<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Operator; // hoặc BusCompany nếu bạn đặt tên khác
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role_id == 1) {
            return view('admin.pages.dashboard', [
                'totalUsers'      => User::where('role_id', 3)->count(),
                'totalAdmins'     => User::where('role_id', 1)->count(),
                'totalBusCompany' => User::where('role_id', 2)->count(),

                'totalPosts'      => Post::count(),
                'totalCategories' => Category::count(),
            ]);
        }

        if ($admin->role_id == 2) {
            return view('admin.pages.dashboard', [
                'totalSchedules' => Schedule::count(),
                'ticketsSold'    => Ticket::count(),
                'ticketsUsed'    => Ticket::where('status', 'used')->count(),
                'totalBookings'  => Booking::count(),
                'revenue'        => Booking::where('paid', 1)->sum('final_price'),
            ]);
        }
    }
}

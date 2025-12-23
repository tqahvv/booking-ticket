<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Operator;
use App\Models\Promotion;
use App\Models\Ticket;
use App\Models\Schedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->role_id == 1) {
            $totalUsers = User::where('role_id', 3)->count();

            $totalPosts = Post::count();

            $totalCategories = Category::count();

            $totalBusCompany = Operator::count();

            $latestUsers = User::where('role_id', 3)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            $latestBusCompanies = Operator::orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            return view('admin.pages.dashboard', [
                'totalUsers' => $totalUsers,
                'totalPosts' => $totalPosts,
                'totalCategories' => $totalCategories,
                'totalBusCompany' => $totalBusCompany,
                'latestUsers'        => $latestUsers,
                'latestBusCompanies' => $latestBusCompanies,
            ]);
        }

        if ($admin->role_id == 2) {

            $totalSchedules = Schedule::where('operator_id', $admin->operator_id)->count();

            $ticketsSold = Ticket::whereHas('booking.schedule', function ($q) use ($admin) {
                $q->where('operator_id', $admin->operator_id);
            })->count();

            $ticketsUsed = Ticket::where('status', 'used')
                ->whereHas('booking.schedule', function ($q) use ($admin) {
                    $q->where('operator_id', $admin->operator_id);
                })->count();

            $revenue = Booking::where('paid', 1)
                ->whereHas('schedule', function ($q) use ($admin) {
                    $q->where('operator_id', $admin->operator_id);
                })->sum('final_price');

            $dailyRevenue = Booking::select(
                DB::raw('DATE(created_at) as day'),
                DB::raw('SUM(final_price) as revenue')
            )
                ->where('paid', 1)
                ->whereHas('schedule', function ($q) use ($admin) {
                    $q->where('operator_id', $admin->operator_id);
                })
                ->whereDate('created_at', '>=', now()->subDays(7))
                ->groupBy('day')
                ->orderBy('day')
                ->get()
                ->map(function ($item) {
                    return [
                        'day' => Carbon::parse($item->day)->format('d/m'),
                        'revenue' => $item->revenue
                    ];
                });;

            $monthlyRevenue = Booking::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(final_price) as revenue')
            )
                ->where('paid', 1)
                ->whereHas('schedule', function ($q) use ($admin) {
                    $q->where('operator_id', $admin->operator_id);
                })
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->map(function ($item) {
                    return [
                        'month' => Carbon::createFromFormat('Y-m', $item->month)->format('m/Y'),
                        'revenue' => $item->revenue
                    ];
                });;

            $todayBookings = Booking::with('passengers', 'schedule.route')
                ->whereDate('created_at', now())
                ->whereHas('schedule', function ($q) use ($admin) {
                    $q->where('operator_id', $admin->operator_id);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return view('admin.pages.dashboard', [
                'totalSchedules' => $totalSchedules,
                'ticketsSold' => $ticketsSold,
                'ticketsUsed' => $ticketsUsed,
                'revenue' => $revenue,
                'dailyRevenue'   => $dailyRevenue,
                'monthlyRevenue' => $monthlyRevenue,
                'todayBookings'  => $todayBookings,
            ]);
        }
    }
}

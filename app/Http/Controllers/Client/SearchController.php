<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Route as BusRoute;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $from = trim($request->query('from', ''));
        $to = trim($request->query('to', ''));
        $date = $request->query('date');
        $seats = (int) $request->query('seats', 1);

        Carbon::setLocale('vi');

        $formattedDate = null;
        if ($date) {
            $formattedDate = Carbon::parse($date)->isoFormat('dddd, D MMMM YYYY');
            $formattedDate = mb_convert_case($formattedDate, MB_CASE_TITLE, "UTF-8");
        }

        if (!$from || !$to) {
            return view('client.pages.booking-home', [
                'schedules' => collect(),
                'from' => $from,
                'to' => $to,
                'date' => $date,
                'seats' => $seats,
            ]);
        }

        $origin = Location::where('name', 'like', "%{$from}%")
            ->orWhere('city', 'like', "%{$from}%")
            ->first();

        $destination = Location::where('name', 'like', "%{$to}%")
            ->orWhere('city', 'like', "%{$to}%")
            ->first();

        if (!$origin || !$destination) {
            return view('client.pages.booking-home', [
                'schedules' => collect(),
                'from' => $from,
                'to' => $to,
                'date' => $date,
                'seats' => $seats,
                'message' => 'Không tìm thấy điểm đi hoặc điểm đến',
            ]);
        }

        $routeIds = BusRoute::where('origin_location_id', $origin->id)
            ->where('destination_location_id', $destination->id)
            ->pluck('id');

        if ($routeIds->isEmpty()) {
            return view('client.pages.booking-home', [
                'schedules' => collect(),
                'from' => $from,
                'to' => $to,
                'date' => $date,
                'seats' => $seats,
                'message' => 'Không có tuyến đường nào giữa hai điểm này',
            ]);
        }

        $schedulesQuery = Schedule::with(['route.origin', 'route.destination', 'operator', 'vehicleType'])
            ->whereIn('route_id', $routeIds)
            ->where('seats_available', '>=', $seats)
            ->orderBy('departure_datetime', 'asc');

        if ($date) {
            $schedulesQuery->whereDate('departure_datetime', '=', $date);
        }

        $schedules = $schedulesQuery->get();

        return view('client.pages.booking-home', [
            'schedules' => $schedules,
            'from' => $from,
            'to' => $to,
            'date' => $date,
            'seats' => $seats,
            'formattedDate' => $formattedDate,
        ]);
    }
}

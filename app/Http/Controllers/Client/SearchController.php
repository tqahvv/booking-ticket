<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Route as BusRoute;
use App\Models\ScheduleTemplate;
use Carbon\Carbon;

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

        $routes = BusRoute::where('origin_location_id', $origin->id)
            ->where('destination_location_id', $destination->id)
            ->pluck('id');

        if ($routes->isEmpty()) {
            return view('client.pages.booking-home', [
                'schedules' => collect(),
                'message' => 'Không có tuyến đường nào giữa hai điểm này',
                'from' => $from,
                'to' => $to,
                'date' => $date,
                'seats' => $seats,
            ]);
        }

        $weekday = Carbon::parse($date)->dayOfWeekIso;

        $templates = ScheduleTemplate::with(['route.origin', 'route.destination', 'operator', 'vehicleType'])
            ->whereHas('route.origin', function ($q) use ($origin) {
                $q->where('city', $origin->city);
            })
            ->whereHas('route.destination', function ($q) use ($destination) {
                $q->where('city', $destination->city);
            })
            ->whereRaw("JSON_CONTAINS(running_days, '\"$weekday\"')")
            ->where('default_seats', '>=', $seats)
            ->get();

        if ($templates->isEmpty()) {
            return view('client.pages.booking-home', [
                'schedules' => collect(),
                'message' => 'Không có chuyến xe nào trong ngày này',
                'from' => $from,
                'to' => $to,
                'date' => $date,
                'seats' => $seats,
                'formattedDate' => $formattedDate,
            ]);
        }

        $schedules = $templates->map(function ($t) use ($date) {

            $departure = Carbon::parse($date . ' ' . $t->departure_time);
            $arrival = $departure->copy()->addMinutes($t->travel_duration_minutes);

            $durationMinutes = $t->travel_duration_minutes;
            $hours = floor($durationMinutes / 60);
            $minutes = $durationMinutes % 60;
            $durationText = $hours . ' giờ';
            if ($minutes > 0) {
                $durationText .= ' ' . $minutes . ' phút';
            }

            return (object)[
                "id" => $t->id,
                "route" => $t->route,
                "operator" => $t->operator,
                "vehicleType" => $t->vehicleType,
                "base_fare" => $t->base_fare,
                "seats_available" => $t->default_seats,
                "departure_datetime" => $departure,
                "arrival_datetime" => $arrival,
                "duration" => $durationText,
            ];
        });

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

<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Operator;
use App\Models\Route;
use App\Models\Schedule;
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

        $routes = Route::where('origin_location_id', $origin->id)
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
            $schedule = Schedule::where('schedule_template_id', $t->id)
                ->whereDate('departure_datetime', $date)
                ->first();

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
                "base_fare" => $schedule->base_fare ?? $t->base_fare,
                "seats_available" => $schedule->seats_available ?? $t->default_seats,
                "departure_datetime" => $schedule->departure_datetime ?? $departure,
                "arrival_datetime" => $schedule->arrival_datetime ?? $arrival,
                "duration" => $durationText,
            ];
        });

        $pickupPoints = Location::where('city', $origin->city)->get();
        $dropoffPoints = Location::where('city', $destination->city)->get();
        $operators = $templates->pluck('operator')->unique('id')->values();

        return view('client.pages.booking-home', [
            'schedules' => $schedules,
            'from' => $from,
            'to' => $to,
            'date' => $date,
            'seats' => $seats,
            'formattedDate' => $formattedDate,
            'pickupPoints' => $pickupPoints,
            'dropoffPoints' => $dropoffPoints,
            'operators' => $operators,
        ]);
    }

    public function filterAjax(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $date = $request->date;
        $seats = (int) $request->seats;

        $pickup = $request->pickup;
        $dropoff = $request->dropoff;
        $operator = $request->operator;
        $timeRange = $request->time;

        $origin = Location::where('city', 'like', "%{$from}%")->first();
        $destination = Location::where('city', 'like', "%{$to}%")->first();

        if (!$origin || !$destination) {
            return response()->json(['data' => []]);
        }

        $weekday = Carbon::parse($date)->dayOfWeekIso;

        $templates = ScheduleTemplate::with([
            'route.origin',
            'route.destination',
            'operator',
            'vehicleType'
        ])
            ->whereRaw("JSON_CONTAINS(running_days, '\"$weekday\"')")
            ->where('default_seats', '>=', $seats)
            ->get();

        if ($pickup) {
            $templates = $templates->filter(fn($t) => $t->route->origin_location_id == $pickup);
        }

        if ($dropoff) {
            $templates = $templates->filter(fn($t) => $t->route->destination_location_id == $dropoff);
        }

        if ($operator) {
            $templates = $templates->filter(fn($t) => $t->operator_id == $operator);
        }

        if ($timeRange) {
            [$start, $end] = explode('-', $timeRange);
            $templates = $templates->filter(
                fn($t) =>
                $t->departure_time >= $start && $t->departure_time <= $end
            );
        }

        return response()->json([
            'data' => $templates->map(function ($t) use ($date) {

                $departure = Carbon::parse($date . ' ' . $t->departure_time);
                $arrival = $departure->copy()->addMinutes($t->travel_duration_minutes);

                $durationMinutes = $t->travel_duration_minutes;
                $hours = floor($durationMinutes / 60);
                $minutes = $durationMinutes % 60;
                $durationText = $hours . ' giờ' . ($minutes ? " $minutes phút" : "");

                return [
                    "id" => $t->id,
                    "operator" => [
                        "name" => $t->operator->name,
                    ],
                    "vehicle_type" => [
                        "name" => $t->vehicleType->name,
                    ],
                    "route" => [
                        "origin" => [
                            "name" => $t->route->origin->name,
                        ],
                        "destination" => [
                            "name" => $t->route->destination->name,
                        ],
                    ],
                    "departure_time" => $t->departure_time,
                    "arrival_time" => $arrival->format('H:i'),
                    "base_fare" => $t->base_fare,
                    "seats_available" => $t->default_seats,
                    "duration" => $durationText,
                ];
            })->values()
        ]);
    }
}

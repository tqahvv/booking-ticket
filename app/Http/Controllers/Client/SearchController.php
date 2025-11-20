<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Route as BusRoute;
use App\Models\Schedule;
use App\Models\ScheduleTemplate;
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

        // Format ngày hiển thị
        $formattedDate = null;
        if ($date) {
            $formattedDate = Carbon::parse($date)->isoFormat('dddd, D MMMM YYYY');
            $formattedDate = mb_convert_case($formattedDate, MB_CASE_TITLE, "UTF-8");
        }

        // Không nhập from/to => trả về trang
        if (!$from || !$to) {
            return view('client.pages.booking-home', [
                'schedules' => collect(),
                'from' => $from,
                'to' => $to,
                'date' => $date,
                'seats' => $seats,
            ]);
        }

        // Tìm location
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

        // Lấy danh sách tuyến
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

        // ===============================
        // 🔥 LẤY LỊCH TỪ SCHEDULE TEMPLATE
        // ===============================

        $weekday = Carbon::parse($date)->dayOfWeekIso; // 1=Mon -> 7=Sun

        $templates = ScheduleTemplate::with(['route.origin', 'route.destination', 'operator', 'vehicleType'])
            ->whereHas('route.origin', function ($q) use ($origin) {
                $q->where('city', $origin->city);
            })
            ->whereHas('route.destination', function ($q) use ($destination) {
                $q->where('city', $destination->city);
            })
            ->whereRaw("JSON_CONTAINS(running_days, '[$weekday]')")
            ->where('default_seats', '>=', $seats)
            ->get();

        // Nếu không có template => không có chuyến
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

        // ===============================
        // 🔥 CHUYỂN TEMPLATE → CHUYẾN THỰC TẾ
        // ===============================

        $schedules = $templates->map(function ($t) use ($date) {

            $departure = Carbon::parse($date . ' ' . $t->departure_time);
            $arrival = $departure->copy()->addMinutes($t->travel_duration_minutes);

            return (object)[
                "id" => $t->id,
                "route" => $t->route,
                "operator" => $t->operator,
                "vehicleType" => $t->vehicleType,
                "base_fare" => $t->base_fare,
                "seats_available" => $t->default_seats,
                "departure_datetime" => $departure,
                "arrival_datetime" => $arrival,
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

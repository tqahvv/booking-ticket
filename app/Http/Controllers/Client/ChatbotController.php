<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\ScheduleTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;

class ChatbotController extends Controller
{
    public function fetchMessages(Request $request)
    {
        if (Auth::check()) {
            $msgs = ChatMessage::where('user_id', Auth::id())
                ->orderBy('created_at')
                ->get(['sender', 'message']);
        } else {
            $token = $request->cookie('chatbot_token');
            $msgs = $token
                ? ChatMessage::where('guest_token', $token)->orderBy('created_at')->get(['sender', 'message'])
                : collect();
        }

        return response()->json($msgs);
    }

    public function sendMessages(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $question = strtolower(trim($request->message));

        // ===============================
        // 👤 USER / GUEST
        // ===============================
        $userId = Auth::id();
        $guestToken = $userId ? null : ($request->cookie('chatbot_token') ?? Str::uuid()->toString());

        // ===============================
        // 🧠 INTENT
        // ===============================
        $intent = $this->detectIntent($question);

        ChatMessage::create([
            'user_id' => $userId,
            'guest_token' => $guestToken,
            'sender' => 'user',
            'message' => $request->message,
            'intent' => $intent
        ]);

        // ===============================
        // 🧠 CONTEXT
        // ===============================
        $context = session()->get('chat_context', []);
        $routeId = $context['route_id'] ?? null;
        $contextDate = $context['date'] ?? null;

        // ===============================
        // 📅 XÁC ĐỊNH NGÀY
        // ===============================
        if (str_contains($question, 'ngày mai')) {
            $date = Carbon::tomorrow();
        } elseif (str_contains($question, 'hôm nay')) {
            $date = Carbon::today();
        } elseif (preg_match('/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})/', $question, $m)) {
            $date = Carbon::createFromFormat('d/m/Y', "{$m[1]}/{$m[2]}/{$m[3]}");
        } elseif ($contextDate) {
            $date = Carbon::parse($contextDate);
        } else {
            $date = null;
        }

        if ($date) {
            session()->put('chat_context.date', $date->toDateString());
        }

        // ===============================
        // 🛣️ LẤY ROUTE
        // ===============================
        $templates = ScheduleTemplate::with([
            'route.origin',
            'route.destination',
            'operator',
            'vehicleType'
        ])->get();

        $matched = $templates->filter(function ($tpl) use ($question) {
            if (!$tpl->route || !$tpl->route->origin || !$tpl->route->destination) {
                return false;
            }

            $q = $this->normalize($question);
            $from = $this->normalize($tpl->route->origin->city);
            $to = $this->normalize($tpl->route->destination->city);

            return str_contains($q, $from) && str_contains($q, $to);
        });

        if ($matched->isEmpty() && $routeId) {
            $matched = $templates->where('route_id', $routeId);
        }

        if ($matched->count()) {
            session()->put('chat_context.route_id', $matched->first()->route_id);
        }

        // ===============================
        // ⏰ USER CHỌN GIỜ (VD: chuyến 22 giờ)
        // ===============================
        if (preg_match('/(\d{1,2})\s*(giờ|h)/', $question, $m) && session()->has('chat_context.results')) {

            $hour = str_pad($m[1], 2, '0', STR_PAD_LEFT) . ':00';
            $results = session('chat_context.results');

            $chosen = collect($results)->firstWhere('time', $hour);

            if ($chosen) {

                session()->put('chat_context.selected_trip', $chosen);

                return $this->reply(
                    "✅ **Bạn đã chọn chuyến thành công**\n\n" .
                        "🕒 **{$chosen['time']}**\n" .
                        "🚍 {$chosen['vehicle']}\n" .
                        "💰 {$chosen['fare']}đ\n" .
                        "💺 Còn {$chosen['seats']} chỗ\n\n" .
                        "👉 Bạn muốn đặt **bao nhiêu vé**?",
                    $request,
                    $guestToken
                );
            }

            return $this->reply(
                "⚠️ Mình không tìm thấy chuyến **{$hour}**.\n👉 Bạn vui lòng chọn lại đúng giờ trong danh sách nhé.",
                $request,
                $guestToken
            );
        }

        // ===============================
        // 🚍 LẤY DANH SÁCH CHUYẾN
        // ===============================
        $results = [];

        if ($date && $matched->count()) {
            $weekday = $date->dayOfWeekIso;

            foreach ($matched as $tpl) {

                if (!is_array($tpl->running_days)) continue;
                if (!in_array((string)$weekday, $tpl->running_days)) continue;

                $departure = Carbon::parse($date->toDateString() . ' ' . $tpl->departure_time);

                $schedule = Schedule::firstOrCreate(
                    [
                        'schedule_template_id' => $tpl->id,
                        'departure_datetime' => $departure
                    ],
                    [
                        'route_id' => $tpl->route_id,
                        'operator_id' => $tpl->operator_id,
                        'vehicle_type_id' => $tpl->vehicle_type_id,
                        'arrival_datetime' => (clone $departure)->addMinutes($tpl->travel_duration_minutes),
                        'total_seats' => $tpl->default_seats,
                        'seats_available' => $tpl->default_seats,
                        'base_fare' => $tpl->base_fare,
                        'status' => 'scheduled'
                    ]
                );

                $results[] = [
                    'time' => $departure->format('H:i'),
                    'operator' => $tpl->operator->name,
                    'vehicle' => $tpl->vehicleType->name,
                    'fare' => number_format($tpl->base_fare),
                    'seats' => $schedule->seats_available
                ];
            }
        }

        if ($results) {

            session()->put('chat_context.results', $results);

            $answer = "📅 **Ngày {$date->format('d/m/Y')} – Nhà xe {$results[0]['operator']}**\n";
            $answer .= "━━━━━━━━━━━━━━━━━━\n";

            foreach ($results as $i => $r) {
                $answer .= ($i + 1) . ". 🕒 **{$r['time']}**\n";
                $answer .= "   🚍 {$r['vehicle']}\n";
                $answer .= "   💺 Còn {$r['seats']} chỗ\n";
                $answer .= "   💰 {$r['fare']}đ\n\n";
            }

            $answer .= "👉 Bạn muốn **đặt chuyến mấy / mấy giờ**?";
            return $this->reply($answer, $request, $guestToken);
        }

        if ($matched->count() && !$date) {
            $route = $matched->first()->route;

            return $this->reply(
                "✅ Mình đã tìm thấy tuyến **{$route->origin->city} → {$route->destination->city}**.\n" .
                    "📅 Bạn muốn đi **ngày nào** (ví dụ: hôm nay, ngày mai, 20/12)?",
                $request,
                $guestToken
            );
        }

        return $this->reply(
            "🤔 Bạn vui lòng cho mình biết **điểm đi – điểm đến** và **ngày đi** nhé.\nVí dụ: *Vinh – Hà Nội ngày mai*",
            $request,
            $guestToken
        );
    }

    private function reply($message, Request $request, $guestToken = null)
    {
        $ai = OpenAI::chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'assistant',
                    'content' => $message
                ]
            ]
        ]);

        $res = response()->json([
            'bot' => [
                'sender' => 'bot',
                'message' => $ai->choices[0]->message->content
            ]
        ]);

        if ($guestToken && !$request->cookie('chatbot_token')) {
            $res->cookie('chatbot_token', $guestToken, 60 * 24 * 30);
        }

        return $res;
    }

    private function detectIntent($text)
    {
        if (str_contains($text, 'đặt vé') || str_contains($text, 'mua vé') || str_contains($text, 'hướng dẫn')) {
            return 'booking';
        }

        if (str_contains($text, 'giá')) return 'price';
        if (str_contains($text, 'còn chỗ')) return 'availability';
        if (str_contains($text, 'chuyến')) return 'schedule';

        return 'other';
    }

    private function normalize($text)
    {
        $text = mb_strtolower($text, 'UTF-8');
        return preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);
    }
}

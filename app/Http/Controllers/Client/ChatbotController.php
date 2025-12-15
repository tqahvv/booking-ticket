<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function fetchMessages(Request $request)
    {
        if (Auth::check()) {
            $msgs = ChatMessage::where('user_id', Auth::id())->orderBy('created_at')->get();
        } else {
            $token = $request->cookie('chatbot_token');
            $msgs = $token ? ChatMessage::where('guest_token', $token)->orderBy('created_at')->get() : collect();
        }
        return response()->json($msgs);
    }

    public function sendMessages(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userId = Auth::id();
        $guestToken = null;

        if (!$userId) {
            $guestToken = $request->cookie('chatbot_token');
            if (!$guestToken) {
                $guestToken = 'guest_' . Str::random(32);
                cookie()->queue(cookie('chatbot_token', $guestToken, 60 * 24 * 30));
            }
        }

        $userMsg = ChatMessage::create([
            'user_id' => $userId,
            'guest_token' => $userId ? null : $guestToken,
            'sender' => 'user',
            'message' => $request->message,
        ]);

        $paymentMethods = PaymentMethod::get(['name', 'type', 'description'])->map(function ($method) {
            return "{$method->name} ({$method->type}): {$method->description}";
        })->toArray();
        $paymentMethodsText = implode("\n", $paymentMethods);
        $prompt = "Bạn là trợ lý ảo của hệ thống đặt vé xe khách.
            Khi người dùng nói tên của họ, ví dụ 'Tôi tên là Lập', hãy chào họ theo tên: 'Chào anh Lập ạ. Tôi có thể giúp gì cho bạn hôm nay ạ.'
            Chỉ trả lời các câu hỏi liên quan đến việc đặt vé, lịch trình, và các phương thức thanh toán.
            Không trả lời ngoài phạm vi này.
            Danh sách phương thức thanh toán:\n$paymentMethodsText";

        $history = ChatMessage::query()->where(function ($q) use ($userId, $guestToken) {
            if ($userId) {
                $q->where('user_id', $userId);
            } else {
                $q->where('guest_token', $guestToken);
            }
        })->latest()->limit(6)->orderBy('created_at', 'asc')->get();

        $contents = [];
        foreach ($history as $msg) {
            $contents[] = [
                'role' => $msg->sender === 'user' ? 'user' : 'model',
                'parts' => [["text" => $msg->message]]
            ];
        }

        $contents[] = [
            'role' => 'user',
            'parts' => [["text" => $request->message]]
        ];

        $aiReplyText = "Xin lỗi, hiện tại tôi chưa thể trả lời câu hỏi của bạn.";
        if (env('GEMINI_API_KEY')) {
            try {
                $url_apikey = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';
                $payload = [
                    "systemInstruction" => [
                        "parts" => [
                            ["text" => $prompt]
                        ]
                    ],
                    "contents" => $contents
                ];

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Goog-Api-Key' => env('GEMINI_API_KEY'),
                ])->post($url_apikey, $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    $aiReplyText = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Xin lỗi, tôi chưa hiểu câu hỏi của bạn.";
                } else {
                    $aiReplyText = "Xin lỗi, hiện tại tôi không thể kết nối đến dịch vụ AI.";
                    Log::error('Chatbot API error', ['response' => $response->json()]);
                }
            } catch (\Throwable $e) {
                Log::error('Chatbot AI error: ' . $e->getMessage());
                $aiReplyText = "Xin lỗi, đã có lỗi xảy ra khi xử lý yêu cầu của bạn.";
            }
        }

        $botMsg = ChatMessage::create([
            'user_id' => $userId,
            'guest_token' => $userId ? null : $guestToken,
            'sender' => 'bot',
            'message' => $aiReplyText,
        ]);

        return response()->json([
            'user' => $userMsg,
            'bot' => $botMsg,
        ]);
    }
}

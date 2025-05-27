<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChatbotService;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    public function chat(Request $request)
    {
        $userInput = $request->input('message');
        Log::info('User Input: ' . $userInput);

        $reply = $this->chatbotService->findMatchingTour($userInput);

        if ($reply) {
            // Nếu findMatchingTour trả về một đối tượng tour, định dạng nó
            if (is_object($reply) && isset($reply->name_tour)) {
                return response()->json(['reply' => $this->chatbotService->formatTourReply($reply)]);
            }
            // Nếu findMatchingTour trả về một mảng (ví dụ: yêu cầu callback hoặc all tours)
            return response()->json(['reply' => $reply]);
        } else {
            // Trường hợp không tìm thấy tour nào phù hợp với yêu cầu cụ thể
            return response()->json([
                'reply' => [
                    'text' => "❓ Xin lỗi, tôi không tìm thấy tour hoặc thông tin phù hợp với yêu cầu của bạn. Bạn có muốn thử lại với các gợi ý sau không?",
                    'quick_replies' => [
                        ['text' => 'Tour Đà Nẵng', 'value' => 'tour Đà Nẵng'],
                        ['text' => 'Tất cả tour hiện có', 'value' => 'tất cả tour hiện có'],
                        ['text' => 'Liên hệ tư vấn', 'value' => 'tôi muốn nói chuyện với nhân viên'],
                    ]
                ]
            ]);
        }
    }
}
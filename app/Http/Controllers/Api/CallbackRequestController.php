<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CallbackRequest; // Đảm bảo đã import Model của bạn
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CallbackRequestController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Received callback request:', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20', // Có thể thêm regex cho định dạng SĐT
        ]);

        if ($validator->fails()) {
            Log::error('Callback request validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            $callbackRequest = CallbackRequest::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            Log::info('Callback request saved successfully:', ['id' => $callbackRequest->id]);

            return response()->json([
                'success' => true,
                'message' => 'Yêu cầu gọi lại của bạn đã được gửi thành công!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving callback request:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lưu yêu cầu của bạn.'
            ], 500); // 500 Internal Server Error
        }
    }
}
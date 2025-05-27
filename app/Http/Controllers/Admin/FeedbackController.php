<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvailableTour;
use App\Models\AvailableTourBooking;
use App\Models\CustomTourBookings;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    // Kiểm tra mã đặt tour (dùng cho AJAX từ giao diện người dùng)
    public function checkBookingCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid booking code.'], 400);
        }

        $code = $request->booking_code;
        $booking = null;
        $tour = null;

        // Tìm trong AvailableTourBooking
        $booking = AvailableTourBooking::where('booking_code', $code)->first();
        if ($booking) {
            $tour = AvailableTour::find($booking->tour_id);
        }

        // Nếu không tìm thấy, tìm trong CustomTourBookings
        if (!$booking) {
            $booking = CustomTourBookings::where('tracking_code', $code)->first();
            if ($booking) {
                // Giả sử CustomTourBookings có cột status hoặc quan hệ custom_tour
                $tour = (object) ['status' => $booking->status ?? 'completed']; // Điều chỉnh nếu có custom_tour
            }
        }

        if (!$booking || !$tour) {
            return response()->json(['success' => false, 'message' => 'Booking or tour not found.'], 404);
        }

        return response()->json(['success' => true, 'status' => $tour->status]);
    }

    // Lưu đánh giá tour từ người dùng
    public function submitTourFeedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_code' => 'required|string',
            'rating' => 'required|integer|between:1,5',
            'tour_message' => 'required|string|max:1000',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $code = $request->booking_code;
        $booking = null;
        $tour = null;
        $isCustomBooking = false;

        // Tìm booking trong AvailableTourBooking
        $booking = AvailableTourBooking::where('booking_code', $code)->first();
        if ($booking) {
            $tour = AvailableTour::find($booking->tour_id);
        }

        // Nếu không tìm thấy, tìm trong CustomTourBookings
        if (!$booking) {
            $booking = CustomTourBookings::where('tracking_code', $code)->first();
            if ($booking) {
                $tour = (object) ['status' => $booking->status ?? 'completed']; // Điều chỉnh nếu có custom_tour
                $isCustomBooking = true;
            }
        }

        if (!$booking || !$tour) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy booking hoặc tour.'
            ], 404);
        }

        // Kiểm tra trạng thái tour
        if ($tour->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Feedback chỉ có thể gửi cho tour đã hoàn thành.'
            ], 403);
        }

        // Kiểm tra feedback đã tồn tại
        $existingFeedback = Feedback::where('booking_code', $code)
            ->orWhere('tracking_code', $code)
            ->exists();
        if ($existingFeedback) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback cho booking này đã được gửi.'
            ], 409);
        }

        Feedback::create([
            'name' => $booking->name ?? ($booking->customer->name ?? 'N/A'),
            'email' => $request->email,
            'message' => $request->tour_message,
            'booking_code' => $isCustomBooking ? null : $code,
            'tracking_code' => $isCustomBooking ? $code : null,
            'tour_id' => $booking->tour_id ?? $booking->id,
            'rating' => $request->rating,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cảm ơn bạn đã gửi feedback!'
        ], 200);
    }

    // Hiển thị danh sách feedback cho admin
    public function index()
    {
        $feedbacks = Feedback::with('tour')->orderBy('created_at', 'desc')->get();
        return view('management.tours.feedback_man.index', compact('feedbacks'));
    }

    // Hiển thị form để phản hồi feedback
    public function reply($id)
    {
        $feedback = Feedback::with('tour')->findOrFail($id);
        return view('management.tours.feedback_man.reply', compact('feedback'));
    }

    // Lưu phản hồi của admin
    public function storeReply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'admin_reply' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $feedback = Feedback::findOrFail($id);
        $feedback->admin_reply = $request->admin_reply;
        $feedback->replied_at = now();
        $feedback->save();

        return redirect()->route('admin.feedbacks.index')->with('success', 'Reply sent successfully!');
    }
}
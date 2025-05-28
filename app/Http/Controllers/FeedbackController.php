<?php

namespace App\Http\Controllers;

use App\Models\AvailableTour;
use App\Models\AvailableTourBooking;
use App\Models\CustomTourBookings;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    public function showFeedbackForm()
    {
        return view('contact');
    }

    public function submitFeedback(Request $request)
    {
        Log::info('Feedback submission started', ['request' => $request->all()]);

        $request->validate([
            'code' => 'required|string',
            'name' => 'required_if:action,submit|string|max:255',
            'message' => 'required_if:action,submit|string',
            'rating' => 'required_if:action,submit|integer|min:1|max:5',
            'action' => 'required|in:submit,view',
        ]);

        $code = $request->input('code');
        $action = $request->input('action');
        Log::info('Processing feedback', ['code' => $code, 'action' => $action]);

        // Tìm booking_code hoặc tracking_code
        $booking = AvailableTourBooking::where('booking_code', $code)->first();
        $customBooking = CustomTourBookings::where('tracking_code', $code)->first();

        if (!$booking && !$customBooking) {
            Log::error('Invalid code: ' . $code);
            return back()->withErrors(['code' => 'Mã đặt tour không hợp lệ.']);
        }

        $tourId = null;
        $isEligible = false;
        $tourType = null;

        if ($booking) {
            $tourType = 'available';
            $tour = AvailableTour::find($booking->tour_id);
            if (!$tour) {
                Log::error('Tour not found for booking: ', ['tour_id' => $booking->tour_id]);
                return back()->withErrors(['code' => 'Tour không tồn tại trong hệ thống.']);
            }
            $tourId = $booking->tour_id;
            $isEligible = $tour->status === AvailableTour::STATUS_COMPLETED;
            Log::info('AvailableTour status: ', ['tour' => $tour, 'isEligible' => $isEligible]);
        } elseif ($customBooking) {
            $tourType = 'custom';
            $tourId = null; // Không cần tour_id cho custom tour
            $isEligible = $customBooking->status === 'approved';
            Log::info('CustomTour status: ', ['customBooking' => $customBooking, 'isEligible' => $isEligible]);
        }

        if (!$isEligible) {
            Log::error('Tour not eligible: ', ['code' => $code]);
            return back()->withErrors(['code' => 'Tour chưa hoàn thành hoặc chưa được phê duyệt.']);
        }

        // Kiểm tra xem đã có phản hồi chưa
        $existingFeedback = Feedback::where(function ($query) use ($code) {
            $query->where('booking_code', $code)->orWhere('tracking_code', $code);
        })->first();

        if ($action === 'submit') {
            if ($existingFeedback) {
                Log::warning('Feedback already exists for code: ' . $code);
                return back()->withErrors(['code' => 'Bạn đã gửi phản hồi cho mã này rồi.']);
            }

            try {
                Log::info('Creating feedback with data: ', [
                    'name' => $request->input('name'),
                    'message' => $request->input('message'),
                    'booking_code' => $booking ? $code : null,
                    'tracking_code' => $customBooking ? $code : null,
                    'tour_id' => $tourId,
                    'rating' => $request->input('rating'),
                    'tour_type' => $tourType,
                ]);

                Feedback::create([
                    'name' => $request->input('name'),
                    'message' => $request->input('message'),
                    'booking_code' => $booking ? $code : null,
                    'tracking_code' => $customBooking ? $code : null,
                    'tour_id' => $tourId,
                    'rating' => $request->input('rating'),
                ]);

                Log::info('Feedback created successfully for code: ' . $code);
                return back()->with('success', 'Phản hồi đã được gửi thành công!');
            } catch (\Exception $e) {
                Log::error('Failed to create feedback: ' . $e->getMessage(), ['code' => $code]);
                return back()->withErrors(['code' => 'Có lỗi xảy ra khi lưu phản hồi. Vui lòng thử lại.']);
            }
        } elseif ($action === 'view') {
            Log::info('Viewing feedback for code: ' . $code);
            return view('contact', [
                'feedback' => $existingFeedback,
                'canSubmit' => !$existingFeedback,
                'tourType' => $tourType,
            ]);
        }
    }
}
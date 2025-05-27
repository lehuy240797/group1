<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvailableTourBooking;
use App\Models\CustomTourBookings;
use App\Models\Feedback;

class MyBookingController extends Controller
{
    public function showSearchForm()
    {
        return view('my-bookings.search');
    }

    public function search(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = $request->input('code');

        // Tìm trong bảng available_tour_bookings
        $availableTourBookings = AvailableTourBooking::where('booking_code', $code)->first();
        if ($availableTourBookings) {
            // Tìm feedback với booking_code
            $feedback = Feedback::where('booking_code', $code)->first();
            return view('my-bookings.result', [
                'availableTourBookings' => $availableTourBookings,
                'type' => 'booking',
                'feedback' => $feedback,
            ]);
        }

        // Tìm trong bảng custom_tour_bookings
        $customTourBookings = CustomTourBookings::where('tracking_code', $code)->first();
        if ($customTourBookings) {
            // Tìm feedback với tracking_code
            $feedback = Feedback::where('tracking_code', $code)->first();
            return view('my-bookings.result', [
                'customTourBookings' => $customTourBookings,
                'type' => 'custom',
                'feedback' => $feedback,
            ]);
        }

        return redirect()->back()->with('error', 'Không tìm thấy mã đặt tour.');
    }
}
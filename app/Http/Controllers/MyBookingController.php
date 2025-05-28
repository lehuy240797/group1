<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvailableTourBooking;
use App\Models\CustomTourBookings;

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
            return view('my-bookings.result', [
                'availableTourBookings' => $availableTourBookings,
                'type' => 'booking',
            ]);
        }

        // Tìm trong bảng custom_tour_bookings
        $customTourBookings = CustomTourBookings::where('tracking_code', $code)->first();
        if ($customTourBookings) {
            return view('my-bookings.result', [
                'customTourBookings' => $customTourBookings,
                'type' => 'custom',
            ]);
        }

        return redirect()->back()->with('error', 'Không tìm thấy mã đặt tour.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvailableTour;
use App\Models\Customer;
use App\Models\AvailableTourBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AvailableTourBookingsController extends Controller
{
    public function create(AvailableTour $availableTour)
    {
        return view('booking.create', compact('availableTour'));
    }

    public function processPayment(Request $request, AvailableTour $availableTour)
    {
        // Kiểm tra xem tour có tồn tại không
        if (!$availableTour) {
            return redirect()->route('home')->withErrors(['error' => 'Tour không tồn tại.']);
        }

        // Kiểm tra xem tour đã hết chỗ chưa
        if ($availableTour->isFullyBooked()) {
            return back()->withErrors(['error' => 'Tour đã hết chỗ.']);
        }

        // Validation
        $request->validate([
            'num_adults' => 'required|integer|min:0',
            'num_children' => 'required|integer|min:0',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15|regex:/^[0-9]{10,15}$/',
        ]);

        $numAdults = $request->input('num_adults', 0);
        $numChildren = $request->input('num_children', 0);
        $totalGuests = $numAdults + $numChildren;

        if ($totalGuests < 1) {
            return back()->withErrors(['error' => 'Vui lòng chọn ít nhất 1 khách.']);
        }

        $bookedGuests = $availableTour->availableTourBookings()->sum('num_guests');

        // Kiểm tra số lượng khách tối đa
        if ($availableTour->max_guests !== null && ($bookedGuests + $totalGuests > $availableTour->max_guests)) {
            return back()->withErrors(['num_guests' => 'Số lượng khách vượt quá giới hạn còn lại.']);
        }

        // Tạo hoặc lấy khách hàng
        $customer = Customer::firstOrCreate(
            ['email' => $request->input('email')],
            ['name' => $request->input('name'), 'phone' => $request->input('phone')]
        );

        // Tính tổng giá
        $adultPrice = $availableTour->price ?? 0;
        $childPrice = $adultPrice / 2; // Giá trẻ em bằng nửa giá người lớn
        $totalPrice = ($numAdults * $adultPrice) + ($numChildren * $childPrice);

        // Lưu thông tin booking vào session
        $bookingData = [
            'tour_id' => $availableTour->id,
            'num_adults' => $numAdults,
            'num_children' => $numChildren,
            'total_guests' => $totalGuests,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'total_price' => $totalPrice,
            'type' => 'available',
            'customer_id' => $customer->id,
        ];
        session(['booking_data' => $bookingData]);

        // Chuyển hướng đến form chọn hình thức thanh toán
        return redirect()->route('payment.form');
    }
}
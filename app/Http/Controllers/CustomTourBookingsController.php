<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomTour;
use App\Models\CustomTourBookings;
use App\Models\Customer;

class CustomTourBookingsController extends Controller
{
    public function processCustomPayment(Request $request, CustomTour $tour)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15|regex:/^[0-9]{10,15}$/',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'num_guests' => 'required|integer|min:1',
        ]);

        // 👉 Tạo hoặc lấy khách hàng
        $customer = Customer::firstOrCreate(
            ['email' => $request->input('email')],
            ['name' => $request->input('name'), 'phone' => $request->input('phone')]
        );

        // 👉 Gán customer_id cho tour nếu muốn liên kết
        $tour->customer_id = $customer->id;
        $tour->save();

        // **Lưu booking vào database**
        CustomTourBookings::create([
            'tour_id' => $tour->id,
            'customer_id' => $customer->id,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'total_price' => $tour->budget,
            'type' => 'custom',
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'num_guests' => $request->input('num_guests'),
            'payment_date' => now(),
            'flight_price' => $tour->flight_price, // ✅ Thêm giá vé máy bay
            'adult_tickets' => $tour->adult_tickets, // ✅ Thêm số vé người lớn
            'child_tickets' => $tour->child_tickets,

        ]);

        // 👉 Lưu thông tin booking vào session
        $bookingData = [
            'tour_id' => $tour->id,
            'customer_id' => $customer->id,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'total_price' => $tour->budget,
            'type' => 'custom',
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'num_guests' => $request->input('num_guests'),
            'flight_price' => $tour->flight_price, // ✅ Thêm giá vé máy bay vào session
            'adult_tickets' => $tour->adult_tickets, // ✅ Thêm số vé người lớn vào session
            'child_tickets' => $tour->child_tickets,
        ];
        session(['booking_data' => $bookingData]);
        if (!$tour->flight_price) {
            return redirect()->back()->with('error', 'Giá vé máy bay không hợp lệ.');
        }
        if ($tour->adult_tickets <= 0) {
            return redirect()->back()->with('error', 'Số lượng vé người lớn phải lớn hơn 0.');
        }
        

        // Chuyển hướng đến form chọn hình thức thanh toán
        return redirect()->route('payment.form');
    }

    public function showSearchForm()
    {
        return view('my-bookings.custom-search');
    }

    public function searchBooking(Request $request)
    {
        $request->validate([
            'tracking_code' => 'required|string|max:255',
        ]);

        $trackingCode = trim($request->input('tracking_code'));
        $customTourBookings = CustomTourBookings::where('tracking_code', $trackingCode)
            ->with(['customer', 'tour'])
            ->first();


        if ($customTourBookings) {
            return view('my-bookings.custom-result', compact('customTourBookings'));
        }

        return back()->with('error', 'Không tìm thấy thông tin đặt tour với mã này.');
    }
}

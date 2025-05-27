<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvailableTour;
use App\Models\AvailableTourBooking;
use App\Models\CustomTourBookings;
use App\Models\CustomTour;
use App\Models\Customer;
use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Cache;
use App\Events\BookingPaid;

class PaymentController extends Controller
{
    public function showPaymentForm(Request $request)
    {
        $bookingData = session('booking_data');
        if (!$bookingData) {
            return redirect()->route('home')->with('error', 'Không có dữ liệu đặt tour.');
        }

        return view('payment.form', compact('bookingData'));
    }

    public function processPayment(Request $request)
    {
        $bookingData = session('booking_data');
        if (!$bookingData) {
            return redirect()->route('home')->with('error', 'Phiên làm việc hết hạn.');
        }

        $request->validate([
            'payment_method' => 'required|in:card,qr',
        ]);

        return $request->payment_method === 'card'
            ? $this->showCardPaymentForm($bookingData)
            : $this->showQRPaymentForm($bookingData);
    }

    private function showCardPaymentForm($bookingData)
    {
        return view('payment.card', compact('bookingData'));
    }

    public function showQRPaymentForm($bookingData)
    {
        $token = uniqid();

        Cache::put("qr_tokens.$token", $bookingData, now()->addMinutes(15));

        $host = getHostByName(getHostName());
        $port = 8000;
        $qrUrl = "http://$host:$port/payment/qr/$token";

        $qrCode = new QrCode($qrUrl);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrImage = 'data:' . $result->getMimeType() . ';base64,' . base64_encode($result->getString());

        return view('payment.qr', compact('qrImage', 'token'));
    }

    public function handleQRRedirect($token)
    {
        $data = Cache::get("qr_tokens.$token");

        if (!$data) {
            return redirect()->route('home')->with('error', 'QR không hợp lệ hoặc đã hết hạn.');
        }

        return view('payment.qr-confirm', compact('data', 'token'));
    }

    public function confirmQR(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        $data = Cache::get("qr_tokens." . $request->token);

        if (!$data) {
            return response()->json(['error' => 'QR đã hết hạn.'], 400);
        }

        $availableTourBookings = null;
        $customTourBookings = null;

        try {
            if ($data['type'] === 'available') {
                $code = $this->generateUniqueBookingCode();
                $availableTourBookings = AvailableTourBooking::create([
                    'tour_id' => $data['tour_id'],
                    'num_adults' => $data['num_adults'],
                    'num_children' => $data['num_children'],
                    'num_guests' => $data['total_guests'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'total_price' => $data['total_price'],
                    'payment_status' => 'Thành Công',
                    'booking_code' => $code,
                    'customer_id' => $data['customer_id'] ?? null,
                ]);

                $availableTour = AvailableTour::find($data['tour_id']);
                if ($availableTour) {
                    $availableTour->booked_seats = ($availableTour->booked_seats ?? 0) + $data['total_guests'];
                    $availableTour->save();
                }

                Cache::put("qr_paid_status." . $request->token, true, now()->addMinutes(10));
            } else {
                $customer = Customer::firstOrCreate(
                    ['email' => $data['email']],
                    ['name' => $data['name'], 'phone' => $data['phone']]
                );

                $customTour = CustomTour::findOrFail($data['tour_id']);
                $customTour->customer_id = $customer->id;
                $customTour->save();

                $customTourBookings = CustomTourBookings::create([
                    'tracking_code' => $this->generateUniqueTrackingCode(),
                    'user_id' => auth()->id() ?? null,
                    'tour_id' => $data['tour_id'],
                    'type' => 'custom',
                    'total_price' => $data['total_price'],
                    'payment_date' => now(),
                    'status' => 'pending',
                    'customer_id' => $customer->id,
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'name' => $data['name'],
                    'hotel' => $data['hotel'] ?? null,
                    'places' => $data['places'] ?? null,
                ]);

                Cache::put("qr_paid_status." . $request->token, true, now()->addMinutes(10));
                Cache::put("qr_tracking_code." . $request->token, $customTourBookings->tracking_code, now()->addMinutes(10));
            }

            if (!$availableTourBookings && !$customTourBookings) {
                return redirect()->route('home')->with('error', 'Lỗi khi tạo booking.');
            }

            event(new BookingPaid($availableTourBookings->booking_code ?? $customTourBookings->tracking_code, $request->token));

            return view('payment.success', compact('availableTourBookings', 'customTourBookings'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi khi xác nhận thanh toán.'], 500);
        }
    }

    public function confirmCardPayment(Request $request)
    {
        $bookingData = session('booking_data');
        if (!$bookingData) {
            return redirect()->route('home')->with('error', 'Dữ liệu giao dịch không hợp lệ.');
        }

        $request->validate([
            'card_number' => 'required|string|size:16',
            'expiry_date' => 'required|string|size:5|regex:/^[0-1][0-9]\/[0-9]{2}$/',
            'cvv' => 'required|string|size:3',
        ]);

        return $this->storeBooking($bookingData);
    }

    private function storeBooking($bookingData)
    {
        $availableTourBookings = null;
        $customTourBookings = null;

        try {
            if ($bookingData['type'] === 'available') {
                $customer = Customer::firstOrCreate(
                    ['email' => $bookingData['email']],
                    ['name' => $bookingData['name'], 'phone' => $bookingData['phone']]
                );

                $availableTourBookings = AvailableTourBooking::create([
                    'tour_id' => $bookingData['tour_id'],
                    'num_adults' => $bookingData['num_adults'],
                    'num_children' => $bookingData['num_children'],
                    'num_guests' => $bookingData['total_guests'], // Tổng số khách
                    'name' => $bookingData['name'],
                    'email' => $bookingData['email'],
                    'phone' => $bookingData['phone'],
                    'total_price' => $bookingData['total_price'],
                    'booking_code' => $this->generateUniqueBookingCode(),
                    'payment_status' => 'Thành Công',
                    'customer_id' => $customer->id,
                ]);

                $availableTour = AvailableTour::find($bookingData['tour_id']);
                if ($availableTour) {
                    $availableTour->booked_seats = ($availableTour->booked_seats ?? 0) + $bookingData['total_guests'];
                    $availableTour->save();
                }
            } else {
                $customer = Customer::firstOrCreate(
                    ['email' => $bookingData['email']],
                    ['name' => $bookingData['name'], 'phone' => $bookingData['phone']]
                );

                $customTour = CustomTour::findOrFail($bookingData['tour_id']);
                $customTour->customer_id = $customer->id;
                $customTour->save();

                $customTourBookings = CustomTourBookings::create([
                    'tracking_code' => $this->generateUniqueTrackingCode(),
                    'user_id' => auth()->id() ?? null,
                    'tour_id' => $bookingData['tour_id'],
                    'type' => 'custom',
                    'total_price' => $bookingData['total_price'],
                    'payment_date' => now(),
                    'status' => 'pending',
                    'customer_id' => $customer->id,
                    'email' => $bookingData['email'],
                    'phone' => $bookingData['phone'],
                    'name' => $bookingData['name'],
                    'hotel' => $bookingData['hotel'] ?? null,
                    'places' => $bookingData['places'] ?? null,
                ]);
            }

            if (!$availableTourBookings && !$customTourBookings) {
                return redirect()->route('home')->with('error', 'Lỗi khi tạo booking.');
            }

            session()->forget('booking_data');

            return view('payment.success', compact('availableTourBookings', 'customTourBookings'));
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Đã xảy ra lỗi khi xử lý đặt tour.');
        }
    }

    public function checkPaymentStatus($token)
    {
        $paid = Cache::get("qr_paid_status.$token", false);
        $exists = Cache::has("qr_tokens.$token");

        if (!$exists) {
            return response()->json([
                'paid' => false,
                'expired' => true,
                'redirect_url' => null,
            ]);
        }
        $trackingCode = Cache::get("qr_tracking_code.$token");

        return response()->json([
            'paid' => $paid,
            'expired' => false,
            'redirect_url' => $paid ? route('payment.success.token', ['token' => $token]) : null,
            'tracking_code' => $trackingCode,
        ]);
    }

    public function showSuccessWithToken($token)
    {
        $data = Cache::get("qr_tokens.$token");

        if (!$data) {
            return redirect()->route('home')->with('error', 'Thông tin thanh toán không còn hợp lệ.');
        }

        $availableTourBookings = null;
        $customTourBookings = null;

        if ($data['type'] === 'available') {
            $availableTourBookings = AvailableTourBooking::where('email', $data['email'])
                ->where('phone', $data['phone'])
                ->orderByDesc('created_at')
                ->first();
        } else {
            $trackingCode = Cache::get("qr_tracking_code.$token");
            $customTourBookings = CustomTourBookings::where('tracking_code', $trackingCode)->first();
        }

        if (!$availableTourBookings && !$customTourBookings) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin booking.');
        }

        return view('payment.success', compact('availableTourBookings', 'customTourBookings'));
    }

    private function generateUniqueBookingCode()
    {
        $today = Carbon::now()->format('ymd');
        $count = AvailableTourBooking::whereDate('created_at', Carbon::today())->count() + 1;
        return 'AVA' . $today . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    private function generateUniqueTrackingCode()
    {
        $today = Carbon::now()->format('ymd');
        $count = CustomTourBookings::whereDate('created_at', Carbon::today())->count() + 1;
        return 'CUS' . $today . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomTour;
use App\Models\CustomTourBookings;
use Illuminate\Support\Facades\Log;


class CusTourManController extends Controller
{
    public function index()
    {
        $tours = CustomTour::with('customTourBooking')->get();
        return view('management.tours.cust_tour_man.index', compact('tours'));
    }

    public function store(Request $request)
{
    Log::info('Request data:', $request->all());

    try {
        $validated = $request->validate([
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'flight_price' => 'required|numeric|min:0',
            'hotel' => 'required|string|max:255',
            'places' => 'nullable|array',
            'places.*' => 'string',
            'adult_tickets' => 'required|integer|min:1',
            'child_tickets' => 'nullable|integer|min:0',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|regex:/^[0-9]{10,15}$/',
            'email' => 'required|email|max:255',
            'type' => 'required|in:custom',
            'price_data' => 'required|json',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Lỗi validate:', $e->errors());
        return back()->withErrors($e->validator)->withInput();
    }

    $priceData = json_decode($validated['price_data'], true);
    if (!$priceData) {
        Log::error('Không thể giải mã priceData:', ['priceData' => $validated['priceData']]);
        return back()->with('error', 'Dữ liệu giá không hợp lệ.');
    }
    $destination = $validated['destination'];
    $startDate = new \DateTime($validated['start_date']);
    $endDate = new \DateTime($validated['end_date']);
    $days = ceil(($endDate->getTimestamp() - $startDate->getTimestamp()) / (60 * 60 * 24));

    // Tính baseCost
    $baseCost = $priceData[$destination]['Giá chung'] ?? 0;

    if (!empty($validated['places'])) {
        foreach ($validated['places'] as $place) {
            $baseCost += $priceData[$destination]['Địa điểm'][$place] ?? 0;
        }
    }

    // Tăng 10% mỗi ngày sau ngày đầu
    if ($days > 1) {
        $baseCost += $baseCost * 0.1 * ($days - 1);
    }

    $adultTickets = $validated['adult_tickets'];
    $childTickets = $validated['child_tickets'] ?? 0;

    $ticketCost = ($adultTickets * $baseCost) + ($childTickets * $baseCost * 0.5);

    // Tính số phòng khách sạn
    $numRooms = $childTickets > 0 ? ceil($adultTickets) : ceil($adultTickets / 2);
    $hotelPrice = $priceData[$destination]['Khách sạn'][$validated['hotel']] ?? 0;
    $hotelCost = $numRooms * $hotelPrice * $days;

    $flightCost = ($adultTickets + $childTickets) * $validated['flight_price'];

    $total = $ticketCost + $hotelCost + $flightCost;

    if ($this->isAnyHoliday($validated['start_date'], $validated['end_date'])) {
        $total *= 1.5;
    }
        

    try {
        // Lưu vào bảng custom_tours
        $tour = CustomTour::create([
            'destination' => $validated['destination'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'budget' => $total,
            'flight_price' => $validated['flight_price'],
            'adult_tickets' => $adultTickets,
            'child_tickets' => $childTickets,
            'hotel' => $validated['hotel'],
            'places' => json_encode($validated['places'] ?? []),

            'status' => 'pending',
        ]);

        // Lưu dữ liệu vào session
        $bookingData = [
            'tour_id' => $tour->id,
            'num_guests' => $adultTickets + $childTickets,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'total_price' => $total,
            'flight_price' => $validated['flight_price'],
            'adult_tickets' => $adultTickets,
            'child_tickets' => $childTickets,
            'type' => 'custom',
            'hotel' => $validated['hotel'],
            'places' => json_encode($validated['places'] ?? []),
            'status' => 'pending',
        ];

        session(['booking_data' => $bookingData]);

        Log::info('Dữ liệu lưu vào session:', session('booking_data'));

        return redirect()->route('payment.form')->with('success', 'Tour đã được tạo. Vui lòng chọn hình thức thanh toán.');
    } catch (\Exception $e) {
        Log::error('Lỗi khi lưu tour:', ['error' => $e->getMessage()]);
        return back()->with('error', 'Đã xảy ra lỗi khi tạo tour. Vui lòng thử lại.');
    }
}

// Bạn cần implement hàm kiểm tra ngày lễ, tương tự JS:
    private function isAnyHoliday($startDate, $endDate)
{
    // Danh sách ngày lễ cố định theo định dạng Y-m-d
    $holidays = [
        '2025-01-01', // Tết Dương lịch
        '2025-04-30', // Giải phóng miền Nam
        '2025-05-01', // Quốc tế Lao động
        '2025-09-02', // Quốc Khánh
        // Tết Âm lịch 2026 (29/12/2025 âm lịch đến 7/1/2026 âm lịch)
            '2026-02-17', '2026-02-18', '2026-02-19', '2026-02-20',
            '2026-02-21', '2026-02-22', '2026-02-23',
            // Giỗ tổ Hùng Vương 2026 (10/3/2026 âm lịch)
            '2026-04-26'
    ];

    $start = new \DateTime($startDate);
    $end = new \DateTime($endDate);

    // Duyệt từng ngày trong khoảng từ start -> end
    while ($start <= $end) {
        $dateStr = $start->format('Y-m-d');
        if (in_array($dateStr, $holidays)) {
            Log::info('Holiday detected:', ['date' => $dateStr]);
            return true; // Có ít nhất 1 ngày lễ
        }
            $start->modify('+1 day');
        }
        Log::info('No holiday detected in range:', [
        'start_date' => $startDate,
        'end_date' => $endDate
    ]);
        return false;
    }

    public function edit($id)
    {
        $tour = CustomTour::findOrFail($id);
        return view('management.tours.cust_tour_man.edit', compact('tour'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'flight_price' => 'required|numeric|min:0',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'total_price' => 'required|numeric|min:0',
            'hotel' => 'required|string|max:255',
            'places' => 'nullable|array',
            'places.*' => 'string',
        ]);

        $tour = CustomTour::findOrFail($id);

        $tour->update([
            'destination' => $validated['destination'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'budget' => $validated['total_price'],
            'flight_price' => $validated['flight_price'],
            'adult_tickets' => $request->input('adult_tickets', 0),
            'child_tickets' => $request->input('child_tickets', 0),
            'hotel' => $validated['hotel'],
            'total_price' => $validated['total_price'],
        ]);

        if ($tour->customTourBooking) {
            $tour->customTourBooking->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'places' => !empty($validated['places']) ? implode(',', $validated['places']) : null,
                'total_price' => $validated['total_price'],
                'flight_price' => $validated['flight_price'],
                'adult_tickets' => $request->input('adult_tickets', 0),
                'child_tickets' => $request->input('child_tickets', 0),
            ]);
        }

        return redirect()->route('admin.cust_tour_man.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $tour = CustomTour::findOrFail($id);

        CustomTourBookings::where('tour_id', $id)->delete();
        $tour->delete();

        return redirect()->route('admin.cust_tour_man.index')->with('success', 'Đã xóa tour thành công!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $customTourBookings = CustomTourBookings::where('tour_id', $id)->first();
        if (!$customTourBookings) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng cần cập nhật.');
        }

        $customTourBookings->status = $request->status;
        $customTourBookings->save();

        return redirect()->route('admin.cust_tour_man.index')->with('success', 'Cập nhật trạng thái thành công!');
    }
    
}
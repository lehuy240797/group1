<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomTour;
use App\Models\CustomTourBookings;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\AvailableTour;


class CusTourManController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = CustomTour::with(['customTourBooking', 'tourGuide', 'driver']);

        if ($request->filled('search')) {
            $query->where('destination', 'like', '%' . $search . '%')
                  ->orWhereHas('customTourBooking', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
        }

        $tours = $query->get()->map(function ($tour) {
            $availableTourGuides = User::where('role', 'tourguide')
                ->whereDoesntHave('customToursAsTourGuide', function ($q) use ($tour) {
                    $q->where('start_date', '<=', $tour->end_date)
                      ->where('end_date', '>=', $tour->start_date);
                })
                ->whereDoesntHave('toursAsTourGuide', function ($q) use ($tour) {
                $q->where('start_date', '<=', $tour->end_date)
                  ->where('end_date', '>=', $tour->start_date);
            })
            ->get();

            $availableDrivers = User::where('role', 'driver')
                ->whereDoesntHave('customToursAsDriver', function ($q) use ($tour) {
                    $q->where('start_date', '<=', $tour->end_date)
                      ->where('end_date', '>=', $tour->start_date);
                })
                ->whereDoesntHave('toursAsDriver', function ($q) use ($tour) {
                $q->where('start_date', '<=', $tour->end_date)
                  ->where('end_date', '>=', $tour->start_date);
            })
            ->get();

            return (object)[
                'id' => $tour->id,
                'name' => optional($tour->customTourBooking)->name,
                'email' => optional($tour->customTourBooking)->email,
                'phone' => optional($tour->customTourBooking)->phone,
                'destination' => $tour->destination,
                'start_date' => $tour->start_date,
                'end_date' => $tour->end_date,
                'flight_price' => $tour->flight_price,
                'budget' => $tour->budget,
                'tracking_code' => optional($tour->customTourBooking)->tracking_code,
                'tourguide_id' => $tour->tourguide_id,
                'driver_id' => $tour->driver_id,
                'tour_guide_name' => optional($tour->tourGuide)->name,
                'driver_name' => optional($tour->driver)->name,
                'status' => optional($tour->customTourBooking)->status ?? 'pending',
                'availableTourGuides' => $availableTourGuides,
                'availableDrivers' => $availableDrivers,
            ];
        });

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

     public function show($id)
    {
        $tour = CustomTour::with(['customTourBooking', 'tourGuide', 'driver'])->findOrFail($id);
        $places = json_decode($tour->places, true) ?? [];

        return view('management.tours.cust_tour_man.show', compact('tour', 'places'));
    }

    public function destroy($id)
    {
        $tour = CustomTour::findOrFail($id);
        $tour->delete();
        return redirect()->route('admin.cust_tour_man.index')->with('success', 'Tour đã được xóa thành công!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $tour = CustomTour::findOrFail($id);
        $tour->customTourBooking->update(['status' => $request->status]);

        return redirect()->route('admin.cust_tour_man.index')->with('success', 'Trạng thái tour đã được cập nhật!');
    }

    public function assignTourGuide(Request $request, $id)
    {
        $request->validate([
            'tourguide_id' => 'nullable|exists:users,id',
        ]);

        $tour = CustomTour::findOrFail($id);

        // Kiểm tra xung đột thời gian
        if ($request->tourguide_id) {
            $conflictingCustomTour = CustomTour::where('tourguide_id', $request->tourguide_id)
                ->where('id', '!=', $id)
                ->where('start_date', '<=', $tour->end_date)
                ->where('end_date', '>=', $tour->start_date)
                ->exists();

            $conflictingAvailableTour = AvailableTour::where('tourguide_id', $request->tourguide_id)
            ->where('start_date', '<=', $tour->end_date)
            ->where('end_date', '>=', $tour->start_date)
            ->exists();

        if ($conflictingCustomTour || $conflictingAvailableTour) {
            return redirect()->route('admin.cust_tour_man.index')
                ->with('error', 'Tour Guide này đã được phân công cho một tour khác trong khoảng thời gian này!');
        }
    }

        $tour->update(['tourguide_id' => $request->tourguide_id]);

        return redirect()->route('admin.cust_tour_man.index')->with('success', 'Tour Guide đã được phân công thành công!');
    }

    public function assignDriver(Request $request, $id)
    {
        $request->validate([
            'driver_id' => 'nullable|exists:users,id',
        ]);

        $tour = CustomTour::findOrFail($id);

        // Kiểm tra xung đột thời gian
        if ($request->driver_id) {
            $conflictingTour = CustomTour::where('driver_id', $request->driver_id)
                ->where('id', '!=', $id)
                ->where('start_date', '<=', $tour->end_date)
                ->where('end_date', '>=', $tour->start_date)
                ->exists();

            if ($conflictingTour) {
                return redirect()->route('admin.cust_tour_man.index')
                    ->with('error', 'Driver này đã được phân công cho một tour khác trong khoảng thời gian này!');
            }
        }

        $tour->update(['driver_id' => $request->driver_id]);

        return redirect()->route('admin.cust_tour_man.index')->with('success', 'Driver đã được phân công thành công!');
    }
    
}
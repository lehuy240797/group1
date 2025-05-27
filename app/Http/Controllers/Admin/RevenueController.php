<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvailableTourBooking;
use App\Models\CustomTourBookings;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        // Query tour có sẵn (đã thanh toán)
        $availableTourQuery = AvailableTourBooking::where('payment_status', 'Thành Công');
        if ($startDate && $endDate) {
            $availableTourQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $availableTourBookings = $availableTourQuery->get(['booking_code', 'total_price', 'created_at']);

        // Query tour tự tạo (đã approved)
        $customTourQuery = CustomTourBookings::where('status', 'approved');
        if ($startDate && $endDate) {
            $customTourQuery->whereBetween('payment_date', [$startDate, $endDate]);
        }
        $customTourBookings = $customTourQuery->get(['tracking_code', 'total_price', 'payment_date']);

        // Tính tổng doanh thu
        $availableTourRevenue = $availableTourBookings->sum('total_price');
        $customTourRevenue = $customTourBookings->sum('total_price');
        $totalRevenue = $availableTourRevenue + $customTourRevenue;

        // === Tổng hợp doanh thu theo ngày ===

        // Định dạng ngày theo d/m/Y
        $availableByDate = $availableTourBookings->groupBy(function ($item) {
            return $item->created_at->format('d/m/Y');
        });

        $customByDate = $customTourBookings->groupBy(function ($item) {
            return $item->payment_date->format('d/m/Y');
        });

        // Danh sách ngày duy nhất có doanh thu
        $allDates = $availableByDate->keys()->merge($customByDate->keys())->unique();

        // Gộp doanh thu và số lượng
        $dailyRevenue = $allDates->map(function ($date) use ($availableByDate, $customByDate) {
            $available = $availableByDate->get($date, collect());
            $custom = $customByDate->get($date, collect());

            return [
                'date' => $date,
                'total_revenue' => $available->sum('total_price') + $custom->sum('total_price'),
                'count' => $available->count() + $custom->count(),
            ];
        })->sortByDesc('date')->values()->toArray();

        return view('management.tours.revenue.index', compact(
            'dailyRevenue',
            'availableTourBookings',
            'customTourBookings',
            'availableTourRevenue',
            'customTourRevenue',
            'totalRevenue',
            'startDate',
            'endDate'
        ));
    }
}

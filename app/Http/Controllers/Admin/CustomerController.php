<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\AvailableTourBooking;
use App\Models\CustomTourBookings;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;


class CustomerController extends Controller
{

public function index(Request $request)
{
    $search = $request->input('search');

    $available = AvailableTourBooking::with('availableTour')
        ->when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        })
        ->get()
        ->map(function ($item) {
            return (object)[
                'name' => $item->name,
                'email' => $item->email,
                'phone' => $item->phone,
                'type' => 'Có sẵn',
                'booking_code' => $item->booking_code,
                'tour_name' => optional($item->availableTour)->name_tour,
            ];
        });

    $custom = CustomTourBookings::with('customTour')
        ->when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        })
        ->get()
        ->map(function ($item) {
            return (object)[
                'name' => $item->name,
                'email' => $item->email,
                'phone' => $item->phone,
                'type' => 'Tự tạo',
                'booking_code' => $item->tracking_code,
                'tour_name' => optional($item->customTour)->destination,
            ];
        });

    $merged = $available->concat($custom)->sortByDesc('booking_code')->values();

    // Phân trang thủ công
    $currentPage = Paginator::resolveCurrentPage() ?: 1;
    $perPage = 10;
    $currentPageItems = $merged->slice(($currentPage - 1) * $perPage, $perPage)->values();

    $paginatedItems = new LengthAwarePaginator(
        $currentPageItems,
        $merged->count(),
        $perPage,
        $currentPage,
        ['path' => Paginator::resolveCurrentPath(), 'query' => $request->query()]
    );

    return view('management.customers.index', ['customers' => $paginatedItems]);
}

}

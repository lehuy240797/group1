<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvailableTour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\CustomTour;

class AvailTourManController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AvailableTour::with(['tourguide', 'driver', 'availableTourBookings']);

        if ($request->filled('name_tour')) {
            $query->where('name_tour', 'like', '%' . $request->name_tour . '%');
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '=', $request->end_date);
        }

        if ($request->filled('price')) {
            $query->where('price', '<=', $request->price);
        }

        if ($request->filled('tourguide_id')) {
        $query->where('tourguide_id', $request->tourguide_id);
    }

    if ($request->filled('driver_id')) {
        $query->where('driver_id', $request->driver_id);
    }

        // Lấy danh sách tours và tính booked_guests_count
        $tours = $query->get()->map(function ($availableTour) {
            $availableTour->booked_guests_count = $availableTour->availableTourBookings->sum('num_guests');
            return $availableTour;
        });

        return view('management.tours.avail_tour_man.index', compact('tours'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $start_date = $request->input('start_date') ?: now()->toDateString();
        $end_date = $request->input('end_date') ?: now()->addDays(1)->toDateString();

        $tourguides = User::where('role', 'tourguide')
            ->whereDoesntHave('customToursAsTourGuide', function ($q) use ($start_date, $end_date) {
                $q->where('start_date', '<=', $end_date)
                  ->where('end_date', '>=', $start_date);
            })
            ->whereDoesntHave('toursAsTourGuide', function ($q) use ($start_date, $end_date) {
                $q->where('start_date', '<=', $end_date)
                  ->where('end_date', '>=', $start_date);
            })
            ->get();

        $drivers = User::where('role', 'driver')
            ->whereDoesntHave('customToursAsDriver', function ($q) use ($start_date, $end_date) {
                $q->where('start_date', '<=', $end_date)
                  ->where('end_date', '>=', $start_date);
            })
            ->whereDoesntHave('toursAsDriver', function ($q) use ($start_date, $end_date) {
                $q->where('start_date', '<=', $end_date)
                  ->where('end_date', '>=', $start_date);
            })
            ->get();

        return view('management.tours.avail_tour_man.create', compact('tourguides', 'drivers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_tour' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'location_value' => 'required|string',
            'duration' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_guests' => 'required|integer|min:1',
            'transportation' => 'required|string|max:255',
            'hotel' => 'required|string|max:255',
            'tourguide_id' => 'nullable|exists:users,id',
            'driver_id' => 'nullable|exists:users,id',
            'image' => 'nullable|string',
        ]);

        if ($request->tourguide_id) {
            $conflictingTourGuideCustom = CustomTour::where('tourguide_id', $request->tourguide_id)
                ->where('start_date', '<=', $request->end_date)
                ->where('end_date', '>=', $request->start_date)
                ->exists();

            $conflictingTourGuideAvailable = AvailableTour::where('tourguide_id', $request->tourguide_id)
                ->where('start_date', '<=', $request->end_date)
                ->where('end_date', '>=', $request->start_date)
                ->exists();

            if ($conflictingTourGuideCustom || $conflictingTourGuideAvailable) {
                return redirect()->back()->with('error', 'Tour Guide này đã được phân công cho một tour khác trong khoảng thời gian này!')->withInput();
            }
        }

        if ($request->driver_id) {
            $conflictingDriverCustom = CustomTour::where('driver_id', $request->driver_id)
                ->where('start_date', '<=', $request->end_date)
                ->where('end_date', '>=', $request->start_date)
                ->exists();

            $conflictingDriverAvailable = AvailableTour::where('driver_id', $request->driver_id)
                ->where('start_date', '<=', $request->end_date)
                ->where('end_date', '>=', $request->start_date)
                ->exists();

            if ($conflictingDriverCustom || $conflictingDriverAvailable) {
                return redirect()->back()->with('error', 'Driver này đã được phân công cho một tour khác trong khoảng thời gian này!')->withInput();
            }
        }

        AvailableTour::create([
            'name_tour' => $request->name_tour,
            'description' => $request->description,
            'price' => $request->price,
            'location' => $request->location_value,
            'duration' => $request->duration,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'max_guests' => $request->max_guests,
            'transportation' => $request->transportation,
            'hotel' => $request->hotel,
            'tourguide_id' => $request->tourguide_id,
            'driver_id' => $request->driver_id,
            'image' => $request->image,
            'status' => AvailableTour::STATUS_NOT_STARTED,
        ]);

        return redirect()->route('admin.avail_tour_man.index')->with('success', 'Tour đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(AvailableTour $avail_tour)
    {
        $availableTourBookings = $avail_tour->availableTourBookings()->get();

        $imagePath = public_path('images/' . $avail_tour->location);
        $images = File::exists($imagePath) ? collect(File::files($imagePath))->map(fn($file) => $file->getFilename()) : [];
        return view('management.tours.avail_tour_man.show', [
            'availableTour' => $avail_tour,
            'availableTourBookings' => $availableTourBookings,
            'images' => $images
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AvailableTour $avail_tour)
    {
        $tourguides = User::where('role', 'tourguide')
            ->whereDoesntHave('customToursAsTourGuide', function ($q) use ($avail_tour) {
                $q->where('start_date', '<=', $avail_tour->end_date)
                  ->where('end_date', '>=', $avail_tour->start_date);
            })
            ->whereDoesntHave('toursAsTourGuide', function ($q) use ($avail_tour) {
                $q->where('start_date', '<=', $avail_tour->end_date)
                  ->where('end_date', '>=', $avail_tour->start_date)
                  ->where('id', '!=', $avail_tour->id);
            })
            ->get();

        $drivers = User::where('role', 'driver')
            ->whereDoesntHave('customToursAsDriver', function ($q) use ($avail_tour) {
                $q->where('start_date', '<=', $avail_tour->end_date)
                  ->where('end_date', '>=', $avail_tour->start_date);
            })
            ->whereDoesntHave('toursAsDriver', function ($q) use ($avail_tour) {
                $q->where('start_date', '<=', $avail_tour->end_date)
                  ->where('end_date', '>=', $avail_tour->start_date)
                  ->where('id', '!=', $avail_tour->id);
            })
            ->get();

        // Ánh xạ để hiển thị tên địa điểm dễ đọc
        $locationMap = [
            'Tp.HCM - Hà Nội' => ['display' => 'Hà Nội', 'value' => 'hanoi'],
            'Tp.HCM - Đà Nẵng' => ['display' => 'Đà Nẵng', 'value' => 'danang'],
            'Tp.HCM - Nha Trang' => ['display' => 'Nha Trang', 'value' => 'nhatrang'],
            'Tp.HCM - Huế' => ['display' => 'Huế', 'value' => 'hue'],
            'Tp.HCM - Sapa' => ['display' => 'Sapa', 'value' => 'sapa'],
            'Tp.HCM - Phú Quốc' => ['display' => 'Phú Quốc', 'value' => 'phuquoc'],
        ];

        return view('management.tours.avail_tour_man.edit', [
            'availableTour' => $avail_tour,
            'tourguides' => $tourguides,
            'drivers' => $drivers,
            'locationMap' => $locationMap,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AvailableTour $avail_tour)
{
    $request->validate([
        'name_tour' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'location_value' => 'required|string',
        'duration' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'max_guests' => 'required|integer|min:1',
        'transportation' => 'required|string|max:255',
        'hotel' => 'required|string|max:255',
        'tourguide_id' => 'nullable|exists:users,id',
        'driver_id' => 'nullable|exists:users,id',
        'image' => 'nullable|string',
    ]);

    if ($request->tourguide_id) {
            $conflictingTourGuideCustom = CustomTour::where('tourguide_id', $request->tourguide_id)
                ->where('start_date', '<=', $request->end_date)
                ->where('end_date', '>=', $request->start_date)
                ->exists();

            $conflictingTourGuideAvailable = AvailableTour::where('tourguide_id', $request->tourguide_id)
                ->where('id', '!=', $avail_tour->id)
                ->where('start_date', '<=', $request->end_date)
                ->where('end_date', '>=', $request->start_date)
                ->exists();

            if ($conflictingTourGuideCustom || $conflictingTourGuideAvailable) {
                return redirect()->back()->with('error', 'Tour Guide này đã được phân công cho một tour khác trong khoảng thời gian này!')->withInput();
            }
        }

        if ($request->driver_id) {
            $conflictingDriverCustom = CustomTour::where('driver_id', $request->driver_id)
                ->where('start_date', '<=', $request->end_date)
                ->where('end_date', '>=', $request->start_date)
                ->exists();

            $conflictingDriverAvailable = AvailableTour::where('driver_id', $request->driver_id)
                ->where('id', '!=', $avail_tour->id)
                ->where('start_date', '<=', $request->end_date)
                ->where('end_date', '>=', $request->start_date)
                ->exists();

            if ($conflictingDriverCustom || $conflictingDriverAvailable) {
                return redirect()->back()->with('error', 'Driver này đã được phân công cho một tour khác trong khoảng thời gian này!')->withInput();
            }
        }

    $avail_tour->update([
        'name_tour' => $request->name_tour,
        'description' => $request->description,
        'price' => $request->price,
        'location' => $request->location_value,
        'duration' => $request->duration,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'max_guests' => $request->max_guests,
        'transportation' => $request->transportation,
        'hotel' => $request->hotel,
        'tourguide_id' => $request->tourguide_id,
        'driver_id' => $request->driver_id,
        'image' => $request->image,
        'status' => $request->status ?? $avail_tour->status,
    ]);

    return redirect()->route('admin.avail_tour_man.index')->with('success', 'Tour đã được cập nhật thành công!');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AvailableTour $avail_tour)
    {
        // Xóa tất cả các booking liên quan
        $avail_tour->availableTourBookings()->delete();

        // Xóa hình ảnh nếu có
        if ($avail_tour->image) {
            $imagePath = public_path('images/' . $avail_tour->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        // Xóa tour
        $avail_tour->delete();
        return redirect()->route('admin.avail_tour_man.index')->with('success', 'Tour đã được xóa thành công!');
    }

    /**
     * Display available tours for frontend.
     */
    public function showAvailableTours()
    {
        $availableTours = AvailableTour::whereRaw('max_guests > (SELECT COALESCE(SUM(num_guests), 0) FROM available_tour_bookings WHERE available_tour_bookings.tour_id = available_tours.id)')
            ->where('type', 'available')
            ->where('status', 'not_started')
            ->get();

        return view('available-tours', compact('availableTours'));
    }

    /**
     * Update the status of a tour.
     */
    public function updateStatus(Request $request, AvailableTour $avail_tour)
    {
        $request->validate([
            'status' => 'required|in:not_started,ongoing,completed',
        ]);

        $avail_tour->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Trạng thái tour đã được cập nhật!');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\AvailableTour;
use App\Models\CustomTour;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Hiển thị danh sách các tour được phân công cho người dùng hiện tại.
     */
    public function index(): View
    {
        $currentUser = Auth::user();
        $assignedTours = collect(); // Kết hợp cả tour có sẵn và tour custom

        if ($currentUser->role === 'tourguide') {
            $availableTours = AvailableTour::where('tourguide_id', $currentUser->id)->get();
            $customTours = CustomTour::with('customTourBooking')
                ->where('tourguide_id', $currentUser->id)
                ->get();
        } elseif ($currentUser->role === 'driver') {
            $availableTours = AvailableTour::where('driver_id', $currentUser->id)->get();
            $customTours = CustomTour::with('customTourBooking')
                ->where('driver_id', $currentUser->id)
                ->get();
        } else {
            $availableTours = collect();
            $customTours = collect();
        }

        // Gắn nhãn type để phân biệt
        $availableTours->each(function ($tour) {
            $tour->source = 'available';
        });

        $customTours->each(function ($tour) {
            $tour->source = 'custom';
        });

        // Gộp lại
        $assignedTours = $availableTours->concat($customTours);

        return view('my-tasks', compact('assignedTours'));
    }
}

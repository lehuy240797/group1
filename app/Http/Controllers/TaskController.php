<?php

namespace App\Http\Controllers;

use App\Models\AvailableTour;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Hiển thị danh sách các tour được phân công cho người dùng hiện tại.
     */
    public function index(): View
    {
        $user = Auth::user();
        $tours = collect(); // Khởi tạo một collection rỗng

        if ($user->role === 'tourguide') {
            $tours =  AvailableTour::where('tourguide_id', $user->id)->get();
        } elseif ($user->role === 'driver') {
            $tours =  AvailableTour::where('driver_id', $user->id)->get();
        }

        return view('my-tasks', compact('tours'));
    }
}

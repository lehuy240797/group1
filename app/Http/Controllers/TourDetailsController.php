<?php

namespace App\Http\Controllers;

use App\Models\AvailableTour;
use Illuminate\View\View;

class TourDetailsController extends Controller
{
    public function show(AvailableTour $availableTour): View
    {
        $location = $availableTour->location; 
        $duration = $availableTour->duration;
        $filePath = public_path("data/itineraries/{$location}.json");

        $itineraryText = '';
        if (file_exists($filePath)) {
            $json = json_decode(file_get_contents($filePath), true);
            $itineraryText = $json[$duration] ?? 'Lịch trình chưa có sẵn.';
        } else {
            $itineraryText = 'Không tìm thấy lịch trình.';
        }

        return view('tour-details', [
            'availableTour' => $availableTour,
            'itineraryText' => $itineraryText,
        ]);
    }
}

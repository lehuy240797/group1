<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Models\AvailableTour;

class HomeController extends Controller
{

    public function home() {
    $availableTours = AvailableTour::where('type', 'available')->get();
    return view('home', compact('availableTours'));
    }
}
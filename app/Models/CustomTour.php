<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CustomTour extends Model
{
    use HasFactory;

    protected $table = 'custom_tours'; // Khai báo tên bảng

    protected $fillable = [
        'destination',
        'start_date',
        'end_date',
        'budget',
        'flight_price',
        'adult_tickets',
        'child_tickets',
        'customer_id',
        'tourguide_id',
        'driver_id',
        'hotel',
        'places'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

   public function tourGuide()
{
    return $this->belongsTo(User::class, 'tourguide_id');
}

public function driver()
{
    return $this->belongsTo(User::class, 'driver_id');
}

public function customTourBooking()
{
    return $this->hasOne(CustomTourBookings::class, 'tour_id');
}

    protected static function booted()
    {
        static::creating(function ($customTour) {
            Log::info('Creating CustomTour:', [
                'budget' => $customTour->budget,
                'total_price' => $customTour->total_price
            ]);
        });

        static::deleting(function ($customTour) {
            CustomTourBookings::where('tour_id', $customTour->id)->delete();
        });
    }
}

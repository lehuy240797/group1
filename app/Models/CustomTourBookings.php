<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomTourBookings extends Model
{
    use HasFactory;

    protected $table = 'custom_tour_bookings';

    protected $fillable = [
        'tracking_code',
        'user_id',
        'tour_id',
        'type',
        'total_price',
        'payment_date',
        'status',
        'customer_id',
        'phone',
        'email',
        'name',
        'start_date',
        'end_date',
        'num_guests',
        'hotel',
        'flight_price',
        'adult_tickets',
        'child_tickets',
        'tour_guide_name',
        'driver_name',
        'places'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

   public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customTour()
    {
        return $this->belongsTo(CustomTour::class, 'tour_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

}
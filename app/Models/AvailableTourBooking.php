<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableTourBooking extends Model
{
    use HasFactory;

    protected $table = 'available_tour_bookings';
    protected $fillable = [
       'tour_id',
        'num_adults',
        'num_children',
        'num_guests',
        'name',
        'email',
        'phone',
        'total_price',
        'payment_status',
        'booking_code',
        'customer_id',
    ];


    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function availableTour()
    {
        return $this->belongsTo(AvailableTour::class, 'tour_id', 'id');
    }
}

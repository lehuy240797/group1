<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
    ];

    public function availableBookings()
    {
        return $this->hasMany(AvailableTourBooking::class);
    }

    public function customTours()
    {
        return $this->hasMany(CustomTour::class);
    }
    public function customBookings()
    {
        return $this->hasMany(CustomTourBookings::class);
    }


    // public function customer()
    // {
    //     return $this->belongsTo(Customer::class);
    // }

}

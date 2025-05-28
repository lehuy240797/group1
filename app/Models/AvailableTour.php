<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AvailableTour extends Model
{
    use HasFactory;
    
    const STATUS_NOT_STARTED = 'not_started';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';

    protected $table = 'available_tours';
    protected $fillable = [
        'name_tour',
        'description',
        'price',
        'location',
        'duration',
        'start_date',
        'end_date',
        'max_guests',
        'transportation',
        'hotel',
        'tourguide_id',
        'driver_id',
        'image',
        'type',
        'status',
    ];

    public function tourguide()
    {
        return $this->belongsTo(User::class, 'tourguide_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function availableTourBookings(): HasMany
    {
        return $this->hasMany(AvailableTourBooking::class, 'tour_id', 'id');
    }

    public function isFullyBooked()
    {
        $bookedGuests = $this->availableTourBookings()->sum('num_guests');

        return $this->max_guests !== null && $bookedGuests >= $this->max_guests;
    }
    public function getFormattedDurationAttribute(): string
    {
        if (preg_match('/(\d+)n(\d+)d/', $this->duration, $matches)) {
            return "{$matches[1]} ngày {$matches[2]} đêm";
        }
        return 'Chưa xác định';
    }
}

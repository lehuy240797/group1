<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany

class User extends Authenticatable
{
    
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'staff_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

   protected $casts = [
    'email_verified_at' => 'datetime',
];

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function toursAsTourGuide(): HasMany
    {
        return $this->hasMany(AvailableTour::class, 'tourguide_id');
    }

    public function toursAsDriver(): HasMany
    {
        return $this->hasMany(AvailableTour::class, 'driver_id');
    }

    public function customToursAsTourGuide()
{
    return $this->hasMany(CustomTour::class, 'tourguide_id');
}

public function customToursAsDriver()
{
    return $this->hasMany(CustomTour::class, 'driver_id');
}

}

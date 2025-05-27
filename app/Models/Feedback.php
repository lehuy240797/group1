<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'message',
        'booking_code',
        'tour_id',
        'rating',
        'admin_reply',
        'replied_at',
    ];

    public function tour()
    {
        return $this->belongsTo(AvailableTour::class, 'tour_id');
    }
}
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class BookingPaid implements ShouldBroadcast
{
    use SerializesModels;

    public $bookingCode;
    public $token;

    public function __construct($bookingCode, $token)
    {
        $this->bookingCode = $bookingCode;
        $this->token = $token;
    }

    public function broadcastOn()
    {
        return new Channel("booking-status.{$this->token}");
    }

    public function broadcastWith()
    {
        return [
            'bookingCode' => $this->bookingCode,
        ];
    }

    public function broadcastAs()
    {
        return 'BookingPaid';
    }
}

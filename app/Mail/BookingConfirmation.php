<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $bookingDetails;

    public function __construct($bookingDetails)
    {
        $this->bookingDetails = $bookingDetails;
    }

    public function build()
    {
        return $this->subject('Xác nhận đặt tour thành công')
                    ->view('emails.booking_confirmation')
                    ->with(['bookingDetails' => $this->bookingDetails]);
    }
}
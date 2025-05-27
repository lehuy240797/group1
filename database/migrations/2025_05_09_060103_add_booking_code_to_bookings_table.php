<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddBookingCodeToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_code')->unique()->nullable();
        });

         // Tạo booking code cho các booking đã có
         \App\Models\Booking::all()->each(function ($booking) {
            if (!$booking->booking_code) {
                $booking->booking_code = Str::upper(Str::random(8)); // Tạo mã ngẫu nhiên 8 ký tự
                $booking->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('booking_code');
        });
    }
}
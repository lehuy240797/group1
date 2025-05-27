<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('custom_tour_bookings', function (Blueprint $table) {
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->integer('num_guests')->nullable();
    });
}

public function down()
{
    Schema::table('custom_tour_bookings', function (Blueprint $table) {
        $table->dropColumn(['start_date', 'end_date', 'num_guests']);
    });
}

};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToAvailableTourBookingsTable extends Migration
{
    public function up()
    {
        Schema::table('available_tour_bookings', function (Blueprint $table) {
            $table->integer('num_adults')->default(0)->after('tour_id');
            $table->integer('num_children')->default(0)->after('num_adults');
            $table->integer('num_infants')->default(0)->after('num_children');
        });
    }

    public function down()
    {
        Schema::table('available_tour_bookings', function (Blueprint $table) {
            $table->dropColumn(['num_adults', 'num_children', 'num_guests']);
        });
    }
}
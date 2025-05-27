<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameToCustomTourBookingsTable extends Migration
{
    public function up()
    {
        Schema::table('custom_tour_bookings', function (Blueprint $table) {
            $table->string('name')->after('id');  // thêm cột name sau cột id
        });
    }

    public function down()
    {
        Schema::table('custom_tour_bookings', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
}

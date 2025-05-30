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
        $table->unsignedBigInteger('tourguide_id')->nullable()->after('tour_id');
    });
}

public function down()
{
    Schema::table('custom_tour_bookings', function (Blueprint $table) {
        $table->dropColumn('tourguide_id');
    });
}

};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('custom_tours', function (Blueprint $table) {
        $table->string('hotel')->nullable();
        $table->text('places')->nullable();
    });

    Schema::table('custom_tour_bookings', function (Blueprint $table) {
        $table->string('hotel')->nullable();
        $table->text('places')->nullable();
    });
}

public function down(): void
{
    Schema::table('custom_tours', function (Blueprint $table) {
        $table->dropColumn(['hotel', 'places']);
    });

    Schema::table('custom_tour_bookings', function (Blueprint $table) {
        $table->dropColumn(['hotel', 'places']);
    });
}

};

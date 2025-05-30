<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('custom_tour_bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('driver_id')->nullable()->after('tour_id');
            // Nếu muốn tạo khóa ngoại:
            // $table->foreign('driver_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('custom_tour_bookings', function (Blueprint $table) {
            $table->dropColumn('driver_id');
        });
    }
};


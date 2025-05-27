<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('available_tour_bookings', function (Blueprint $table) {
            $table->renameColumn('name', 'name');
            $table->renameColumn('phone', 'phone');
            $table->renameColumn('email', 'email');
        });
    }

    public function down(): void
    {
        Schema::table('available_tour_bookings', function (Blueprint $table) {
            $table->renameColumn('name', 'name');
            $table->renameColumn('phone', 'phone');
            $table->renameColumn('email', 'email');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->string('booking_code')->nullable()->after('email');
            $table->unsignedBigInteger('tour_id')->nullable()->after('booking_code');
            $table->integer('rating')->nullable()->after('tour_id');
            $table->foreign('tour_id')->references('id')->on('available_tours')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropForeign(['tour_id']);
            $table->dropColumn(['booking_code', 'tour_id', 'rating']);
        });
    }
};
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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tour_id'); // Khóa ngoại đến bảng tours
            $table->integer('num_guests');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->decimal('total_price', 10, 2); // 10 chữ số, 2 số thập phân
            $table->timestamps();

            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade'); // Ràng buộc khóa ngoại (tùy chọn)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
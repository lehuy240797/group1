<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('feedback', function (Blueprint $table) {
            // Xóa ràng buộc khóa ngoại hiện tại
            $table->dropForeign(['tour_id']);
            // Thay đổi cột tour_id để cho phép NULL
            $table->unsignedBigInteger('tour_id')->nullable()->change();
            // Thêm lại ràng buộc khóa ngoại với ON DELETE SET NULL
            $table->foreign('tour_id')->references('id')->on('available_tours')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('feedback', function (Blueprint $table) {
            // Xóa ràng buộc khóa ngoại
            $table->dropForeign(['tour_id']);
            // Khôi phục cột tour_id không nullable
            $table->unsignedBigInteger('tour_id')->nullable(false)->change();
            // Thêm lại ràng buộc khóa ngoại
            $table->foreign('tour_id')->references('id')->on('available_tours')->onDelete('set null');
        });
    }
};
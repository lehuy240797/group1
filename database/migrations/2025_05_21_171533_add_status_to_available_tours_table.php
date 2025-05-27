<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('available_tours', function (Blueprint $table) {
            $table->string('status')->default('chua_bat_dau'); // trạng thái mặc định "chưa bắt đầu"
        });
    }

    public function down()
    {
        Schema::table('available_tours', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};

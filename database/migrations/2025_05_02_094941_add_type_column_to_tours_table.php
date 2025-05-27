<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeColumnToToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->string('type')->nullable()->default('available')->after('price');
            // Bạn có thể đặt giá trị mặc định là 'available' hoặc null tùy theo logic của bạn.
            // Vị trí cột 'type' sẽ sau cột 'price'. Bạn có thể điều chỉnh nếu cần.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
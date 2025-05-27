<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('available_tours', function (Blueprint $table) {
            $table->renameColumn('name', 'name_tour');
        });
    }

    public function down(): void
    {
        Schema::table('available_tours', function (Blueprint $table) {
            $table->renameColumn('name_tour', 'name');
        });
    }
};

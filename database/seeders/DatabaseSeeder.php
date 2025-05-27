<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gọi UsersTableSeeder để tạo các tài khoản user
        $this->call(UsersTableSeeder::class);

        // Các seeders khác nếu có
        // $this->call(OtherSeeder::class);
    }
}
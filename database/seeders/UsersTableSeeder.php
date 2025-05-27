<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo tài khoản admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@tourgether.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'admin_type' => 'admin',
        ]);

      
        // Bạn có thể thêm các user khác ở đây nếu cần
    }
}
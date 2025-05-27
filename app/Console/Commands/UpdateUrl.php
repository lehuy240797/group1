<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateUrl extends Command
{
    protected $signature = 'app:update-url';
    protected $description = 'Cập nhật APP_URL theo IP nội bộ hiện tại';

    public function handle()
    {
        $ip = getHostByName(getHostName());
        if (!$ip) {
            $this->error('Không thể lấy IP.');
            return;
        }

        $newUrl = "http://{$ip}:8000";
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            $this->error('.env file không tồn tại!');
            return;
        }

        $envContent = File::get($envPath);
        $envContent = preg_replace('/^APP_URL=.*/m', "APP_URL={$newUrl}", $envContent);
        File::put($envPath, $envContent);

        $this->info("APP_URL đã được cập nhật thành: {$newUrl}");
    }
}

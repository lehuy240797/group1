<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Đăng ký các lệnh Artisan tùy chỉnh tại đây.
     */
    protected $commands = [
        \App\Console\Commands\UpdateUrl::class,
    ];

    /**
     * Định nghĩa các lịch cron (nếu có).
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Đăng ký đường dẫn tới file lệnh.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

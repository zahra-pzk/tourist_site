<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// اینجا کامند دلخواه خودت رو ایمپورت می‌کنی:
use App\Console\Commands\MergeCsvFiles;

class Kernel extends ConsoleKernel
{
    /**
     * این بخش برای ثبت دستی کامندهاست
     */
    protected $commands = [
        MergeCsvFiles::class,
    ];

    /**
     * برنامه‌ریزی زمان‌بندی تسک‌ها (اختیاری)
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * ثبت مسیرهای کامندها در این متد انجام می‌شه
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

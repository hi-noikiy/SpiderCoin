<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\BDataKlineCommand::class,
        \App\Console\Commands\BDataNowCommand::class,
        \App\Console\Commands\BDataDepthCommand::class,
        \App\Console\Commands\BDataTradesCommand::class,
        \App\Console\Commands\DingTouInspectionCommand::class,
        \App\Console\Commands\DingTouSellCommand::class,
        \App\Console\Commands\DingTouBuyCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
    }
}

<?php

namespace App\Console;

use App\Commands\ChargeNebulaOrder;
use App\Console\Commands\WeeklyDraw;
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
        WeeklyDraw::class,
        Commands\ChargeNebulaOrder::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('nebula:pay')->daily();
        $schedule->command('pryo:draw')->monthly();
        $schedule->command('subscription:renew')->monthly();
        $schedule->command('raffle:draw')->weekly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

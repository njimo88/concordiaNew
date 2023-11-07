<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\ClickAsso;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('process:email-queue')->everyMinute();
        $schedule->job(new ClickAsso)->dailyAt('00:00');
        $schedule->command('bills:transfer')->dailyAt('00:00');
        $schedule->job(new \App\Jobs\SyncWithClickAssoJob)->dailyAt('00:00');
        $schedule->command('baskets:delete-daily')->dailyAt('00:00');
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

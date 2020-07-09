<?php

namespace App\Console;

use App\Console\Commands\CheckCalls;
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
	    \App\Console\Commands\BonusesUpdatePartners::class,
//	    \App\Console\Commands\BonusesUpdateTariffs::class,
//	    \App\Console\Commands\RatingUpdateTariffs::class,
//	    \App\Console\Commands\BonusesUpdateBox::class,
	    \App\Console\Commands\UpdateCurrency::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
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

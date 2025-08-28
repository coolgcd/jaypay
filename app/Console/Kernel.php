<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

     protected $commands = [
        \App\Console\Commands\CalculateDailyIncome::class,
    \App\Console\Commands\ProcessSponsorDailyIncome::class,
    \App\Console\Commands\ProcessBinaryMatching::class,
    \App\Console\Commands\ProcessRewardBonus::class,
];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
          $schedule->command('income:calculate')->daily();


           $schedule->command('income:process-sponsor')->dailyAt('02:00');

           
               $schedule->command('process:binary-matching')->daily(); // or ->hourly() for testing

           
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

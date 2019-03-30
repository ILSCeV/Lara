<?php

namespace Lara\Console;

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
        Commands\TestLog::class,
        Commands\LDAPsync::class,
        Commands\UpdateLara::class,
        Commands\CleanShiftTypes::class,
        Commands\SyncBDclub::class,
        Commands\ResetUserPassword::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Syncronize Lara Persons with the latest status on the LDAP server
        $schedule->command('lara:ldapsync')->dailyAt('04:00');

        // Cleanup of the ShiftTypes table - remove unused entries, substitute duplicates
        $schedule->command('lara:clean-shifttypes')->dailyAt('04:10');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}

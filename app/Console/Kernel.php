<?php

namespace App\Console;

use App\Console\Commands\ProxyNotice;
use App\Console\Commands\ScanChapters;
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
        ScanChapters::class,
        ProxyNotice::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('winex:scan-chapters')->everyFifteenMinutes()->withoutOverlapping(15);
        $schedule->command('winex:scan-chapters')->everyThirtyMinutes();
        // $schedule->command('winex:proxy-notice')->daily()->weekly();	

        $schedule->command('backup:clean')->daily()->at('11:00');
        $schedule->command('backup:run')->daily()->at('12:00');

        if (config('appsettings.telescope_enabled')) {
            $schedule->command('telescope:prune --hours=48')->daily();
        }

        $schedule->command('model:prune')->daily();
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

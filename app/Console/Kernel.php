<?php

namespace App\Console;

use App\Jobs\AssignCaptainToOrder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run custom logic to check and start queue worker only if jobs exist
        $schedule->command('queue:work')->everyMinute()->withoutOverlapping();

        $schedule->command('queue:restart')->everyFifteenMinutes();

        $schedule->command('orders:process-unassigned')->dailyAt('00:05');

        $schedule->command('notify:expired-packages')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

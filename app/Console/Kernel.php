<?php

namespace App\Console;

use Carbon\Carbon;
use App\Jobs\SendDailySalesReportJob;
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
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(
            new SendDailySalesReportJob('seller', Carbon::parse('now', 'America/Sao_Paulo')
                ->subDay()
                ->setTimezone('UTC')
                ->format('Y-m-d'))
        )->everyMinute()->timezone('America/Sao_Paulo')->withoutOverlapping(60)->name('sendDailySalesReportJob:seller')->onOneServer();

        $schedule->job(
            new SendDailySalesReportJob('admin', Carbon::parse('now', 'America/Sao_Paulo')
                ->subDay()
                ->setTimezone('UTC')
                ->format('Y-m-d'))
        )->everyMinute()->timezone('America/Sao_Paulo')->withoutOverlapping(60)->name('sendDailySalesReportJob:admin')->onOneServer();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

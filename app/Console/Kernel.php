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
        \App\Console\Commands\ArtisanOnlineCount::class,
        \App\Console\Commands\ArtisanPayCount::class,
        \App\Console\Commands\ArtisanMoneyCount::class,
        \App\Console\Commands\ArtisanBwsCount::class,
        \App\Console\Commands\ArtisanBrnnMoneyCount::class,
        \App\Console\Commands\ArtisanPayGoodsCount::class,
        \App\Console\Commands\ArtisanPayProfileCount::class,
        \App\Console\Commands\ArtisanPaySceneCountModel::class,
        \App\Console\Commands\ArtisanActiveCount::class,
        \App\Console\Commands\ArtisanMatchCount::class,
        \App\Console\Commands\ArtisanLuckDrawCount::class,
        \App\Console\Commands\ArtisanSeasonCount::class,
        \App\Console\Commands\ArtisanDayCount::class,
        \App\Console\Commands\ArtisanZfbMoneyCount::class,    
        \App\Console\Commands\ArtisanCoinCount::class,
        \App\Console\Commands\ArtisanMatchRankCount::class,
        \App\Console\Commands\ArtisanCoinRankCount::class,
        \App\Console\Commands\ArtisanPlayCoinCount::class,
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
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}

<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\BuildChildCommand',
        'App\Console\Commands\BuildForkCommand',
        'App\Console\Commands\BuildNormalCommand',
        'App\Console\Commands\BuildRawCommand',
        'App\Console\Commands\BuildReactCommand',
        'App\Console\Commands\BuildReactSocketCommand',
        'App\Console\Commands\ChildReactCommand',
        'App\Console\Commands\SlidesServerCommand',
        'App\Console\Commands\SocketServerCommand'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}

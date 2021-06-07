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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        //$date = Carbon::now()->toW3cString();
        $environment = env('APP_ENV');
        //$schedule->command('backup:clean')->daily()->at('01:00');
        if($environment === "production"){
          $schedule->command('backup:clean')->daily()->at('01:00');
          $schedule->command('backup:run')->daily()->at('01:30');
          //$schedule->command('backup:run')->daily()->at('10:20');
          /*$schedule->command('backup:run');
          Mail::raw('ghin.io database backup ran successfully',  function($message){
               $message->from('support@ghin.io', "ghin.io support");
               $message->subject("ghin.io database backup ran successfully");
               $message->to(env('ADMIN_EMAIL'));
           });*/
        }

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

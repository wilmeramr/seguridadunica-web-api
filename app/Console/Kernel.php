<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Stringable;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function scheduleTimezone(){

        return "America/Argentina/Buenos_Aires";
    }
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
       // $schedule->command('BashInicial:process')->cron('* * * * *');
       $schedule->command('Token:Enviar')->cron('* * * * *');

        $schedule->command('MailIngreso:Enviar')->cron('* * * * *');
       //  $schedule->command('Autorizaciones:Desactivar')->daily();
         $schedule->command('mailautorizacion:enviar')->cron('* * * * *');
        //$schedule->command('barcode:clear')->everyMinute();
        // $schedule->command('enviarnotifications:enviar')->everyMinute();


    }
    protected function shortSchedule(\Spatie\ShortSchedule\ShortSchedule $shortSchedule)
    {
         $shortSchedule->command('mailautorizacion:enviar')->everySeconds(30);
       //  $shortSchedule->command('barcode:clear')->everyMinute();

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

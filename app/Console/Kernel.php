<?php

namespace Vanguard\Console;

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
        \Vanguard\Console\Commands\Inspire::class,
        \Vanguard\Console\Commands\ActualizaFeriados::class,
        \Vanguard\Console\Commands\EmailContratos::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();

        $schedule->command('contrato:finalizacion')
                 ->hourly();

        //cronjob de feriados
        $schedule->command('feriado:actualiza')
                ->cron('59 23 31 12 *');

        $schedule->command('email:aviso')
                ->dailyAt('7:00');
    }
}

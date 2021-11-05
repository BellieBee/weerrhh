<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;
use Vanguard\Feriado;

class ActualizaFeriados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feriado:actualiza';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el ano de los dias feriados';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $feriados = Feriado::All();

        foreach ($feriados as $fecha) {
            
            $actualizaFecha = date_add(date_create($fecha->fecha), date_interval_create_from_date_string('1 year'));
            $fecha->fecha = $actualizaFecha;
            $fecha->save();
        }

        $this->info('Las fechas de los feriados han sido actualizadas correctamente');
    }
}

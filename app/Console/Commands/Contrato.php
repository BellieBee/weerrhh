<?php

namespace Vanguard\Console\Commands;
use DB;
use Illuminate\Console\Command;

class Contrato extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contrato:finalizacion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'adjunta adenda en la bd';

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
        DB::table('adendas')->insert(
            [
                'fecha_contrato' => '', 
                'fecha_contrato_nueva' => '',
                'fecha_cumplimineto' => '', 
                'fecha_cumplimiento_nueva' => '',
                'motivo' => '',

            ]
        );
        
    }
}

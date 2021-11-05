<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;
use Vanguard\Contrato;
use Vanguard\User;
use Entrust;
use Mail;

class EmailContratos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:aviso';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'envia un email a la coordinadora de los contratos que estan por vencer';

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
        $contratos = Contrato::All();

        foreach ($contratos as $contrato) {
            
            if ($contrato->fecha_fin == date('Y-m-d') && $contrato->status == 5) {
                
                $user = $contrato->user;

                $data = array(
                    'n_contrato' => $contrato->n_contrato,
                    'fecha_fin' => $contrato->fecha_fin,
                    'nombre' => $user->first_name,
                    'apellido' => $user->last_name,
                    'status' => $contrato->status
                );

                Mail::send('emails.contratos.vence_contrato', $data, function ($message) use ($user)
                {
                    $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                    $message->subject('Aviso de vencimiento de contrato');
                    $message->to($user->withRole('Coordinadora')->get(), $user->first_name);
                });
            }
            elseif (date('Y-m', strtotime($contrato->fecha_fin)) == date('Y-m') && date("d", strtotime($contrato->fecha_fin)) - date('d') == 5 ) {
                
                $user = $contrato->user;

                $data = array(
                    'n_contrato' => $contrato->n_contrato,
                    'fecha_fin' => $contrato->fecha_fin,
                    'nombre' => $user->first_name,
                    'apellido' => $user->last_name,
                    'sexo' => $user->sexo,
                    'status' => $contrato->status
                );

                Mail::send('emails.contratos.falta_cinco', $data, function ($message) use ($user)
                {
                    $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                    $message->subject('Faltan 5 dias para que expire el contrato');
                    $message->to($user->withRole('Coordinadora')->get(), $user->first_name);
                });
            }

            $this->info('Se enviaron los emails correspondientes');
        }
    }
}

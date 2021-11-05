<?php

namespace Vanguard\Http\Controllers;

use Illuminate\Http\Request;

use Vanguard\Contrato;
use Vanguard\Http\Requests;
use Carbon\Carbon;
use Vanguard\Feriado;
use Vanguard\User;
use Illuminate\Support\Facades\Mail;
class CronController extends Controller
{
    public function contrato_email()
    { 
      $coord = User::select('first_name', 'email')->withRole('Coordinadora')->get();
      $contratos = Contrato::where('status',1)->get();//contratos aprobados
       
        foreach ($contratos as $contrato) {
           	$user = $contrato->user;
            
           	$data = array(
               'contrato' => $contrato,
               'user' => $user                
            );
          	//Contratos que finalizan hoy y tiene un status = 1 que son los aprobados    
          	if ($contrato->fecha_fin == date('Y-m-d')) {

          	    if (!$contrato->cumplimiento) {
            		$contrato->status=5;//contrato vencido
            		$contrato->save();
            	}           
            	//return view('emails.contratos.vence_contrato',$data);
	            Mail::send('emails.contratos.vence_contrato', $data, function ($message) use ($user,$coord)
	            {
	              $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
	              $message->subject('Aviso de vencimiento de contrato de ');
	              $message->to($coord[0]['email'], $coord[0]['first_name']);
	             
	            });
	            
          	}
          	elseif (date('Y-m', strtotime($contrato->fecha_fin)) == date('Y-m') && date("d", strtotime($contrato->fecha_fin)) - date('d') == 5 ) {
          		//return view('emails.contratos.falta_cinco',$data);
	            Mail::send('emails.contratos.falta_cinco', $data, function ($message) use ($user,$coord)
	            {
	              $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
	              $message->subject('Faltan 5 dias para que expire el contrato');
	              $message->to($coord[0]['email'], $coord[0]['first_name']);
	            });
	            echo $coord[0]['email'];
          	}
          	
          
        }//foreach
    }
    public function actualizarFeriados()
    {
        $feriados = Feriado::All();

        foreach ($feriados as $fecha) {
            
            $actualizaFecha = date_add(date_create($fecha->fecha), date_interval_create_from_date_string('1 year'));
            $fecha->fecha = $actualizaFecha;
            $fecha->save();
        }        
    }    
   public function email_prueba()
    {
        //sleep(5);
        $email=$_POST['email'];
    
        $data=[];
        $mail=Mail::send('emails.prueba', $data, function ($message) use ($email) {
                $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                $message->subject('Email Prueba');
                $message->to('abel291m@gmail.com');
        }); 
        echo $mail;      
    } 
}

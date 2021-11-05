<?php

namespace Vanguard\Http\Controllers;

use Illuminate\Http\Request;

use Vanguard\Http\Requests;
use Entrust;
use Mail;
use PDF;
use DB;
use Vanguard\Cargo;
use Vanguard\User;
use Vanguard\Month;
use Vanguard\Vacaciones;
use Vanguard\Role;
use Vanguard\Permission;
use Carbon\Carbon;

class VacacionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-vacaciones-todos|ver-vacaciones-oficina|ver-vacaciones-solo');       
    }

    public function index()
    {
    	$users="";
    	$oficina_id = auth()->user()->oficina_id;
    	
        if (Entrust::can('ver-vacaciones-todos')) {

            $vacaciones = Vacaciones::orderBy('created_at','desc')->get(); 
        }
        elseif (Entrust::can('ver-vacaciones-oficina')) {
            //arreglo para que no aparezcan los cargos de director
            $usersNot = User::whereIn('cargo_id', [6,7])->get();
            $not = array();
            foreach($usersNot as $u)
            {
                array_push($not, $u->id);
            }
            
            $vacaciones = Vacaciones::orderBy('created_at','desc')->where('oficina_id',$oficina_id)->whereNotIn('user_id', $not)->get();
        }
        elseif (Entrust::can('ver-vacaciones-solo')) {

            $userSuperior = auth()->user()->cargo_id;
            $cargosInferiores = new Cargo;

            $colegasInferiores = $cargosInferiores->inferiores($userSuperior, $oficina_id);

            if($colegasInferiores != []) {

                $vacacionesColegas = Vacaciones::orderBy('created_at','desc')->where('user_id', auth()->user()->id)->get();
                $vacacionesInferiores = Vacaciones::orderBy('created_at','desc')->whereIn('user_id', $colegasInferiores)->get();
                $vacaciones = $vacacionesColegas->merge($vacacionesInferiores);
            }
            else {
                $vacaciones = Vacaciones::orderBy('created_at','desc')->where('user_id', auth()->user()->id)->get();
            }
        
        }

        //Preguntamos si tiene permisos para crear ciertos usuarios para llenar la variable users

        if (Entrust::can('crear-vacaciones-todos')) {
            $users = User::where('status', 1)->whereNotIn('categoria_id', [3])->orderBy('first_name', 'asc')->get();
        }
        elseif (Entrust::can('crear-vacaciones-oficina')) {
            $users = User::orderBy('first_name','asc')->where('oficina_id',$oficina_id)->whereNotIn('categoria_id', [3])->where('status', 1)->get();
        }

       // dd($permisos->first()->created_at);
    	return view('vacaciones.list',compact('vacaciones','users'));
    }

    public function create(Request $requests)
    {       
       
        $edit=false;

        if (Entrust::can(['crear-vacaciones-todos', 'crear-vacaciones-oficina'])) {

            $user=User::find($requests->id);

        }else{

            $user=auth()->user();

        }

        $pais=$user->oficina->pais;
        $mesDesde = $pais->mes_vac;
        $vacaciones = 0;
        if(count($user->planilla) == 0){
            $user->acumulado_vacaciones;
        }else{
            /*$mes = substr($user->planilla[count($user->planilla) - 1]->planilla->m_a, 0, -5);
            $año = substr($user->planilla[count($user->planilla) - 1]->planilla->m_a, -4);
            $act = Carbon::now();*/
            
            $calculate = $this->conteoPlanilla($user, $mesDesde);

            //dd($calculate);

            $count = $calculate['count'];
            $dic = $calculate['dic'];

            $vac_dic = $pais->vac_dic;

            //$count = $count == 0 ? 1 : $count;

            $vacaciones = $user->acumulado_vacaciones + ($pais->vacaciones * $count);
            
            if($dic != 0 && $count == 0) {
                $vacaciones = $user->acumulado_vacaciones + ($vac_dic * $dic);
            }

            if($dic != 0 && $count >= 1) {
                $vacaciones = $user->acumulado_vacaciones + ($pais->vacaciones * $count) + ($vac_dic * $dic);
            }

            $user->acumulado_vacaciones = $vacaciones;
        }
   
        //$solicitudes_vacaciones=Vacaciones::whereYear('created_at','=',date('Y'))->where('user_id',$user->id)->get();
        $solicitudes_vacaciones = Vacaciones::whereDate('created_at', '>=', '2019-'.$mesDesde.'-01')
            ->where('user_id', $user->id)
            ->whereIn('aprobacion_directora', [0, 1])
            ->get();
        
        if (count($solicitudes_vacaciones) > 0) {
            
          $user->acumulado_vacaciones=round($user->acumulado_vacaciones-$solicitudes_vacaciones->sum('num_dh'),2);
          //$vacaciones = round($vacaciones - $solicitudes_vacaciones->sum('num_dh'), 2);

          if($user->acumulado_vacaciones<=0)
            return redirect()->route('vacaciones.list')
                ->withErrors("Al parecer al empleado $user->first_name $user->last_name no le quedan mas dias de vacaciones por es te año");         
        }

        $aprobacion_directora=0; 
        
        $permiso = new Permission();
        $rolesAprobAll = $permiso->rolesPermission('aprobar-vacaciones');
        $rolesAprobInf = $permiso->rolesPermission('aprobar-vacaciones-inferior');
        //$roles = $rolesAprobAll->merge($rolesAprobInf);
        
        /**Transaccion por motivos de fix */
        $consult = User::where('id', $user->id)->first();
        $consult->acumulate = $user->acumulado_vacaciones;
        $consult->save();

        return view('vacaciones.create',compact('user','edit','aprobacion_directora', 'rolesAprobAll', 'rolesAprobInf'));   	
    	
    }

    public function store(Request $requests)
    {
    	if($requests->vacaciones_id) {
            $vacaciones=Vacaciones::find($requests->vacaciones_id);
             if ($vacaciones->aprobacion_directora!=0) {
               return redirect()->route('vacaciones.list')
                 ->withErrors("Las solicitudes de vacaciones ya aprobadas no pueden ser editadas"); 
            }
            $vacaciones= $vacaciones->fill($requests->all());
            $mensaje='Solicitud de vacaciones modificada con exito';
        }else{
            $vacaciones=(new Vacaciones)->fill($requests->all());
            $mensaje='Solicitud de vacaciones creada con exito';

        }
        //$vacaciones->dh='dias';
        //$vacaciones->num_dh=count(explode(",", $requests->fechas));        
    	$vacaciones->save();
        $user = $vacaciones->user;
        $user->acumulate = $requests->acumulado - $requests->num_dh;
        $user->save();

        //Notificación de correo electrónico
        $status="<label style='color: green;'><b>Se ha creado una solicitud de vacaciones </b></label>";
        $data = array(
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'contrato' => $user->n_contrato,
            'cargo' => $user->cargo->cargo,
            'oficina' => $user->oficina->oficina,
            'fecha_vacaciones' => explode(',', $vacaciones->fechas),
            'tiempo_vacaciones' => $vacaciones->num_dh,
            'dh' => $vacaciones->dh,
            'status'=>$status,
            'tipo' => 'vacaciones',
        );


        //enviamos un mail a todos los que tengan permiso de enterarse de todas las vacaciones y/o las de su oficina

        $colegas = User::all();
        $coord = DB::table('users')->join('role_user','users.id', '=', 'role_user.user_id')->where('status', 1)->where('role_id', 4)->select('users.*', 'role_user.role_id')->first();

        foreach ($colegas as $key => $colega) {
            
            if($colega->can('notf-crear-vacaciones-todos') || ($colega->can('notf-crear-vacaciones-oficina') && $colega->oficina_id == $requests->oficina_id) || $requests->user_id == $colega->id) {

                Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use ($colega) {
                    $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                    $message->subject('Solicitud de Vacaciones');
                    $message->to($colega->email,"$colega->first_name $colega->last_name");
                });
            }
        }

        Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use ($coord) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Permiso');
            $message->to($coord->email,"$coord->first_name $coord->last_name");
        });

        //coordinadora
        /*$user = Role::where('id', 4)->first();
        $user=$user->users->first();
        //return view('emails.aprobacion.vacaciones_permiso',$data);
        Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use ($user) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Vacaciones');
            $message->to($user->email,"$user->first_name $user->last_name");
        });

        // send mail to directora
        $directora = Role::where('id', 5)->first();
        $directora = $directora->users->first();

        Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use ($directora) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Vacaciones');
            $message->to($directora->email,"$directora->first_name $directora->last_name");
        });

        // send mail to contralora
        $contralora = Role::where('id', 6)->first();
        $contralora = $contralora->users->first();

        Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use ($contralora) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Vacaciones');
            $message->to($contralora->email,"$contralora->first_name $contralora->last_name");
        });*/

    	return redirect()->route('vacaciones.list')
            ->withSuccess($mensaje);    
    	
    }

    public function edit($id)
    {
        $vacaciones=Vacaciones::find($id);        
        $user=$vacaciones->user;        
        $edit=true;  
        $pais=$user->oficina->pais; 

        $user->acumulado_vacaciones = round($user->acumulate, 2);
        $aprobacion_directora=$vacaciones->aprobacion_directora;

        $permiso = new Permission();
        $rolesAprobAll = $permiso->rolesPermission('aprobar-vacaciones');
        $rolesAprobInf = $permiso->rolesPermission('aprobar-vacaciones-inferior');
        //$roles = $rolesAprobAll->merge($rolesAprobInf);

        return view('vacaciones.create',compact('edit','user','vacaciones','aprobacion_directora', 'rolesAprobAll', 'rolesAprobInf')); 

    }

    public function delete($id)
    {
        $vacaciones=Vacaciones::find($id);
        if ($vacaciones->aprobacion_directora!=0) {
            return redirect()->route('vacaciones.list')
            ->withErrors("Las solicitudes de vacaciones ya aprobadas no pueden ser eliminados"); 
           
        }

        $vacaciones->delete();
        return redirect()->route('vacaciones.list')
        ->withSuccess("Solicitud de vacaciones borrada con exito"); 

    }
    
    public function aprobacion(Request $requests, $id)
    {
        $vacaciones=Vacaciones::find($id);
        $user = $vacaciones->user;
       
        $vacaciones->aprobacion_directora=$requests->aprobacion;

        $vacaciones->save();

        //Notificación de correo electrónico
        if ($requests->aprobacion==1) {
            $status="<label style='color: green;'><b>Su solicitud de vacaciones ha sido aprobada con exito </b></label>";   
        }elseif ($requests->aprobacion==2) {
           $status="<label style='color: red;'><b>Su solicitud de vacaciones ha sido rechazada</b></label>";
        }

        $aprobador = auth()->user();
        $coord = DB::table('users')->join('role_user','users.id', '=', 'role_user.user_id')->where('status', 1)->where('role_id', 4)->select('users.*', 'role_user.role_id')->first();
        
        $data = array(
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'contrato' => $user->n_contrato,
            'cargo' => $user->cargo->cargo,
            'oficina' => $user->oficina->oficina,
            'fecha_vacaciones' => explode(',', $vacaciones->fechas),
            'tiempo_vacaciones' => $vacaciones->num_dh,
            'dh' => $vacaciones->dh,
            'status'=>$status,
            'tipo' => 'vacaciones',
        );

        Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use ($aprobador) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Vacaciones');
            $message->to($aprobador->email,"$aprobador->first_name $aprobador->last_name");
            $message->bcc($aprobador->email,"$aprobador->first_name $aprobador->last_name");
        });

        Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use ($user) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Vacaciones');
            $message->to($user->email,"$user->first_name $user->last_name");
            $message->bcc($user->email,"$user->first_name $user->last_name");
        });

        Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use ($coord) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Permiso');
            $message->to($coord->email,"$coord->first_name $coord->last_name");
        });

        // return view('emails.aprobacion.vacaciones_permiso',$data);
        /*$dir = Role::where('id', 5)->first();
        $dir = $dir->users->first();
        Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use ($user, $dir) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Vacaciones');
            $message->to($user->email,"$user->first_name $user->last_name");
            $message->bcc($dir->email,"$dir->first_name $dir->last_name");
        });*/        
        
        if ($requests->aprobacion==1) {
           return redirect()->route('vacaciones.list')
            ->withSuccess("Aprobada la solicitud de vacaciones de:  ".$vacaciones->user->first_name." ".$vacaciones->user->last_name);   
        }elseif ($requests->aprobacion==2) {
           return redirect()->route('vacaciones.list')
            ->withErrors("Rechazada la solicitud de vacaciones de:  ".$vacaciones->user->first_name." ".$vacaciones->user->last_name); 
        }elseif ($requests->aprobacion==3) {
            return redirect()->route('vacaciones.list')
            ->withSuccess("Anulada la solicitud de vacaciones de:  ".$vacaciones->user->first_name." ".$vacaciones->user->last_name);
        }
            
    }

    private function conteoPlanilla($user, $mesDesde)
    {
        $year = 2019;
        $count = 0;
        $dic = 0;

        foreach($user->planilla as $planilla)
        {
            /* if((substr($planilla->planilla->m_a, 0, -5) != 'Diciembre') && (substr($planilla->planilla->m_a, -4) >= $year))
            {
                $mes_planilla = Month::where('month', substr($planilla->planilla->m_a, 0, -5))->first();
                if($mes_planilla->id >= $mesDesde)
                {
                    $count++;   
                }
            } */

            if(substr($planilla->planilla->m_a, 0, -5) != 'Diciembre')
            {
                if(substr($planilla->planilla->m_a, -4) == $year) 
                {
                    $mes_planilla = Month::where('month', substr($planilla->planilla->m_a, 0, -5))->first();

                    if($mes_planilla->id >= $mesDesde)
                    {
                        $count++;   
                    }   
                }
                elseif (substr($planilla->planilla->m_a, -4) > $year) 
                {
                    $count++;
                }
            }
            if((substr($planilla->planilla->m_a, 0, -5) == 'Diciembre') && (substr($planilla->planilla->m_a, -4) >= $year))
            {
                $dic++;
            }
        }

        return [
            'count' => $count,
            'dic' => $dic,
        ];
    }

    public function descargarSolicitudVacaciones($id)
    {
        $solicitud = Vacaciones::find($id);
        $user = $solicitud->user;

        $pdf = PDF::loadView('vacaciones.pdf_solicitud_vacaciones', compact('solicitud','user'));
        
        return $pdf->download("Solicitud de Vacaciones-".date('d-m-Y')."-".$user->first_name." ".$user->last_name.".pdf");
    }
}

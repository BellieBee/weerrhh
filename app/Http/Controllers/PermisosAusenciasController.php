<?php

namespace Vanguard\Http\Controllers;

use Illuminate\Http\Request;

use Vanguard\Http\Requests;
use Entrust;
use Mail;
use DB;
use Vanguard\Permiso;
use Vanguard\Oficina;
use Vanguard\User;
use Vanguard\Role;
use Vanguard\Cargo;
use Vanguard\Motivo_permiso;
use Vanguard\Permission;

class PermisosAusenciasController extends Controller
{
    
	public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-permisosausencias-todos|ver-permisosausencias-oficina|ver-permisosausencias-solo'
        );       
    }
    public function index()
    {    	
    	$permisos=Permiso::orderBy('created_at','asc')->get();
    	$users="";
    	$oficina_id=auth()->user()->oficina_id;
    	
        if (Entrust::can('ver-permisosausencias-todos')) {

            $users=User::whereNotIn('categoria_id', [3])->where('status', 1)->orderBy('first_name','asc')->get();
            $permisos=Permiso::orderBy('created_at','asc')->get();          

        }
        elseif (Entrust::can('ver-permisosausencias-oficina')) {
            $users=User::whereNotIn('categoria_id', [3])->where('status', 1)->where('oficina_id',$oficina_id)->orderBy('first_name','asc')->get();
            $usersNot = User::whereIn('cargo_id', [6,7])->get();
            $not = array();
            foreach($usersNot as $u)
            {
                array_push($not, $u->id);
            }
    	 	$permisos=Permiso::orderBy('created_at','asc')->where('oficina_id',$oficina_id)->whereNotIn('user_id', $not)->get();

    	}
        elseif (Entrust::can('ver-permisosausencias-solo')) {

            $userSuperior = auth()->user()->cargo_id;
            $cargosInferiores = new Cargo;

            $colegasInferiores = $cargosInferiores->inferiores($userSuperior, $oficina_id);

            $permisos = Permiso::orderBy('created_at','asc')->whereIn('user_id', [auth()->user()->id, $colegasInferiores ? $colegasInferiores : ''])->get();        

        }
       // dd($permisos->first()->created_at);
    	return view('permisos.list',compact('permisos','users'));
    }

    public function create(Request $requests)
    {
        $motivo_permiso=Motivo_permiso::get();
        $edit=false;

        if (Entrust::can('crear-permisosausencias')) {

            $user=User::find($requests->id);

        }
        if(Entrust::can('crear-permisosausencias-solo')) {

            $user=auth()->user();

        }
        $aprobacion_coordinadora=0;

        $permiso = new Permission();
        $rolesAprobAll = $permiso->rolesPermission('aprobar-permisosausencias');
        $rolesAprobInf = $permiso->rolesPermission('aprobar-permisosausencias-inferior');
        //dd($rolesAprobInf);
        //$roles = $rolesAprobAll->merge($rolesAprobInf);

        return view('permisos.create',compact('user','edit','motivo_permiso','aprobacion_coordinadora','rolesAprobAll', 'rolesAprobInf'));   	
    	
    }
    public function store(Request $requests)
    {
        if($requests->permiso_id) {
            $permiso=Permiso::find($requests->permiso_id);
            if ($permiso->aprobacion_coordinadora!=0) {
               return redirect()->route('permisos.list')
                 ->withErrors("Los permisos ya aprobados no pueden ser editados"); 
            }
            $permiso= $permiso->fill($requests->all());
            $mensaje="Solicitud de permiso o ausencia modificada con exito";
        }else{
            $permiso= (new Permiso)->fill($requests->all());
            $mensaje="Solicitud de Permiso creada con exito";
        }
                
        $permiso->save();

        //Notificación de correo electrónico
        $user = $permiso->user;
        $data = array(
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'contrato' => $user->n_contrato,
            'cargo' => $user->cargo->cargo,
            'oficina' => $user->oficina->oficina,
            'tipo_permiso' => $permiso->tipo,
            'motivo' => $permiso->motivo,
            'fecha_inicio' => $permiso->fecha_inicio,
            'fecha_fin' => $permiso->fecha_fin,
            'num_dh' => $permiso->num_dh,
            'dh' => $permiso->dh,
            'status' =>$permiso->status,
            'tipo' => 'permiso'
        );

        $colegas = User::all();
        $coord = DB::table('users')->join('role_user','users.id', '=', 'role_user.user_id')->where('status', 1)->where('role_id', 4)->select('users.*', 'role_user.role_id')->first();

        foreach ($colegas as $key => $colega) {
            
            if($colega->can('notf-crear-permisosausencias-todos') || ($colega->can('notf-crear-permisosausencias-oficina') && $colega->oficina_id == $requests->oficina_id) || $requests->user_id == $colega->id) {

                Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use($colega) {
                    $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                    $message->subject('Solicitud de Permiso');
                    $message->to($colega->email,"$colega->first_name $colega->last_name");
                });
            }
        }

        Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use ($coord) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Permiso');
            $message->to($coord->email,"$coord->first_name $coord->last_name");
        });

        /*$user = Role::where('id', 4)->first();
        $user=$user->users->first();

        //return view('emails.aprobacion.vacaciones_permiso',$data);
        Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use($user) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Permiso');
            $message->to($user->email,"$user->first_name $user->last_name");
        });*/

         return redirect()->route('permisos.list')
            ->withSuccess($mensaje);    
        
    }
    public function edit($id)
    {
        $permiso=Permiso::find($id);        
        $user=$permiso->user;
        $motivo_permiso=Motivo_permiso::get();
        $edit=true;
        $aprobacion_coordinadora=$permiso->aprobacion_coordinadora;

        $permiso = new Permission();
        $rolesAprobAll = $permiso->rolesPermission('aprobar-permisosausencias');
        $rolesAprobInf = $permiso->rolesPermission('aprobar-permisosausencias-inferior');

        return view('permisos.create',compact('permiso','edit','user','motivo_permiso','aprobacion_coordinadora', 'rolesAprobAll', 'rolesAprobInf')); 

    }
    public function delete($id)
    {
       $permiso=Permiso::find($id);
       if ($permiso->aprobacion_coordinadora!=0) {
            return redirect()->route('permisos.list')
            ->withErrors("Los permisos ya aprobados no pueden ser eliminados");           
       }
       $permiso->delete();
       return redirect()->route('permisos.list')
            ->withSuccess("Permiso borrado con exito"); 

    }
    public function aprobacion(Request $requests, $id)
    {
        
        $permiso=Permiso::find($id);
        $user = $permiso->user;        
        
        $permiso->aprobacion_coordinadora=$requests->aprobacion;

        //Notificación de correo electrónico

        if ($requests->aprobacion==1) {
            $status="<label style='color: green;'><b>Su solicitud de permiso ha sido aprobada con exito </b></label>";   
        }elseif ($requests->aprobacion==2) {
           $status="<label style='color: red;'><b>Su solicitud de permiso ha sido rechazada</b></label>";
        }

        $aprobador = auth()->user();
        $coord = DB::table('users')->join('role_user','users.id', '=', 'role_user.user_id')->where('status', 1)->where('role_id', 4)->select('users.*', 'role_user.role_id')->first(); 

        $data = array(                
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'contrato' => $user->n_contrato,
            'cargo' => $user->cargo->cargo,
            'oficina' => $user->oficina->oficina,
            'tipo_permiso' => $permiso->tipo,
            'motivo' => $permiso->motivo,
            'fecha_inicio' => $permiso->fecha_inicio,
            'fecha_fin' => $permiso->fecha_fin,
            'num_dh' => $permiso->num_dh,
            'dh' => $permiso->dh,
            'tipo' => 'permiso',
            'status' => $status
        );

        Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use ($aprobador) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Permiso');
            $message->to($aprobador->email,"$aprobador->first_name $aprobador->last_name");
            $message->bcc($aprobador->email,"$aprobador->first_name $aprobador->last_name");
        });

        Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use ($user) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Permiso');
            $message->to($user->email,"$user->first_name $user->last_name");
            $message->bcc($user->email,"$user->first_name $user->last_name");
        }); 

        Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use ($coord) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Permiso');
            $message->to($coord->email,"$coord->first_name $coord->last_name");
        });       

        //return view('emails.aprobacion.vacaciones_permiso',$data);
        /*Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use($user) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Permiso');
            $message->to($user->email,"$user->first_name $user->last_name");
            $message->bcc( auth()->user()->email );
        });*/   
        
        $permiso->save();        

        if ($requests->aprobacion==1) {

            return redirect()->route('permisos.list')
            ->withSuccess("Aprobado El permiso de  ".$user->first_name." ".$user->last_name);   
        
        }elseif ($requests->aprobacion==2) {

           return redirect()->route('permisos.list')
            ->withErrors("Rechazado el permiso de  ".$user->first_name." ".$user->last_name); 
        
        }elseif ($requests->aprobacion==3) {
            return redirect()->route('permisos.list')
            ->withSuccess("Anulado el permiso de  ".$user->first_name." ".$user->last_name);
        } 
       
    
    }
    
    public function reenvio($id)
    {
        $permiso=Permiso::find($id);
        $user = $permiso->user;    
        $status="...";
        if ($permiso->aprobacion_coordinadora==1) {
            $status="<label style='color: green;'><b>Su solicitud de permiso ha sido aprobada con exito </b></label>";   
        }elseif ($permiso->aprobacion_coordinadora==2) {
           $status="<label style='color: red;'><b>Su solicitud de permiso ha sido rechazada</b></label>";
        } 

        $data = array(                
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'contrato' => $user->n_contrato,
            'cargo' => $user->cargo->cargo,
            'oficina' => $user->oficina->oficina,
            'tipo_permiso' => $permiso->tipo,
            'motivo' => $permiso->motivo,
            'fecha_inicio' => $permiso->fecha_inicio,
            'fecha_fin' => $permiso->fecha_fin,
            'num_dh' => $permiso->num_dh,
            'dh' => $permiso->dh,
            'tipo' => 'permiso',
            'status' => $status
        );        

        //return view('emails.aprobacion.vacaciones_permiso',$data);
        Mail::send('emails.aprobacion.vacaciones_permiso', $data, function ($message) use($user) {
            //$message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject("Solicitud de Permiso ( $user->first_name $user->last_name )");
            $message->to($user->email,"$user->first_name $user->last_name");
            $message->bcc( auth()->user()->email );
        }); 

        return json_encode([
            'msj'=>'Correo enviado'
        ]);
    }

}

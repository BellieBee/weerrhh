<?php

namespace Vanguard\Http\Controllers;

use Illuminate\Http\Request;

use Vanguard\Http\Requests;
use Entrust;
use Mail;
use Vanguard\User;
use Vanguard\Viajes;

class ViajesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-viajes-todos|ver-viajes-oficina|ver-viajes-solo');       
    }

    public function index()
    {
        $viajes = Viajes::orderBy('created_at','desc')->get();
    	$users = "";
    	$oficina_id = auth()->user()->oficina_id;
    	
        
        if (Entrust::can('ver-viajes-todos')) {

            $viajes = Viajes::orderBy('created_at','desc')->get();          
        }
        elseif (Entrust::can('ver-viajes-oficina')) {
            //$users = User::orderBy('first_name','asc')->where('oficina_id',$oficina_id)->get();
    	 	$viajes = Viajes::orderBy('created_at','desc')->where('oficina_id',$oficina_id)->get();

    	}elseif (Entrust::can('ver-viajes-solo')) {

            $viajes = Viajes::orderBy('created_at','desc')->where('user_id',auth()->user()->id)->get();    
        }

        //Preguntamos cuales usuarios está autorizado para crear para llenar el select

        if(Entrust::can('crear-viajes-todos')) {

            $users = User::orderBy('first_name', 'asc')->whereNotIn('categoria_id', [3])->where('status', 1)->get();
        }
        elseif(Entrust::can('crear-viajes-oficina')) {

            $users = User::orderBy('first_name','asc')->where('oficina_id',$oficina_id)->whereNotIn('categoria_id', [3])->where('status', 1)->get();
        }

        return view('viajes.list', compact('viajes','users'));
    }

    public function create(Request $requests)
    {       
        $edit = false;

        if (Entrust::can(['crear-viajes-todos','crear-viajes-oficina'])) {
            $user = User::find($requests->id);
        }else{
            $user = auth()->user();
        }

        $pais = $user->oficina->pais;
        $solicitudes_viajes = Viajes::whereYear('created_at','=',date('Y'))->where('user_id',$user->id)->get();
        $aprobacion_directora = 0;      

        return view('viajes.create', compact('user','edit','aprobacion_directora'));   	
    }

    public function store(Request $requests)
    {
    	if($requests->viajes_id) {

            $viajes = Viajes::find($requests->viajes_id);
             if ($viajes->aprobacion_directora != 0) {
               return redirect()->route('viajes.list')
                 ->withErrors("Las solicitudes de viajes ya aprobadas no pueden ser editadas"); 
            }
            $viajes = $viajes->fill($requests->all());
            $mensaje = 'Solicitud de viajes modificada con exito';

        }else{
            $viajes = (new Viajes)->fill($requests->all());
            $mensaje = 'Solicitud de viajes creada con exito';
        }

    	$viajes->save();

    	return redirect()->route('viajes.list')
            ->withSuccess($mensaje);
    }

    public function edit($id)
    {
        $viajes = Viajes::find($id);        
        $user = $viajes->user;        
        $edit = true;  
        $pais = $user->oficina->pais;
        $aprobacion_directora = $viajes->aprobacion_directora;
        return view('viajes.create',compact('edit','user','viajes','aprobacion_directora')); 
    }

    public function delete($id)
    {
        $viajes = Viajes::find($id);
        if ($viajes->aprobacion_directora != 0) {
            return redirect()->route('viajes.list')
            ->withErrors("Las solicitudes de viajes ya aprobadas no pueden ser eliminados"); 
           
        }
        $viajes->delete();
        return redirect()->route('viajes.list')
        ->withSuccess("Solicitud de viajes borrada con exito"); 
    }

    /*public function aprobacion(Request $request, $id)
    {
        $viaje = Viajes::find($id);
        $viaje->aprobacion_directora = $request->
    }*/
}

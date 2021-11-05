<?php

namespace Vanguard\Http\Controllers;

use Illuminate\Http\Request;

use Vanguard\Http\Requests;
use Entrust;
use Vanguard\Oficina;
use Vanguard\User;
use Vanguard\Documentos_recepcion;
use Vanguard\Recepcion;
class RecepcionesController extends Controller
{
    public function index()
    {	
    	$oficina=auth()->user()->oficina;
    	
    	
    	if(Entrust::hasRole('Administradora')){
    		$recepciones=Recepcion::orderBy('id','desc')->where('oficina_id',$oficina->id)->get();
    		$users=User::where('oficina_id',$oficina->id)->get();
    	}else{
    		$recepciones=Recepcion::get();
    		$user="";
    	}
    	//dd($recepciones->first()->documentos);
    	//dd($recepciones);
    	return view('recepciones.list',compact('recepciones','users'));

    }
    public function create(Request $request)
    {
    	
    	$edit=false;
		$user=User::find($request->id);

		return view('recepciones.create',compact('user','edit'));   	
    }
    public function store(Request $request)
    {	

    	if ($request->recepcion_id) {
    		$recepcion=Recepcion::find($request->recepcion_id);
    		$recepcion->documentos()->delete();
    		$mensaje="Recepcion modificada con exito";
    		
    	}else{
    		$recepcion = Recepcion::create($request->all());
    		$mensaje="Recepcion creada con exito" ;   		
    	}
    	foreach ($request->titulo as $key => $value) {
    		
    		if ($value!="") {   		
	    		$documento=new Documentos_recepcion;
	    		$documento->titulo=$request->titulo[$key];
	    		$documento->tipo=$request->tipo[$key];
	    		$documento->descripcion=$request->descripcion[$key];
	    		$documento->prioridad=$request->prioridad[$key];
	    		$documento->recepcion()->associate($recepcion->id);
	    		$documento->save();
	    	}
    	}

    	return redirect()->route('recepciones.list')
            ->withSuccess($mensaje);
    	
    	//$recepcion=(new Recepcion)->fill($request->all());
    	
    }
    public function edit($id)
    {
    	$recepcion=Recepcion::find($id);
    	$edit=true;
    	$user=$recepcion->user;
    	//dd($recepcion->documentos);
		return view('recepciones.create',compact('recepcion','user','edit')); 
    }
    public function delete($id)
    {
       $recepcion=Recepcion::find($id)->delete();
       return redirect()->route('recepciones.list')
            ->withSuccess("Recepcion Borrada con exito");
    }   
    public function recogido($id)
    {
    	$recepcion=Recepcion::find($id)->update(['recogido' => 1]);
    	//dd($recepcion);
    	return redirect()->route('recepciones.list')
            ->withSuccess("Recepcion modifica con exito");
    }
    public function email($id)
    {
    	$recepcion=Recepcion::find($id);
    
    	return view('emails.recepciones.notificacion',compact('recepcion'));
    }
}

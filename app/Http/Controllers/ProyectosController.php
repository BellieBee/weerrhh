<?php

namespace Vanguard\Http\Controllers;

use Illuminate\Http\Request;
use Vanguard\Http\Requests;
use Vanguard\Proyecto;
use Vanguard\Oficina;
use Entrust;

class ProyectosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-proyecto-todos|ver-proyecto-oficina');
    }

    public function index()
    {
    	$oficina_id = auth()->user()->oficina_id;

    	if (Entrust::can('ver-proyecto-todos')) {
    		
    		$proyectos = Proyecto::orderBy('nombre', 'asc')->get();
    	}
    	elseif (Entrust::can('ver-proyecto-oficina')) {
    		
    		$proyectos = Proyecto::orderBy('nombre', 'asc')->where('oficina_id', $oficina_id)->get();
    	}

    	return view('compras.proyectos.index', compact('proyectos'));
    }

    public function create()
    {
    	if (Entrust::can('crear-proyecto-todos')) {
    		$oficinas = Oficina::orderBy('oficina', 'asc')->get();
    	}
    	elseif (Entrust::can('crear-proyecto-oficina')) {
    		$oficinas = Oficina::where('id', auth()->user()->oficina_id)->get();
    	}

    	return view('compras.proyectos.create', compact('oficinas'));
    }

    public function store(Request $request) 
    {
    	$proyecto = (new Proyecto)->fill($request->all());
    	$proyecto->save();

    	return redirect()->route('proyecto.index')
            ->withSuccess("¡Proyecto creado con éxito!");
    }

    public function edit($id)
    {
    	$proyecto = Proyecto::find($id);

    	if (Entrust::can('crear-proyecto-todos')) {
    		$oficinas = Oficina::orderBy('oficina', 'asc')->get();
    	}
    	elseif (Entrust::can('crear-proyecto-oficina')) {
    		$oficinas = Oficina::where('id', auth()->user()->oficina_id)->get();
    	}

    	return view('compras.proyectos.edit', compact('proyecto', 'oficinas'));

    }

    public function update(Request $request)
    {
    	$proyecto = Proyecto::find($request->id);
    	$proyecto = $proyecto->fill($request->all());
    	$proyecto->save();

    	return redirect()->route('proyecto.index')
            ->withSuccess("¡Proyecto actualizado con éxito!");
    }

    public function show($id)
    {
        $proyecto = Proyecto::find($id);

        if (Entrust::can('crear-proyecto-todos')) {
            $oficinas = Oficina::orderBy('oficina', 'asc')->get();
        }
        elseif (Entrust::can('crear-proyecto-oficina')) {
            $oficinas = Oficina::where('id', auth()->user()->oficina_id)->get();
        }

        return view('compras.proyectos.show', compact('proyecto', 'oficinas'));
    }

    public function destroy($id) 
    {
    	$proyecto = Proyecto::find($id);

    	if(count($proyecto->detalle) > 0) 
    	{
    		return redirect()->route('proyecto.index')->withErrors("El Proyecto no puede ser borrado porque está siendo usado en una o más actividades");
    	}
    	else {

        	$proyecto->delete();

       		return redirect()->route('proyecto.index')
            	->withSuccess('El Proyecto ha sido borrado con exito');
        }
    }
}

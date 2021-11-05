<?php

namespace Vanguard\Http\Controllers;

use Illuminate\Http\Request;
use Vanguard\Http\Requests;
use Vanguard\LineaPresupuestaria;
use Vanguard\Oficina;
use Entrust;

class LineasPresupuestariasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-lineapresupuestaria-todos|ver-lineapresupuestaria-oficina');
    }

    public function index() 
    {
    	$oficina_id = auth()->user()->oficina_id;

    	if (Entrust::can('ver-lineapresupuestaria-todos')) {
    		
    		$lineaspresupuestarias = LineaPresupuestaria::orderBy('id', 'desc')->get();
    	}
    	elseif (Entrust::can('ver-lineapresupuestaria-oficina')) {
    		
    		$lineaspresupuestarias = LineaPresupuestaria::orderBy('id', 'desc')->where('oficina_id', $oficina_id)->get();
    	}

    	return view('compras.lineas_presupuestarias.index', compact('lineaspresupuestarias'));
    }

    public function create()
    {
    	if (Entrust::can('crear-lineapresupuestaria-todos')) {
    		$oficinas = Oficina::orderBy('oficina', 'asc')->get();
    	}
    	elseif (Entrust::can('crear-lineapresupuestaria-oficina')) {
    		$oficinas = Oficina::where('id', auth()->user()->oficina_id)->get();
    	}

    	return view('compras.lineas_presupuestarias.create', compact('oficinas'));
    }

    public function store(Request $request) 
    {
    	$linea = (new LineaPresupuestaria())->fill($request->all());
    	$linea->save();

    	return redirect()->route('lineapresupuestaria.index')
            ->withSuccess("¡Línea Presupuestaria creada con éxito!");
    }

    public function edit($id)
    {
    	$linea = LineaPresupuestaria::find($id);

    	if (Entrust::can('crear-lineapresupuestaria-todos')) {
    		$oficinas = Oficina::orderBy('oficina', 'asc')->get();
    	}
    	elseif (Entrust::can('crear-lineapresupuestaria-oficina')) {
    		$oficinas = Oficina::where('id', auth()->user()->oficina_id)->get();
    	}

    	return view('compras.lineas_presupuestarias.edit', compact('linea', 'oficinas'));

    }

    public function update(Request $request)
    {
    	$linea = LineaPresupuestaria::find($request->id);
    	$linea = $linea->fill($request->all());
    	$linea->save();

    	return redirect()->route('lineapresupuestaria.index')
            ->withSuccess("¡Línea Presupuestaria actualizada con éxito!");
    }

    public function show($id)
    {
        $linea = LineaPresupuestaria::find($id);

        if (Entrust::can('crear-lineapresupuestaria-todos')) {
            $oficinas = Oficina::orderBy('oficina', 'asc')->get();
        }
        elseif (Entrust::can('crear-lineapresupuestaria-oficina')) {
            $oficinas = Oficina::where('id', auth()->user()->oficina_id)->get();
        }

        return view('compras.lineas_presupuestarias.show', compact('linea', 'oficinas'));
    }

    public function destroy($id) 
    {
    	$linea = LineaPresupuestaria::find($id);

    	if(count($linea->detalle) > 0) 
    	{
    		return redirect()->route('lineapresupuestaria.index')->withErrors("La Línea Presupuestaria no puede ser borrada porque está siendo usada en una o más actividades");
    	}
    	else {

        	$linea->delete();

       		return redirect()->route('lineapresupuestaria.index')
            	->withSuccess('La Línea Presupuestaria ha sido borrada con exito');
        }
    }
}

<?php

namespace Vanguard\Http\Controllers;

use Vanguard\CentroCosto;
use Vanguard\Oficina;
use Illuminate\Http\Request;
use Vanguard\Http\Requests;
use Entrust;

class CentrosCostoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-centrocosto-todos|ver-centrocosto-oficina');
    }

    public function index() 
    {
    	$oficina_id = auth()->user()->oficina_id;

    	if (Entrust::can('ver-centrocosto-todos')) {
    		
    		$centroscosto = CentroCosto::orderBy('id', 'desc')->get();
    	}
    	elseif (Entrust::can('ver-centrocosto-oficina')) {
    		
    		$centroscosto = CentroCosto::orderBy('id', 'desc')->where('oficina_id', $oficina_id)->get();
    	}

    	return view('compras.centros_costo.index', compact('centroscosto'));
    }

    public function create()
    {
    	if (Entrust::can('crear-centrocosto-todos')) {
    		$oficinas = Oficina::orderBy('oficina', 'asc')->get();
    	}
    	elseif (Entrust::can('crear-centrocosto-oficina')) {
    		$oficinas = Oficina::where('id', auth()->user()->oficina_id)->get();
    	}

    	return view('compras.centros_costo.create', compact('oficinas'));
    }

    public function store(Request $request) 
    {
    	$centro = (new CentroCosto())->fill($request->all());
    	$centro->save();

    	return redirect()->route('centrocosto.index')
            ->withSuccess("¡Centro de Costo creado con éxito!");
    }

    public function edit($id)
    {
    	$centrocosto = CentroCosto::find($id);

    	if (Entrust::can('crear-centrocosto-todos')) {
    		$oficinas = Oficina::orderBy('oficina', 'asc')->get();
    	}
    	elseif (Entrust::can('crear-centrocosto-oficina')) {
    		$oficinas = Oficina::where('id', auth()->user()->oficina_id)->get();
    	}

    	return view('compras.centros_costo.edit', compact('centrocosto', 'oficinas'));

    }

    public function update(Request $request)
    {
    	$centrocosto = CentroCosto::find($request->id);
    	$centrocosto = $centrocosto->fill($request->all());
    	$centrocosto->save();

    	return redirect()->route('centrocosto.index')
            ->withSuccess("¡Centro de Costo actualizado con éxito!");
    }

    public function show($id)
    {
        $centrocosto = CentroCosto::find($id);

        if (Entrust::can('crear-centrocosto-todos')) {
            $oficinas = Oficina::orderBy('oficina', 'asc')->get();
        }
        elseif (Entrust::can('crear-centrocosto-oficina')) {
            $oficinas = Oficina::where('id', auth()->user()->oficina_id)->get();
        }

        return view('compras.centros_costo.show', compact('centrocosto', 'oficinas'));
    }

    public function destroy($id) 
    {
    	$centrocosto = CentroCosto::find($id);

    	if(count($centrocosto->detalle) > 0) 
    	{
    		return redirect()->route('centrocosto.index')->withErrors("El Centro de Costo no puede ser borrado porque está siendo usado en una o más actividades");
    	}
    	else {

        	$centrocosto->delete();

       		return redirect()->route('centrocosto.index')
            	->withSuccess('El Centro de Costo ha sido borrado con exito');
        }
    }
}

<?php

namespace Vanguard\Http\Controllers;

use Vanguard\Proveedor;
use Vanguard\Oficina;
use Vanguard\Pais;
use Illuminate\Http\Request;
use Vanguard\Http\Requests;
use Entrust;

class ProveedoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-proveedor-todos|ver-proveedor-oficina');
    }

    public function index()
    {
    	$oficina_id = auth()->user()->oficina_id;

    	if (Entrust::can('ver-proveedor-todos')) {
    		
    		$proveedores = Proveedor::orderBy('nombre', 'asc')->get();
    	}
    	elseif (Entrust::can('ver-proveedor-oficina')) {
    		
    		$proveedores = Proveedor::orderBy('nombre', 'asc')->where('oficina_id', $oficina_id)->get();
    	}

    	return view('compras.proveedores.index', compact('proveedores'));
    }

    public function create()
    {
    	if (Entrust::can('crear-proveedor-todos')) {
    		$oficinas = Oficina::orderBy('oficina', 'asc')->get();
    	}
    	elseif (Entrust::can('crear-proveedor-oficina')) {
    		$oficinas = Oficina::where('id', auth()->user()->oficina_id)->get();
    	}

    	return view('compras.proveedores.create', compact('oficinas'));
    }

    public function buscarPais(Request $request)
    {
    	$oficina = Oficina::where('id', $request['oficina'])->first();
    	$data = [
    		'id' => $oficina->pais_id,
    		'pais' => $oficina->pais->pais
    	];

    	return response()->json($data);
    }

    public function store(Request $request) 
    {
    	$proveedor = (new Proveedor)->fill($request->all());
    	$proveedor->save();

    	return redirect()->route('proveedor.index')
            ->withSuccess("¡Proveedor creado con éxito!");
    }

    public function edit($id)
    {
    	$proveedor = Proveedor::find($id);

    	if (Entrust::can('crear-proveedor-todos')) {
    		$oficinas = Oficina::orderBy('oficina', 'asc')->get();
    	}
    	elseif (Entrust::can('crear-proveedor-oficina')) {
    		$oficinas = Oficina::where('id', auth()->user()->oficina_id)->get();
    	}

    	return view('compras.proveedores.edit', compact('proveedor', 'oficinas'));

    }

    public function update(Request $request)
    {
    	$proveedor = Proveedor::find($request->id);
    	$proveedor = $proveedor->fill($request->all());
    	$proveedor->save();

    	return redirect()->route('proveedor.index')
            ->withSuccess("¡Proveedor actualizado con éxito!");
    }

    public function show($id)
    {
    	$proveedor = Proveedor::find($id);

    	if (Entrust::can('crear-proveedor-todos')) {
    		$oficinas = Oficina::orderBy('oficina', 'asc')->get();
    	}
    	elseif (Entrust::can('crear-proveedor-oficina')) {
    		$oficinas = Oficina::where('id', auth()->user()->oficina_id)->get();
    	}

    	return view('compras.proveedores.show', compact('proveedor', 'oficinas'));
    }

    public function destroy($id) 
    {
    	$proveedor = Proveedor::find($id);

        if(count($proveedor->actividad) > 0 || count($proveedor->decision) > 0 || count($proveedor->ordencompra) > 0 || count($proveedor->pago) > 0) {

            return redirect()->route('proveedor.index')->withErrors("El Proveedor no puede ser borrado porque está siendo usado en uno o más registros");
        } else {

            $proveedor->delete();

            return redirect()->route('proveedor.index')
            ->withSuccess('El Proveedor ha sido borrado con exito');
        }
    }
}

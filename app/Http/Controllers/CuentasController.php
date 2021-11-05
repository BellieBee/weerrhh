<?php

namespace Vanguard\Http\Controllers;

use Vanguard\CuentaContable;
use Vanguard\Oficina;
use Illuminate\Http\Request;
use Vanguard\Http\Requests;
use Entrust;

class CuentasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-cuenta-todos|ver-cuenta-oficina');
    }

    public function index() 
    {
    	$oficina_id = auth()->user()->oficina_id;

    	if (Entrust::can('ver-cuenta-todos')) {
    		
    		$cuentas = CuentaContable::orderBy('id', 'desc')->get();
    	}
    	elseif (Entrust::can('ver-cuenta-oficina')) {
    		
    		$cuentas = CuentaContable::orderBy('id', 'desc')->where('oficina_id', $oficina_id)->get();
    	}

    	return view('compras.cuentas.index', compact('cuentas'));
    }

    public function create()
    {
    	if (Entrust::can('crear-cuenta-todos')) {
    		$oficinas = Oficina::orderBy('oficina', 'asc')->get();
    	}
    	elseif (Entrust::can('crear-cuenta-oficina')) {
    		$oficinas = Oficina::where('id', auth()->user()->oficina_id)->get();
    	}

    	return view('compras.cuentas.create', compact('oficinas'));
    }

    public function store(Request $request) 
    {
    	$cuenta = (new CuentaContable)->fill($request->all());
    	$cuenta->save();

    	return redirect()->route('cuenta.index')
            ->withSuccess("¡Cuenta Contable creada con éxito!");
    }

    public function edit($id)
    {
    	$cuenta = CuentaContable::find($id);

    	if (Entrust::can('crear-cuenta-todos')) {
    		$oficinas = Oficina::orderBy('oficina', 'asc')->get();
    	}
    	elseif (Entrust::can('crear-cuenta-oficina')) {
    		$oficinas = Oficina::where('id', auth()->user()->oficina_id)->get();
    	}

    	return view('compras.cuentas.edit', compact('cuenta', 'oficinas'));

    }

    public function update(Request $request)
    {
    	$cuenta = CuentaContable::find($request->id);
    	$cuenta = $cuenta->fill($request->all());
    	$cuenta->save();

    	return redirect()->route('cuenta.index')
            ->withSuccess("¡Cuenta Contable actualizado con éxito!");
    }

    public function show($id)
    {
        $cuenta = CuentaContable::find($id);

        if (Entrust::can('crear-cuenta-todos')) {
            $oficinas = Oficina::orderBy('oficina', 'asc')->get();
        }
        elseif (Entrust::can('crear-cuenta-oficina')) {
            $oficinas = Oficina::where('id', auth()->user()->oficina_id)->get();
        }

        return view('compras.cuentas.show', compact('cuenta', 'oficinas'));
    }

    public function destroy($id) 
    {
    	$cuenta = CuentaContable::find($id);

    	if(count($cuenta->detalle) > 0) 
    	{
    		return redirect()->route('cuenta.index')->withErrors("La Cuenta Contable no puede ser borrada porque está siendo usada en una o más actividades");
    	}
    	else {

        	$cuenta->delete();

       		return redirect()->route('cuenta.index')
            	->withSuccess('La Cuenta Contable ha sido borrada con exito');
        }
    }
}

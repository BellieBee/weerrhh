<?php

namespace Vanguard\Http\Controllers;

use Illuminate\Http\Request;
use Vanguard\Http\Requests;
use Vanguard\OrdenCompra;
use Vanguard\Actividad;
use Vanguard\Decision;
use Vanguard\Contrato;
use Vanguard\PagoCompra;
use Vanguard\Proveedor;
use Vanguard\CuentaContable;
use Vanguard\User;
use Vanguard\Oficina;
use Vanguard\Categoria;
use Vanguard\Cargo;
use Entrust;
use Storage;
use Carbon\Carbon;
use DB;
use PDF;


class OrdenComprasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-ordencompra-todos|ver-ordencompra-oficina|ver-ordencompra-solo');
    }

    public function index()
    {
    	$oficina_id = auth()->user()->oficina_id;

    	if (Entrust::can('ver-ordencompra-todos')) {
    		
    		$ordenes_compra = OrdenCompra::orderBy('fecha', 'desc')->get();
    	}
    	elseif (Entrust::can('ver-ordencompra-oficina')) {
    		
    		$ordenes_compra = OrdenCompra::orderBy('fecha', 'desc')->where('oficina_id', $oficina_id)->get();
    	}
    	elseif (Entrust::can('ver-ordencompra-solo')) {
    		
    		$userSuperior = auth()->user()->cargo_id;
            $cargosInferiores = new Cargo;
            $colegasInferiores = $cargosInferiores->inferiores($userSuperior, $oficina_id);

            $ordenes_compra = OrdenCompra::orderBy('fecha', 'desc')->whereIn('solicitante_id', [auth()->user()->id, $colegasInferiores ? $colegasInferiores : ''])->get();
    	}

    	return view('compras.ordenes_compra.index', compact('ordenes_compra'));
    }

    public function create(Request $request, $id)
    {
    	//$actividad = $request->actividad;
    	$user = auth()->user();
        $correlativo = $user->oficina->inicial_correlativo.'-'.$user->oficina->correlativo_solicitud;
        $correlativo = $correlativo.'-'.Carbon::now()->year;
        if ($request->actividad == 1) {

        	$actividad = Actividad::find($id);
        	$proveedor = $actividad->proveedor_id != 0 ? Proveedor::find($actividad->proveedor_id) : false;
            $provUser = $actividad->proveedor_user_id != null ? User::find($actividad->proveedor_user_id) : false;
        	$cuenta = CuentaContable::find($actividad->cuenta_id);
        	$monto = $actividad->monto;
        	$monto_usd = $actividad->monto_usd;
        	$monto_sk = $actividad->monto_sk;
        	$es_actividad = 1;

        	return view('compras.ordenes_compra.create', compact('actividad', 'user', 'correlativo', 'proveedor', 'provUser' , 'cuenta', 'monto', 'monto_usd', 'monto_sk', 'es_actividad'));
        }
        else {

        	$decision = Decision::find($id);
        	$proveedor = $decision->proveedor_id != 0 ? Proveedor::find($decision->proveedor_id) : false;
            $provUser = $decision->proveedor_user_id != null ? User::find($decision->proveedor_user_id) : false;
        	$cuentas = CuentaContable::orderBy('id', 'desc')->where('oficina_id', $user->oficina_id)->get();
        	$es_actividad = 0;

        	return view('compras.ordenes_compra.create', compact('decision', 'user', 'correlativo', 'proveedor', 'provUser', 'cuentas', 'es_actividad'));
        }
    }

    public function store(Request $request) 
    {
    	$ordencompra = new OrdenCompra;
    	$ordencompra->correlativo = $request->correlativo;
    	if($request->actividad_id) {
    		$ordencompra->actividad_id = $request->actividad_id;
    	}
    	else {
    		$ordencompra->decision_id = $request->decision_id;
    	}
    	$ordencompra->oficina_id = $request->oficina_id;
    	$ordencompra->fecha = date('Y-m-d');
    	$ordencompra->solicitante_id = $request->solicitante_id;
    	$ordencompra->proveedor_id = $request->proveedor_id;
        if($request->proveedor_user_id != 0) {
            $ordencompra->proveedor_user_id = $request->proveedor_user_id;
        } 
        else {
            $ordencompra->proveedor_user_id = null;
        }
    	$ordencompra->descripcion = $request->descripcion;
    	$ordencompra->monto = $request->monto;
    	$ordencompra->monto_usd = $request->monto_usd;
    	$ordencompra->monto_sk = $request->monto_sk;
    	$ordencompra->aprobacion_solicitante = 0;
    	$ordencompra->aprobacion_1 = 0;
    	$ordencompra->aprobacion_2 = 0;
    	$ordencompra->save();

        $correlativo = $ordencompra->oficina->correlativo_compra;
        DB::table('oficinas')->where('id', $request->oficina_id)->update(['correlativo_compra' => $correlativo+1]);

    	return redirect()->route('ordencompra.index')->withSuccess("Orden de Compra creada con éxito");
    }

    public function edit($id)
    {
    	$ordencompra = OrdenCompra::find($id);
    	$cuentas = CuentaContable::where('oficina_id', auth()->user()->oficina_id)->get();

    	return view('compras.ordenes_compra.edit', compact('ordencompra', 'cuentas'));
    }

    public function update(Request $request)
    {
    	$ordencompra = OrdenCompra::find($request->id);
    	$ordencompra->descripcion = $request->descripcion;
    	$ordencompra->monto = $request->monto;
    	$ordencompra->monto_usd = $request->monto_usd;
    	$ordencompra->monto_sk = $request->monto_sk;
    	$ordencompra->save();

    	return redirect()->route('ordencompra.index')->withSuccess("Orden de Compra actualizada con éxito");
    }

    public function show($id)
    {
    	$ordencompra = OrdenCompra::find($id);
    	$cuentas = CuentaContable::orderBy('id', 'desc')->where('oficina_id', auth()->user()->oficina_id)->get();
        $oficinas = Oficina::get();
        $categoria = Categoria::where('categoria','Consultor')->first()->id;
      
        $users = User::where('categoria_id', $categoria)->get();

        if(Entrust::can('crear-contratos-oficina')) {
            
            $oficinas = $oficinas->where('id', auth()->user()->oficina->id); 
        }

    	return view('compras.ordenes_compra.show', compact('ordencompra', 'cuentas', 'oficinas', 'users'));
    }

    public function aprobacion(Request $request, $id)
    {
    	$ordencompra = OrdenCompra::find($id);

    	if($request->aprobacion_solicitante) {

    		$ordencompra->aprobacion_solicitante = $request->aprobacion_solicitante;
    	}
    	elseif($request->aprobacion_1) {

            $ordencompra->aprobacion_1 = $request->aprobacion_1;
            $ordencompra->fecha_aprobacion_1 = date('Y-m-d');
            $ordencompra->aprobador_1_id = auth()->user()->id;
        }
        elseif ($request->aprobacion_2) {

            $ordencompra->aprobacion_2 = $request->aprobacion_2;
            $ordencompra->fecha_aprobacion_2 = date('Y-m-d');
            $ordencompra->aprobador_2_id = auth()->user()->id;
        }

        $ordencompra->save();

        if($request->aprobacion_solicitante == 1) {

            return redirect()->route('ordencompra.index')->withSuccess("La Solicitud de Orden de Compra de ".$ordencompra->solicitante->first_name." ".$ordencompra->solicitante->last_name." ha recibido la aprobación del solicitante");
        }
        if($request->aprobacion_solicitante == 2) {

            return redirect()->route('ordencompra.index')->withErrors("La Solicitud de Orden de Compra de ".$ordencompra->solicitante->first_name." ".$ordencompra->solicitante->last_name." ha recibido el rechazo del solicitante");
        }
        if($request->aprobacion_solicitante == 3) {

            return redirect()->route('ordencompra.index')->withSuccess("La Solicitud de Orden de Compra de ".$ordencompra->solicitante->first_name." ".$ordencompra->solicitante->last_name." ha recibido la anulación del solicitante");
        }
        if($request->aprobacion_1 == 1) {

            return redirect()->route('ordencompra.index')->withSuccess("La Solicitud de Orden de Compra de ".$ordencompra->solicitante->first_name." ".$ordencompra->solicitante->last_name." ha recibido su primera aprobación");
        }
        if ($request->aprobacion_1 == 2) {
            
            return redirect()->route('ordencompra.index')->withErrors("La Solicitud de Orden de Compra de ".$ordencompra->solicitante->first_name." ".$ordencompra->solicitante->last_name." ha recibido su primer rechazo");
        }
        if($request->aprobacion_1 == 3) {

            return redirect()->route('ordencompra.index')->withSuccess("La Solicitud de Orden de Compra de ".$ordencompra->solicitante->first_name." ".$ordencompra->solicitante->last_name." ha recibido su primera anulación");
        }
        if($request->aprobacion_2 == 1) {

            return redirect()->route('ordencompra.index')->withSuccess("La Solicitud de Orden de Compra de ".$ordencompra->solicitante->first_name." ".$ordencompra->solicitante->last_name." ha recibido su segunda aprobación");
        }
        if ($request->aprobacion_2 == 2) {
            
            return redirect()->route('ordencompra.index')->withErrors("La Solicitud de Orden de Compra de ".$ordencompra->solicitante->first_name." ".$ordencompra->solicitante->last_name." ha recibido su segundo rechazo");
        }
        if($request->aprobacion_2 == 3) {

            return redirect()->route('ordencompra.index')->withSuccess("La Solicitud de Orden de Compra de ".$ordencompra->solicitante->first_name." ".$ordencompra->solicitante->last_name." ha recibido su segunda anulación");
        }
    }

    public function destroy($id)
    {
        $ordencompra = OrdenCompra::find($id);

        $pago = PagoCompra::where('orden_compra_id', $id)->first();

        if($pago != null) {

            $pago_delete = PagoCompra::find($pago->id);
            $pago_delete->delete();
        }
        else {

            $contrato = Contrato::where('ordencompra_id', $id)->first();

            if($contrato != null) {
           
                $pago_cont = PagoCompra::where('contrato_id', $contrato->id)->first();

                if ($pago_cont != null) {
               
                    $pago_cont_delete = PagoCompra::find($pago_cont->id);
                    $pago_cont_delete->delete();
                }

                $contrato_delete = Contrato::find($contrato->id);
                $documentos = $contrato_delete->documentos;
       
                foreach ($documentos as $documento) {
                    if (file_exists(public_path() . '/documentos/'.$documento->documento)) {
                        Storage::disk('documentos')->delete($documento->documento);
                        $documento->delete();
                    }
                }      
       
                $contrato_delete->delete();
            }
        } 

        $ordencompra->delete(); 

        return redirect()->route('ordencompra.index') ->withSuccess('Orden de Compra borrada con exito');
    }

    public function download($id)
    {
    	$ordencompra = OrdenCompra::find($id);

        //return view('compras.ordenes_compra.pdf_orden_compra', compact('ordencompra'));

    	$pdf = PDF::loadView('compras.ordenes_compra.pdf_orden_compra', compact('ordencompra'));
        
        return $pdf->download("Orden de Compra-".date('d-m-Y')."-".$ordencompra->solicitante->first_name." ".$ordencompra->solicitante->last_name.".pdf");

    }
}

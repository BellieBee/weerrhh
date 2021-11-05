<?php

namespace Vanguard\Http\Controllers;

use Illuminate\Http\Request;
use Vanguard\Http\Requests;
use Vanguard\PagoCompra;
use Vanguard\Actividad;
use Vanguard\OrdenCompra;
use Vanguard\Contrato;
use Vanguard\Proveedor;
use Vanguard\TipoPago;
use Vanguard\User;
use Vanguard\CuentaContable;
use Vanguard\Permission;
use Entrust;
use Carbon\Carbon;
use DB;
use PDF;
use Mail;

class PagosComprasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-pago-todos|ver-pago-oficina|ver-pago-solo');
    }

    public function index()
    {
    	$oficina_id = auth()->user()->oficina_id;

    	if(Entrust::can('ver-pago-todos')) {

    		$pagos = PagoCompra::orderBy('fecha', 'desc')->get();
    	}
    	elseif (Entrust::can('ver-pago-oficina')) {

    		$pagos = PagoCompra::orderBy('fecha', 'desc')->where('oficina_id', $oficina_id)->get();
    	}
    	elseif (Entrust::can('ver-pago-solo')) {
    		
    		$pagos = PagoCompra::orderBy('fecha', 'desc')->where('user_id', auth()->user()->id)->get();
    	}

    	return view('compras.pagos.index', compact('pagos'));
    }

    public function create(Request $request, $id)
    {
    	$tiposPagos = TipoPago::get();

    	if ($request->actividad == 1) {
    		
    		$actividad = Actividad::find($id);
    		$oficina = $actividad->oficina;
            $imp_deduc = floatval($actividad->monto) * (floatval($oficina->imp_pago_compra) / 100);
            $isr_deduc = floatval($actividad->monto) * (floatval($oficina->isr_pago_compra) / 100);
            $monto_total = (floatval($actividad->monto) + $imp_deduc) - $isr_deduc;
    		$monto_usd = $actividad->monto_usd;
    		$monto_sk = $actividad->monto_sk;
    		$es_actividad = 1;

    		return view('compras.pagos.create', compact('tiposPagos', 'actividad', 'oficina', 'monto_total', 'monto_usd', 'monto_sk', 'es_actividad'));		
    	}
    	elseif ($request->actividad == 0) {
    		
    		$ordencompra = OrdenCompra::find($id);
    		$oficina = $ordencompra->oficina;
            $imp_deduc = floatval($ordencompra->monto) * (floatval($oficina->imp_pago_compra) / 100);
            $isr_deduc = floatval($ordencompra->monto) * (floatval($oficina->isr_pago_compra) / 100);
            $monto_total = (floatval($ordencompra->monto) + $imp_deduc) - $isr_deduc;
    		$monto_usd = $ordencompra->monto_usd;
    		$monto_sk = $ordencompra->monto_sk;
    		$es_actividad = 0;

    		return view('compras.pagos.create', compact('tiposPagos', 'ordencompra', 'oficina', 'monto_total', 'monto_usd', 'monto_sk', 'es_actividad'));
    	}
    	elseif ($request->actividad == 2) {
    		
    		$contrato = Contrato::find($id);
    		$oficina = $contrato->oficina;
    		$users = User::where('status', 1)->get();
            $proveedores = Proveedor::orderBy('razon_social', 'asc')->where('oficina_id', auth()->user()->oficina_id)->get();
    		$cuentas = CuentaContable::orderBy('id', 'desc')->where('oficina_id', auth()->user()->oficina_id)->get();
            $imp_deduc = floatval($contrato->monto_total) * (floatval($oficina->imp_pago_compra) / 100);
            $isr_deduc = floatval($contrato->monto) * (floatval($oficina->isr_pago_compra) / 100);
            $monto_total = (floatval($contrato->monto_total) + $imp_deduc) - $isr_deduc;
    		$monto_usd = floatval($contrato->monto_total) / floatval($oficina->pais->tasa_conv_usd);
    		$monto_sk = floatval($contrato->monto_total) / floatval($oficina->pais->tasa_conv_corona);
    		$es_actividad = 2;

    		return view('compras.pagos.create', compact('tiposPagos', 'contrato', 'oficina', 'users', 'proveedores', 'cuentas', 'monto_total', 'monto_usd', 'monto_sk', 'es_actividad'));
    	}
    }

    public function store(Request $request) 
    {
        if ($request->tipopago_id == '') {
            
            return redirect()->route('pago.index')->withErrors("ERROR. Campo Tipo de Pago vacío. Debe llenar el campo.");
        }
    	$pago = new PagoCompra;
    	if($request->actividad_id) {
    		$pago->actividad_id = $request->actividad_id;
    	} 
    	elseif ($request->ordencompra_id) {
    		$pago->orden_compra_id = $request->ordencompra_id;
    	}
    	elseif ($request->contrato_id) {
    		$pago->contrato_id = $request->contrato_id;
    	}
    	$pago->oficina_id = $request->oficina_id;
    	$pago->tipopago_id = $request->tipopago_id;
        $pago->proveedor_id = $request->proveedor_id;
        $pago->proveedor_user_id = $request->proveedor_id != 0 ? null : $request->proveedor_user_id;
    	$pago->tramitante_id = $request->tramitante_id;
    	$pago->user_id = $request->user_id;
    	$pago->concepto = $request->concepto;
    	$pago->factura = $request->factura;
    	//$pago->centro_costo = $request->centro_costo;
    	//$pago->linea_presupuesta = $request->linea_presupuesta;
        //$pago->moneda_pago = $request->moneda_pago;
        $pago->exo_imp = $request->exo_imp = 'on' ? 1 : 0;
    	$pago->monto = $request->monto;
        $pago->impuesto = $request->impuesto;
        $pago->isr = $request->isr;
        $pago->monto_total = $request->monto_total;
    	$pago->monto_usd = $request->monto_usd;
    	$pago->monto_sk = $request->monto_sk;
    	$pago->fecha = date('Y-m-d');
        $pago->revision = 0;
    	$pago->save();

    	return redirect()->route('pago.index')->withSuccess("Orden de Pago creada con éxito");
    }

    public function edit($id)
    {
    	$pago = PagoCompra::find($id);
    	$tiposPagos = TipoPago::get();

        $permiso = new Permission();
        //permisos de actividad
        $rolesAprobAct1 = $permiso->rolesPermission('aprobar-actividad-1');
        $rolesAprobAct2 = $permiso->rolesPermission('aprobar-actividad-2');
        $rolesAprobAct1Inf = $permiso->rolesPermission('aprobar-actividad-1-inferior');
        $rolesAprobAct2Inf = $permiso->rolesPermission('aprobar-actividad-2-inferior');
        //permisos pago
        $rolesAprobPa = $permiso->rolesPermission('aprobar-pago');
        $rolesAprobPaInf = $permiso->rolesPermission('aprobar-pago-inferior');

        if ($pago->orden_compra_id != null) {
            //permisos orden de compra
            $rolesAprobOrd1 = $permiso->rolesPermission('aprobar-ordencompra-1');
            $rolesAprobOrd2 = $permiso->rolesPermission('aprobar-ordencompra-2');
            $rolesAprobOrd1Inf = $permiso->rolesPermission('aprobar-ordencompra-1-inferior');
            $rolesAprobOrd2Inf = $permiso->rolesPermission('aprobar-ordencompra-2-inferior');

            return view('compras.pagos.edit', compact('pago', 'tiposPagos', 'rolesAprobAct1', 'rolesAprobAct1Inf', 'rolesAprobAct2', 'rolesAprobAct2Inf', 'rolesAprobOrd1', 'rolesAprobOrd1Inf', 'rolesAprobOrd2', 'rolesAprobOrd2Inf', 'rolesAprobPa', 'rolesAprobPaInf'));
        }
    	elseif($pago->contrato_id != null) {

    		$users = User::where('status', 1)->get();
            $proveedores = Proveedor::orderBy('razon_social', 'asc')->where('oficina_id', auth()->user()->oficina_id)->get();

            //permisos decision
            $rolesAprobDec1 = $permiso->rolesPermission('aprobar-decision-1');
            $rolesAprobDec2 = $permiso->rolesPermission('aprobar-decision-2');
            $rolesAprobDec1Inf = $permiso->rolesPermission('aprobar-decision-1-inferior');
            $rolesAprobDec2Inf = $permiso->rolesPermission('aprobar-decision-2-inferior');
            //permisos orden de compra
            $rolesAprobOrd1 = $permiso->rolesPermission('aprobar-ordencompra-1');
            $rolesAprobOrd2 = $permiso->rolesPermission('aprobar-ordencompra-2');
            $rolesAprobOrd1Inf = $permiso->rolesPermission('aprobar-ordencompra-1-inferior');
            $rolesAprobOrd2Inf = $permiso->rolesPermission('aprobar-ordencompra-2-inferior');
            //permisos contrato
            $rolesAprobConCoord = $permiso->rolesPermission('confirmar-contratos');
            $rolesAprobConDir = $permiso->rolesPermission('aprobar-contratos');

    		return view('compras.pagos.edit', compact('pago', 'tiposPagos', 'users', 'proveedores', 'rolesAprobAct1', 'rolesAprobAct1Inf', 'rolesAprobAct2', 'rolesAprobAct2Inf', 'rolesAprobDec1', 'rolesAprobDec1Inf', 'rolesAprobDec2', 'rolesAprobDec2Inf', 'rolesAprobOrd1', 'rolesAprobOrd1Inf', 'rolesAprobOrd2', 'rolesAprobOrd2Inf', 'rolesAprobConCoord', 'rolesAprobConDir', 'rolesAprobPa', 'rolesAprobPaInf'));
    	}
    	else {

    		return view('compras.pagos.edit', compact('pago', 'tiposPagos', 'rolesAprobAct1', 'rolesAprobAct1Inf', 'rolesAprobAct2', 'rolesAprobAct2Inf', 'rolesAprobPa', 'rolesAprobPaInf'));
    	}
    }

    public function update(Request $request)
    {
        //dd($request);
        $pago = PagoCompra::find($request->id);
        $pago->tipopago_id = $request->tipopago_id;
        if ($pago->contrato_id != null) {
            $pago->proveedor_id = $request->proveedor_id;
            $pago->proveedor_user_id = $request->proveedor_id != 0 ? null : $request->proveedor_user_id;
        }
        $pago->concepto = $request->concepto;
        $pago->factura = $request->factura;
        //$pago->centro_costo = $request->centro_costo;
        //$pago->linea_presupuesta = $request->linea_presupuesta;
        //$pago->moneda_pago = $request->moneda_pago;
        $pago->exo_imp = $request->exo_imp = 'on' ? 1 : 0;
        $pago->monto = $request->monto;
        $pago->impuesto = $request->impuesto;
        $pago->isr = $request->isr;
        $pago->monto_total = $request->monto_total;
        $pago->monto_usd = $request->monto_usd;
        $pago->monto_sk = $request->monto_sk;
        $pago->save();

        if($request->actividad_aprobacion_1 || $request->actividad_aprobacion_2 || $request->decision_aprobacion_1 || 
            $request->decision_aprobacion_2 || $request->orden_aprobacion_sol || $request->orden_aprobacion_1 || 
            $request->orden_aprobacion_2 || $request->contrato_aprobacion_coord || $request->contrato_aprobacion_dir) {
            if ($pago->actividad_id != null) {
                $actividad = $pago->actividad;
            }
            elseif ($pago->orden_compra_id != null) {
                $ordencompra = $pago->ordencompra;
                $actividad = $ordencompra->actividad;
                
                $ordencompra->aprobacion_solicitante = $request->orden_aprobacion_sol ? $request->orden_aprobacion_sol : $ordencompra->aprobacion_solicitante; 
                $ordencompra->aprobacion_1 = $request->orden_aprobacion_1 ? $request->orden_aprobacion_1 : $ordencompra->aprobacion_1;
                $ordencompra->aprobador_1_id = $request->orden_aprobacion_1 ? auth()->user()->id : $ordencompra->aprobador_1_id;
                $ordencompra->fecha_aprobacion_1 = $request->orden_aprobacion_1 ? date('Y-m-d') : $ordencompra->fecha_aprobacion_1;
                $ordencompra->aprobacion_2 = $request->orden_aprobacion_2 ? $request->orden_aprobacion_2 : $ordencompra->aprobacion_2;
                $ordencompra->aprobador_2_id = $request->orden_aprobacion_2 ? auth()->user()->id : $ordencompra->aprobador_2_id;
                $ordencompra->fecha_aprobacion_2 = $request->orden_aprobacion_2 ? date('Y-m-d') : $ordencompra->fecha_aprobacion_2;
                $ordencompra->save();
            }
            else {
                $contrato = $pago->contrato;
                $ordencompra = $contrato->ordencompra;
                $decision = $ordencompra->decision;
                $actividad = $decision->actividad;
                
                $decision->aprobacion_1 = $request->decision_aprobacion_1 ? $request->decision_aprobacion_1 : $decision->aprobacion_1;
                $decision->aprobacion_2 = $request->decision_aprobacion_2 ? $request->decision_aprobacion_2 : $decision->aprobacion_2;
                $decision->aprobador_1_id = $request->decision_aprobacion_1 ? auth()->user()->id : $decision->aprobador_1_id;
                $decision->aprobador_2_id = $request->decision_aprobacion_2 ? auth()->user()->id : $decision->aprobador_2_id;
                $decision->fecha_aprobacion_1 = $request->decision_aprobacion_1 ? date('Y-m-d') : $decision->fecha_aprobacion_1;
                $decision->fecha_aprobacion_2 = $request->decision_aprobacion_2 ? date('Y-m-d') : $decision->fecha_aprobacion_2;
                $decision->save();

                $ordencompra->aprobacion_solicitante = $request->orden_aprobacion_sol ? $request->orden_aprobacion_sol : $ordencompra->aprobacion_solicitante; 
                $ordencompra->aprobacion_1 = $request->orden_aprobacion_1 ? $request->orden_aprobacion_1 : $ordencompra->aprobacion_1;
                $ordencompra->aprobador_1_id = $request->orden_aprobacion_1 ? auth()->user()->id : $ordencompra->aprobador_1_id;
                $ordencompra->fecha_aprobacion_1 = $request->orden_aprobacion_1 ? date('Y-m-d') : $ordencompra->fecha_aprobacion_1;
                $ordencompra->aprobacion_2 = $request->orden_aprobacion_2 ? $request->orden_aprobacion_2 : $ordencompra->aprobacion_2;
                $ordencompra->aprobador_2_id = $request->orden_aprobacion_2 ? auth()->user()->id : $ordencompra->aprobador_2_id;
                $ordencompra->fecha_aprobacion_2 = $request->orden_aprobacion_2 ? date('Y-m-d') : $ordencompra->fecha_aprobacion_2;
                $ordencompra->save();

                $contrato->aprobacion_coordinadora = $request->contrato_aprobacion_coord ? $request->contrato_aprobacion_coord : $contrato->aprobacion_coordinadora;
                $contrato->coordinadora_id = $request->contrato_aprobacion_coord ? auth()->user()->id : $contrato->coordinadora_id;
                $contrato->fecha_aprobacion_coordinadora = $request->contrato_aprobacion_coord ? date('Y-m-d') : $contrato->fecha_aprobacion_coordinadora;
                $contrato->aprobacion_directora = $request->contrato_aprobacion_dir ? $request->contrato_aprobacion_dir : $contrato->aprobacion_directora;
                $contrato->directora_id = $request->contrato_aprobacion_dir ? auth()->user()->id : $contrato->directora_id;
                $contrato->fecha_aprobacion_directora = $request->contrato_aprobacion_dir ? date('Y-m-d') : $contrato->fecha_aprobacion_directora;
                if ($request->contrato_anular) {
                    $contrato->status = 4;
                }
                $contrato->save();
            }

            $actividad->aprobacion_1 = $request->actividad_aprobacion_1 ? $request->actividad_aprobacion_1 : $actividad->aprobacion_1;
            $actividad->aprobacion_2 = $request->actividad_aprobacion_2 ? $request->actividad_aprobacion_2 : $actividad->aprobacion_2;
            $actividad->fecha_aprobacion_1 = $request->actividad_aprobacion_1 ? date('Y-m-d') : $actividad->fecha_aprobacion_1;
            $actividad->fecha_aprobacion_2 = $request->actividad_aprobacion_2 ? date('Y-m-d') : $actividad->fecha_aprobacion_2;
            $actividad->save();

        }

        return redirect()->route('pago.index')->withSuccess("Orden de Pago actualizada con éxito.");
    }

    public function show($id)
    {
        $pago = PagoCompra::find($id);
        $tiposPagos = TipoPago::get();

        if($pago->contrato_id != null) {

            $users = User::where('status', 1)->get();
            //$cuentas = CuentaContable::where('oficina_id', auth()->user()->oficina_id)->get();
            $proveedores = Proveedor::orderBy('razon_social', 'asc')->where('oficina_id', auth()->user()->oficina_id)->get();

            return view('compras.pagos.show', compact('pago', 'tiposPagos', 'users', 'proveedores'));
        }
        else {

            return view('compras.pagos.show', compact('pago', 'tiposPagos'));
        }
    }

    public function aprobacion(Request $request, $id)
    {
        $pago = PagoCompra::find($id);
        $user = $pago->user;

        if($request->aprobacion == 1) {

            $status="<label style='color: green;'><b>Su solicitud Pago de Compra ha sido aprobada con exito </b></label>";
            //return redirect()->route('pago.index')->withSuccess("La Solicitud de Pago de ".$pago->user->first_name." ".$pago->user->last_name." ha pasado exitosamente la revisión");
        }
        elseif($request->aprobacion == 2) {
            $status="<label style='color: red;'><b>Su solicitud Pago de Compra ha sido rechazada</b></label>";
            //return redirect()->route('pago.index')->withErrors("La Solicitud de Pago de ".$pago->user->first_name." ".$pago->user->last_name." ha sido rechazada");
        }
        elseif($request->aprobacion == 3) {
            $status="<label style='color: blue;'><b>Su solicitud Pago de Compra ha sido anulada</b></label>";
            //return redirect()->route('pago.index')->withSuccess("La Solicitud de Pago de ".$pago->user->first_name." ".$pago->user->last_name." ha sido anulada");
        }

        $pago->revision = $request->aprobacion;
        $pago->aprobador_id = auth()->user()->id;
        $pago->fecha_revision = date('Y-m-d');
        $pago->save();

        $tramitante = $pago->tramitante;

        $superior_id = $user->cargo->superior_id;
        $superior = User::where('cargo_id', $superior_id)
            ->where('status', 1);

        if (count($superior) > 1) {
            
            $superior = $superior->where('oficina_id', $pago->oficina_id)->first();
        }
        else {

            $superior = $superior->first();
        }

        //dd($superior);

        $direcFin = User::where('status', 1)->whereHas('roles', function($q) {
            $q->whereIn('name', ['DirectorFinanciero']);
        })->first();

        /*if ($pago->actividad_id != null) {
            
            $detalle = $pago->actividad->detalle;
        }
        else {

            if ($pago->ordencompra_id != null) {
                
                $ordencompra = $pago->ordencompra;
            }
            else {

                $ordencompra = $pago->contrato->ordencompra;
            }

            if ($ordencompra->actividad_id != null) {

                $detalle = $ordencompra->actividad->detalle;
            }
            else {

                $detalle = $ordencompra->decision->actividad->detalle;
            }
        }*/

        $data = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'contrato' => $user->n_contrato,
            'cargo' => $user->cargo->cargo,
            'oficina' => $user->oficina->oficina,
            'fecha' => $pago->fecha,
            'tramitante' => $tramitante->first_name. ' ' .$tramitante->last_name,
            'proveedor' => $pago->proveedor_id != 0 ? $pago->proveedor->razon_social : $pago->proveedor_user->first_name. ' ' .$pago->proveedor_user->last_name,
            'tipo_pago' => $pago->tipopago->nombre,
            'concepto' => $pago->concepto,
            'centro_costo' => $pago->centro_costo,
            'linea_presupuesta' => $pago->linea_presupuesta,
            'moneda_pago' => $pago->moneda_pago,
            'descripcion' => $pago->descripcion,
            //'detalle' => $detalle,
            'monto' => $pago->monto,
            'impuesto' => $pago->impuesto,
            'isr' => $pago->isr,
            'monto_total' => $pago->monto_total,
            'monto_usd' => $pago->monto_usd,
            'monto_sk' => $pago->monto_sk,
            'status'=> $status,
        ];

        //return view('emails.aprobacion.pago', $data);

        Mail::send('emails.aprobacion.pago', $data, function ($message) use ($superior) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
        
            $message->to($superior->email,"$superior->first_name $superior->last_name");
        
            $message->bcc($superior->email,"$superior->first_name $superior->last_name");
        
            $message->subject('Solicitud de Pago de Compra');
        });

        Mail::send('emails.aprobacion.pago', $data, function ($message) use ($user) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
        
            $message->to($user->email,"$user->first_name $user->last_name");
        
            $message->bcc($user->email,"$user->first_name $user->last_name");
        
            $message->subject('Solicitud de Pago de Compra');
        });

        Mail::send('emails.aprobacion.pago', $data, function ($message) use ($direcFin) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
        
            $message->to($direcFin->email,"$direcFin->first_name $direcFin->last_name");
        
            $message->bcc($direcFin->email,"$direcFin->first_name $direcFin->last_name");
        
            $message->subject('Solicitud de Pago de Compra');
        });

        if ($request->aprobacion == 1) {
           return redirect()->route('pago.index')
            ->withSuccess("Aprobada la solicitud Pago de Compra de:  ".$user->first_name." ".$user->last_name);   
        } 
        elseif ($request->aprobacion == 2) {
           return redirect()->route('pago.index')
            ->withErrors("Rechazada la solicitud Pago de Compra de:  ".$user->first_name." ".$user->last_name); 
        }
        elseif ($request->aprobacion == 3) {
           return redirect()->route('pago.index')
            ->withSuccess("Anulada la solicitud Pago de Compra de:  ".$user->first_name." ".$user->last_name); 
        }
    }

    public function destroy($id)
    {
       $pago = PagoCompra::find($id); 
       $pago->delete();

       return redirect()->route('pago.index')
            ->withSuccess('Orden de Pago borrada con exito');
    }

    public function download($id)
    {
        $pago = PagoCompra::find($id);

        //return view('compras.pagos.pdf_pago', compact('pago'));

        $pdf = PDF::loadView('compras.pagos.pdf_pago', compact('pago'));
        
        return $pdf->download("Orden de Pago-".date('d-m-Y')."-".$pago->user->first_name." ".$pago->user->last_name.".pdf");
    }
}

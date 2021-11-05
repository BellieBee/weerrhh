<?php

namespace Vanguard\Http\Controllers;

use Vanguard\Actividad;
use Vanguard\BonoSalud;
use Vanguard\ActividadDetalle;
use Vanguard\ActividadDocumento;
use Vanguard\Decision;
use Vanguard\OrdenCompra;
use Vanguard\Contrato;
use Vanguard\PagoCompra;
use Vanguard\Proyecto;
use Vanguard\Proveedor;
use Vanguard\CuentaContable;
use Vanguard\CentroCosto;
use Vanguard\LineaPresupuestaria;
use Vanguard\TipoCompra;
use Vanguard\TipoDocumentoCompra;
use Vanguard\Cargo;
use Vanguard\User;
use Vanguard\Pais;
use Illuminate\Http\Request;
use Vanguard\Http\Requests;
use Entrust;
use Storage;
use Carbon\Carbon;
use DB;
use PDF;

class ActividadesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-actividad-todos|ver-actividad-oficina|ver-actividad-solo');
    }

    public function index()
    {
    	$colegas = "";
    	$oficina_id = auth()->user()->oficina_id;

    	if(Entrust::can('ver-actividad-todos')) {

    		$actividades = Actividad::orderBy('fecha','desc')->get();
    	}
    	elseif(Entrust::can('ver-actividad-oficina')) {
    			
    		$actividades = Actividad::orderBy('fecha','desc')->where('oficina_id', $oficina_id)->get();
    	}
    	elseif (Entrust::can('ver-actividad-solo')) {
    		
    		$userSuperior = auth()->user()->cargo_id;
            $cargosInferiores = new Cargo;
            $colegasInferiores = $cargosInferiores->inferiores($userSuperior, $oficina_id);

            //dd($colegasInferiores);

            $actividades = Actividad::orderBy('fecha', 'desc')->whereIn('user_id', [auth()->user()->id, $colegasInferiores ? $colegasInferiores : ''])->get();
    	}


    	if (Entrust::can('crear-actividad-todos')) {
            $colegas = User::orderBy('created_at', 'desc')->where('status', 1)->whereNotIn('categoria_id', [3])->get();
        }
        elseif (Entrust::can('crear-actividad-oficina')) {
            $colegas = User::orderBy('created_at', 'desc')->where('oficina_id', $oficina_id)->where('status', 1)->whereNotIn('categoria_id', [3])->get();
        }

    	return view('compras.actividades.index', compact('actividades','colegas'));	
    }

    public function create(Request $request)
    {

        if($request->user_id) {

            $user = User::find($request->user_id);
            $bono_salud = BonoSalud::find($request->id);
        } 
        else
        {
            if (Entrust::can(['crear-actividad-todos', 'crear-actividad-oficina'])) {

                $user = User::find($request->id);
            }
            else {

                $user = auth()->user();
            }

            $bono_salud = null;
        }

        $correlativo = $user->oficina->inicial_correlativo.'-'.$user->oficina->correlativo_solicitud;
        $correlativo = $correlativo.'-'.Carbon::now()->year;
        $proyectos = Proyecto::orderBy('nombre', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $cuentas = CuentaContable::orderBy('nombre', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $centrosCosto = CentroCosto::orderBy('codigo', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $lineasPresupuestarias = LineaPresupuestaria::orderBy('codigo', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $tipoCompra = TipoCompra::get();
        $proveedores = Proveedor::orderBy('razon_social', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $provUsers = User::orderBy('first_name', 'asc')->where('oficina_id', $user->oficina_id)->where('status', 1)->get();
        $tipoMoneda = Pais::orderBy('moneda_nombre', 'asc')->get();
        $tipoDoc = TipoDocumentoCompra::get();
        
        return view('compras.actividades.create', compact('user', 'bono_salud', 'correlativo', 'proyectos', 'cuentas', 'centrosCosto', 'lineasPresupuestarias', 'tipoCompra', 'proveedores', 'provUsers', 'tipoMoneda', 'tipoDoc'));

    }

    public function buscarMoneda(Request $request)
    {
    	$pais = Pais::where('id', $request['moneda'])->first();
    	$data = [
    		'moneda_simbolo' => $pais->moneda_simbolo,
    		'moneda_nombre' => $pais->moneda_nombre
    	];

    	return response()->json($data);
    }

    public function store(Request $request) 
    { 
        //dd($request->proveedor_id);

        if ($request->tipo_compra == 1 || $request->tipo_compra == 2) {

            if ($request->proveedor_id == '' || $request->proveedor_id == 0 && $request->proveedor_user_id == '') {
                return redirect()->route('actividad.index')->withErrors("El Pago de Servicios o Pago Directo debe tener un Proveedor o Colega Proveedor");
            }
        }
        
        if ($request->tipo_compra == 1 && $request->file_factura[1] == null /*|| (in_array(2, $request->tipo_documento) == false)*/) {

            return redirect()->route('actividad.index')->withErrors("El Pago de Servicios debe tener al menos una factura");
        } 
        elseif ($request->tipo_compra == 2 && in_array(1, $request->tipo_documento) == false && $request->file_factura[1] == null /*|| in_array(2, $request->tipo_documento) == false*/) {
                
            return redirect()->route('actividad.index')->withErrors("El Tipo de Compra Directo debe tener al menos una cotización y una factura");
        }
        else { 
            //dd($request);
            $actividad = new Actividad;
            $actividad->correlativo = $request->correlativo;
            $actividad->fecha = date('Y-m-d');
            $actividad->user_id = $request->solicitante;
            $actividad->admin_id = $request->persona_administrativa;
            $actividad->oficina_id = $request->oficina_id;
            $actividad->bono_salud_id = $request->bono_salud_id;
            $actividad->actividad = $request->actividad;
            $actividad->comentarios = $request->comentarios;
            $actividad->tipo_compra_id = $request->tipo_compra;
            $actividad->proveedor_id = $request->proveedor_id ? $request->proveedor_id : null;
            $actividad->proveedor_user_id = $request->proveedor_user_id ? $request->proveedor_user_id : null;
            $actividad->monto = $request->monto_total;
            $actividad->tipo_moneda_id = $request->tipo_moneda;
            $actividad->monto_sk = $request->monto_sk;
            $actividad->monto_usd = $request->monto_usd;
            $actividad->aprobacion_1 = 0;
            $actividad->aprobacion_2 = 0;
            $actividad->save();

            $file_factura = $request->file('file_factura');
            foreach ($file_factura as  $key => $value) {

                if ($value) {

                    if (($request->proyecto[$key] == '' && $request->cuenta[$key] == '' && $request->centroCosto[$key] == '' && $request->lineaPresup[$key] == '') || $request->proyecto[$key] == '' || $request->cuenta[$key] == '' || $request->centroCosto[$key] == '' || $request->lineaPresup[$key] == '') {
                        
                        return redirect()->route('actividad.index')->withErrors("ATENCION: La Solicitud de Actividad ha sido creada. Sin embargo se detectó que la factura no tiene Proyecto, Cuenta, Centro de Costo y/o Línea Presupuestaria asignado. NO se guardará su detalle de factura si no llenan estos campos.");
                    }
                    else {
                  
                        $nombre_documento = uniqid().'.'.$value->getClientOriginalExtension();            
                  
                        Storage::disk('documentos_compras_actividad')->put($nombre_documento,  \File::get($value));

                        $factura = new ActividadDetalle;
                        $factura->actividad_id = $actividad->id;
                        $factura->factura = $request->factura[$key];
                        $factura->proyecto_id = $request->proyecto[$key];
                        $factura->cuenta_id = $request->cuenta[$key];
                        $factura->centro_costo_id = $request->centroCosto[$key];
                        $factura->linea_presupuestaria_id = $request->lineaPresup[$key];
                        $factura->file = $nombre_documento;
                        $factura->monto = $request->monto[$key];
                        $factura->save();            
                    }
                }
            }

            $file_documento = $request->file('file_documento');
            foreach ($file_documento as  $key => $value) {

                if ($value) {

                    if ($request->tipo_documento[$key] == '') {
                        return redirect()->route('actividad.index')->withErrors("ATENCION: La Solicitud de Actividad ha sido creada. Sin embargo se detectó que uno de los documentos no tiene Tipo de Documento asignado. NO se guardará su documento si no llena este campo.");
                    }
                    else {
                  
                        $nombre_documento = uniqid().'.'.$value->getClientOriginalExtension();            
                  
                        Storage::disk('documentos_compras_actividad')->put($nombre_documento,  \File::get($value));

                        $documento = new ActividadDocumento;
                        $documento->actividad_id = $actividad->id;
                        $documento->fecha = $request->fecha_documento[$key];
                        $documento->tipo_documento_compras_id = $request->tipo_documento[$key];
                        $documento->file = $nombre_documento;
                        $documento->proveedor_id = $request->proveedor_documento[$key] ? $request->proveedor_documento[$key] : null;
                        $documento->save();
                    }            
                }
            }

            $correlativo = $actividad->oficina->correlativo_solicitud;
            DB::table('oficinas')->where('id', $request->oficina_id)->update(['correlativo_solicitud' => $correlativo+1]);

            return redirect()->route('actividad.index')->withSuccess("Actividad creada con éxito");
        }
    }

    public function edit($id) 
    {
        $actividad = Actividad::find($id);
        $user = $actividad->user;

        $proyectos = Proyecto::orderBy('nombre', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $cuentas = CuentaContable::orderBy('nombre', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $centrosCosto = CentroCosto::orderBy('codigo', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $lineasPresupuestarias = LineaPresupuestaria::orderBy('codigo', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $tipoCompra = TipoCompra::get();
        $tipoMoneda = Pais::orderBy('moneda_nombre', 'asc')->get();
        $tipoDoc = TipoDocumentoCompra::get();
        $proveedores = Proveedor::orderBy('razon_social', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $provUsers = User::orderBy('first_name', 'asc')->where('oficina_id', $user->oficina_id)->where('status', 1)->get();

        return view('compras.actividades.edit', compact('actividad', 'user', 'proyectos', 'cuentas', 'centrosCosto', 'lineasPresupuestarias', 'tipoCompra', 'tipoMoneda','tipoDoc', 'proveedores', 'provUsers'));   
    }

    public function update(Request $request)
    {
        if ($request->tipo_compra == 1 || $request->tipo_compra == 2) {

            if ($request->proveedor_id == '' || $request->proveedor_id == 0 && $request->proveedor_user_id == '') {
                return redirect()->route('actividad.index')->withErrors("El Pago de Servicios o Pago Directo debe tener un Proveedor o Colega Proveedor");
            }
        }

        if ($request->tipo_compra == 1 && !$request->oldF && count($request->file_factura) == 1) {

            return redirect()->route('actividad.index')->withErrors("El Pago de Servicios debe tener al menos una factura");
        } 
        elseif ($request->tipo_compra == 2 && in_array(1, $request->tipo_documento) == false && count($request->file_factura) == 1 && !$request->oldF) {
                
            return redirect()->route('actividad.index')->withErrors("El Tipo de Compra Directo debe tener al menos una cotización y una factura");
        }
        else {

            $actividad = Actividad::find($request->id);
            $actividad->actividad = $request->actividad;
            $actividad->comentarios = $request->comentarios;
            $actividad->tipo_compra_id = $request->tipo_compra;
            $actividad->proveedor_id = $request->proveedor_id ? $request->proveedor_id : null;
            $actividad->proveedor_user_id = $request->proveedor_user_id ? $request->proveedor_user_id : null;
            $actividad->monto = $request->monto_total;
            $actividad->tipo_moneda_id = $request->tipo_moneda;
            $actividad->monto_sk = $request->monto_sk;
            $actividad->monto_usd = $request->monto_usd;
            $actividad->save();

            //primero preguntamos si hay facturas y docs creados y si requieren actualizarse
            
            $facturas = ActividadDetalle::where('actividad_id', $request->id)->get();        
            $documentos = ActividadDocumento::where('actividad_id', $request->id)->get();

            if (count($facturas) > 0) {
                
                foreach ($facturas as $key => $factura) {
                    
                    $factura->factura = $request->factura[$key+1];
                    $factura->proyecto_id = $request->proyecto[$key+1];
                    $factura->cuenta_id = $request->cuenta[$key+1];
                    $factura->centro_costo_id = $request->centroCosto[$key+1];
                    $factura->linea_presupuestaria_id = $request->lineaPresup[$key+1];
                    $factura->monto = $request->monto[$key+1];
                    $factura->save();
                }
            }

            if (count($documentos) > 0) {
                    
                foreach ($documentos as $key => $documento) {
                        
                    $documento->fecha = $request->fecha_documento[$key+1];
                    $documento->tipo_documento_compras_id = $request->tipo_documento[$key+1];
                    $documento->proveedor_id = $request->proveedor_documento[$key+1] ? $request->proveedor_documento[$key+1] : null;
                    $documento->save();
                }
            }

            //Después preguntamos si hay una factura y/o documento nuevo añadidos

            $file_factura = $request->file('file_factura');
            $file_documento = $request->file('file_documento');

            if (count($file_factura) > 1) {
                
                $start_fact = count($facturas);

                foreach ($file_factura as $key => $fact) {
                    
                    if ($fact) {

                        if (($request->proyecto[$start_fact+$key] == '' && $request->cuenta[$start_fact+$key] == '' && $request->centroCosto[$start_fact+$key] == '' && $request->lineaPresup[$start_fact+$key] == '') || $request->proyecto[$start_fact+$key] == '' || $request->cuenta[$start_fact+$key] == '' || $request->centroCosto[$start_fact+$key] == '' || $request->lineaPresup[$start_fact+$key] == '') {
                            
                            return redirect()->route('actividad.index')->withErrors("ATENCION: La Solicitud de Actividad ha sido actualizada. Sin embargo se detectó que la nueva factura no tiene Proyecto, Cuenta, Centro de Costo y/o Línea Presupuestaria asignado. NO se guardará su detalle de factura nuevo si no llena estos campos.");
                        }
                        else {
                        
                            $nombre_factura = uniqid().'.'.$fact->getClientOriginalExtension();
                            Storage::disk('documentos_compras_actividad')->put($nombre_factura,  \File::get($fact));

                            $newFact = new ActividadDetalle;
                            $newFact->actividad_id = $actividad->id;
                            $newFact->factura = $request->factura[$start_fact+$key];
                            $newFact->proyecto_id = $request->proyecto[$start_fact+$key];
                            $newFact->cuenta_id = $request->cuenta[$start_fact+$key];
                            $newFact->centro_costo_id = $request->centroCosto[$start_fact+$key];
                            $newFact->linea_presupuestaria_id = $request->lineaPresup[$start_fact+$key];
                            $newFact->file = $nombre_factura;
                            $newFact->monto = $request->monto[$start_fact+$key];
                            $newFact->save();
                        }
                    }
                }
            }

            if (count($file_documento) > 1) {

                $start = count($documentos);
                
                foreach ($file_documento as  $key => $value) {

                    if ($value) {

                        if ($request->tipo_documento[$key] == '') {
                            return redirect()->route('actividad.index')->withErrors("ATENCION: La Solicitud de Actividad ha sido actualizada. Sin embargo se detectó que uno de los nuevos documentos no tiene Tipo de Documento asignado. NO se guardará su documento nuevo si no llena este campo.");
                        }
                        else {

                            $nombre_documento = uniqid().'.'.$value->getClientOriginalExtension();
                            Storage::disk('documentos_compras_actividad')->put($nombre_documento,  \File::get($value));

                            $newDoc = new ActividadDocumento;
                            $newDoc->actividad_id = $actividad->id;
                            $newDoc->fecha = $request->fecha_documento[$start+$key];
                            $newDoc->tipo_documento_compras_id = $request->tipo_documento[$start+$key];
                            $newDoc->file = $nombre_documento;
                            $newDoc->proveedor_id = $request->proveedor_documento[$start+$key] ? $request->proveedor_documento[$start+$key] : null;
                            $newDoc->save();
                        }            
                    }
                }
            }
        }

        return redirect()->route('actividad.index')->withSuccess("Actividad actualizada con éxito");

    }

    public function deleteDoc($id, Request $request) 
    {
        if($request->factura) {

            $documento = ActividadDetalle::find($id);
        }
        else {

            $documento = ActividadDocumento::find($id);
        }

        $actividad = $documento->actividad;

        if(file_exists(public_path() . '/documentos_compras_actividad/'.$documento->file)) {

            Storage::disk('documentos_compras_actividad')->delete($documento->file);
        }
        
        $documento->delete();     
      
        return redirect()->route('actividad.edit',['id' => $actividad->id])->withSuccess('Documento borrado con exito');
    }

    public function show($id)
    {
        $actividad = Actividad::find($id);
        $user = $actividad->user;

        $proyectos = Proyecto::orderBy('nombre', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $cuentas = CuentaContable::orderBy('nombre', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $centrosCosto = CentroCosto::orderBy('codigo', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $lineasPresupuestarias = LineaPresupuestaria::orderBy('codigo', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $tipoCompra = TipoCompra::get();
        $tipoMoneda = Pais::orderBy('moneda_nombre', 'asc')->get();
        $proveedores = Proveedor::orderBy('razon_social', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $tipoDoc = TipoDocumentoCompra::get();
        $provUsers = User::orderBy('first_name', 'asc')->where('oficina_id', $user->oficina_id)->where('status', 1)->get();

        return view('compras.actividades.show', compact('actividad', 'user', 'proyectos', 'cuentas', 'centrosCosto', 'lineasPresupuestarias', 'tipoCompra', 'tipoMoneda', 'proveedores', 'provUsers', 'tipoDoc'));
    }

    /*public function aprobacion(Request $request)
    {
        $actividad = Actividad::find($request['id']);

        if($request['aprobacion_1']) {

            $actividad->aprobacion_1 = $request['aprobacion_1'];
            $actividad->fecha_aprobacion_1 = date('Y-m-d');
        }
        elseif ($request['aprobacion_2']) {

            $actividad->aprobacion_2 = $request['aprobacion_2'];
            $actividad->fecha_aprobacion_2 = date('Y-m-d');
        }

        $actividad->save();
        $data = [
            'aprobacion_1' => $actividad->aprobacion_1,
            'aprobacion_2' => $actividad->aprobacion_2
        ];

        return response()->json($data);

        if($request->aprobacion_1 == 1) {

            return redirect()->route('actividad.index')->withSuccess("La Solicitud de Actividad de ".$actividad->user->first_name." ".$actividad->user->last_name." ha recibido su primera aprobación");
        }
        if ($request->aprobacion_1 == 2) {
            
            return redirect()->route('actividad.index')->withErrors("La Solicitud de Actividad de ".$actividad->user->first_name." ".$actividad->user->last_name." ha recibido su primer rechazo");
        }
        if ($request->aprobacion_1 == 3) {
            
            return redirect()->route('actividad.index')->withSuccess("La Solicitud de Actividad de ".$actividad->user->first_name." ".$actividad->user->last_name." ha recibido su primera anulación");
        }
        if($request->aprobacion_2 == 1) {

            return redirect()->route('actividad.index')->withSuccess("La Solicitud de Actividad de ".$actividad->user->first_name." ".$actividad->user->last_name." ha recibido su segunda aprobación");
        }
        if ($request->aprobacion_2 == 2) {
            
            return redirect()->route('actividad.index')->withErrors("La Solicitud de Actividad de ".$actividad->user->first_name." ".$actividad->user->last_name." ha recibido su segundo rechazo");
        }
        if($request->aprobacion_2 == 3) {

            return redirect()->route('actividad.index')->withSuccess("La Solicitud de Actividad de ".$actividad->user->first_name." ".$actividad->user->last_name." ha recibido su segunda anulación");
        }
    }*/

    public function destroy($id)
    {
        $actividad = Actividad::find($id);

        if ($actividad->tipo_compra_id == 1) {
            
            $pago = PagoCompra::where('actividad_id', $id)->first();

            if ($pago != null) {
                
                $pago_delete = PagoCompra::find($pago->id);
                $pago_delete->delete();
            }
        }
        elseif ($actividad->tipo_compra_id == 2) {
            
            $ordencompra = OrdenCompra::where('actividad_id', $id)->first();

            if ($ordencompra != null) {
                
                $pago = PagoCompra::where('orden_compra_id', $ordencompra->id)->first();

                if ($pago != null) {
                    
                    $pago_delete = PagoCompra::find($pago->id);
                    $pago_delete->delete();
                }

                $ordencompra_delete = OrdenCompra::find($ordencompra->id);
                $ordencompra_delete->delete();
            }
        }
        else {

            $decision = Decision::where('actividad_id', $id)->first();

            if ($decision != null) {
                
                $ordencompra = OrdenCompra::where('decision_id', $decision->id)->first();

                if ($ordencompra != null) {
                    
                    $contrato = Contrato::where('ordencompra_id', $ordencompra->id)->first();

                    if ($contrato != null) {
                        
                        $pago = PagoCompra::where('contrato_id', $contrato->id)->first();

                        if ($pago != null) {
                        
                            $pago_delete = PagoCompra::find($pago->id);
                            $pago_delete->delete();
                        }

                        $contrato_delete = Contrato::find($contrato->id);

                        $documentos_contrato = $contrato_delete->documentos;
       
                        foreach ($documentos_contrato as $docC) {
                            if (file_exists(public_path() . '/documentos/'.$docC->documento)) {
                                Storage::disk('documentos')->delete($docC->documento);
                                $docC->delete();
                            }
                        }

                        $contrato_delete->delete();
                    }

                    $ordencompra_delete = OrdenCompra::find($ordencompra->id);
                    $ordencompra_delete->delete();
                }

                $decision_delete = Decision::find($decision->id);

                /*$documentos_decision = $decision_delete->documento;
       
                foreach ($documentos_decision as $docD) {

                    if (file_exists(public_path() . '/documentos_compras_actividad/'.$docD->documento)) {
                        Storage::disk('documentos_compras_actividad')->delete($docD->documento);
                        $docD->delete();
                    }
                }*/   
                
                $decision_delete->delete();
            }
        }

        $facturas = $actividad->detalle;
        $documentos = $actividad->documento;

        foreach ($facturas as $factura) {
            if (file_exists(public_path() . '/documentos_compras_actividad/'.$factura->file)) {
                Storage::disk('documentos_compras_actividad')->delete($factura->file);
                $factura->delete();
            }
        }
       
        foreach ($documentos as $documento) {
            if (file_exists(public_path() . '/documentos_compras_actividad/'.$documento->file)) {
                Storage::disk('documentos_compras_actividad')->delete($documento->file);
                $documento->delete();
            }
        }     
       
       $actividad->delete();

       return redirect()->route('actividad.index')
            ->withSuccess('Actividad borrada con exito');
    }

    public function downloadDocument($document)
    {
        $file = public_path().'/documentos_compras_actividad/'.$document;

        return response()->download($file);
    }
}

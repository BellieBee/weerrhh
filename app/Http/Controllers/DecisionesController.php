<?php

namespace Vanguard\Http\Controllers;

use Vanguard\Actividad;
use Vanguard\ActividadDetalle;
use Vanguard\ActividadDocumento;
use Vanguard\Proveedor;
use Vanguard\Proyecto;
use Vanguard\CuentaContable;
use Vanguard\CentroCosto;
use Vanguard\LineaPresupuestaria;
use Vanguard\Decision;
use Vanguard\DecisionDocumento;
use Vanguard\OrdenCompra;
use Vanguard\PagoCompra;
use Vanguard\Contrato;
use Vanguard\TipoDocumentoCompra;
use Vanguard\User;
use Vanguard\Cargo;
use Illuminate\Http\Request;
use Vanguard\Http\Requests;
use Entrust;
use Storage;
use Carbon\Carbon;
use DB;
use PDF;

class DecisionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-decision-todos|ver-decision-oficina|ver-decision-solo');
    }

    public function index()
    {
        $oficina_id = auth()->user()->oficina_id;
        
        if(Entrust::can('ver-decision-todos')) {

            $decisiones = Decision::orderBy('fecha','desc')->get();
        }
        elseif (Entrust::can('ver-decision-oficina')) {
            
            $decisiones = Decision::orderBy('fecha', 'desc')->where('oficina_id', $oficina_id)->get();
        }
        elseif (Entrust::can('ver-decision-solo')) {

            $user_id = auth()->user()->id;
            $userSuperior = auth()->user()->cargo_id;
            $cargosInferiores = new Cargo;
            $colegasInferiores = $cargosInferiores->inferiores($userSuperior, $oficina_id);

            $find = DB::table('actividades')->select('id');

            $find->when($colegasInferiores, function($query) use ($colegasInferiores) {
                return $query->whereIn('user_id', [$colegasInferiores]);
            });

            $find->when($user_id, function($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            });

            $find->when($user_id, function($query) use ($user_id) {
                return $query->where('admin_id', $user_id);
            });
            
            $find->get();

            $actividades_id = $find->paginate(8);

            $decisiones = Decision::orderBy('fecha', 'desc')->whereIn('actividad_id', [$actividades_id])->get();
        }

        return view('compras.decisiones.index', compact('decisiones'));
    }

    public function create($id) 
    {
    	$user = auth()->user();
        $correlativo = $user->oficina->correlativo_decision;
        $correlativo = 'WE-ADC-'.$correlativo.'-'.Carbon::now()->year;
    	$actividad = Actividad::find($id);
    	$tipoDoc = TipoDocumentoCompra::get();
    	$proveedores = Proveedor::orderBy('razon_social', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $provUsers = User::orderBy('first_name', 'asc')->where('oficina_id', $user->oficina_id)->where('status', 1)->get();
        $proyectos = Proyecto::orderBy('nombre', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $cuentas = CuentaContable::orderBy('nombre', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $centrosCosto = CentroCosto::orderBy('codigo', 'asc')->where('oficina_id', $user->oficina_id)->get();
        $lineasPresupuestarias = LineaPresupuestaria::orderBy('codigo', 'asc')->where('oficina_id', $user->oficina_id)->get(); 

    	return view('compras.decisiones.create', compact('correlativo', 'actividad', 'tipoDoc', 'proveedores', 'provUsers', 'proyectos', 'cuentas', 'centrosCosto', 'lineasPresupuestarias'));
    }

    public function store(Request $request)
    {
    	$decision = new Decision;
    	$decision->correlativo = $request->correlativo;
    	$decision->fecha = date('Y-m-d');
    	$decision->actividad_id = $request->actividad_id;
    	$decision->oficina_id = $request->oficina_id;
    	$decision->antecedentes = $request->antecedentes;
    	$decision->decision = $request->decision;
    	$decision->proveedor_id = $request->proveedor_id;
        $decision->proveedor_user_id = $request->proveedor_user_id ? $request->proveedor_user_id : null;
    	$decision->notas = $request->notas;
    	$decision->justificacion = $request->justificacion;
    	$decision->aprobacion_1 = 0;
    	$decision->aprobacion_2 = 0;

    	$decision->save();

        if($decision->actividad->monto = "0.00") {

            $actividad = Actividad::find($request->actividad_id);
            $actividad->monto = $request->monto_total;
            $actividad->monto_sk = $request->monto_sk;
            $actividad->monto_usd = $request->monto_usd;
            $actividad->save();
        }

    	$file_documento = $request->file('file_documento');
        if (count($file_documento) > 1) {

            //$start = count($file_documento);
            
            foreach ($file_documento as  $key => $value) {

                if ($value) {

                    if ($request->tipo_documento[$key] == '') {
                        return redirect()->route('decision.index')->withErrors("ATENCION: La Decisión ha sido actualizada. Sin embargo se detectó que uno de los nuevos documentos no tiene Tipo de Documento asignado. NO se guardará su documento nuevo si no llena este campo.");
                    }
                    else {

                        $nombre_documento = uniqid().'.'.$value->getClientOriginalExtension();
                        
                        Storage::disk('documentos_compras_actividad')->put($nombre_documento,  \File::get($value));

                        $newDoc = new ActividadDocumento;
                        $newDoc->actividad_id = $decision->actividad_id;
                        $newDoc->fecha = $request->fecha_documento[$key];
                        $newDoc->tipo_documento_compras_id = $request->tipo_documento[$key];
                        $newDoc->file = $nombre_documento;
                        $newDoc->proveedor_id = $request->proveedor_documento[$key] ? $request->proveedor_documento[$key] : null;
                        $newDoc->save();
                    }           
                }
            }
        }

        $facturas = ActividadDetalle::where('actividad_id', $request->actividad_id)->get();

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

        $file_factura = $request->file('file_factura');

        if (count($file_factura) > 1) {
                
            $start_fact = count($facturas);

            foreach ($file_factura as $key => $fact) {
                    
                if ($fact) {

                    if (($request->proyecto[$start_fact+$key] == '' && $request->cuenta[$start_fact+$key] == '' && $request->centroCosto[$start_fact+$key] == '' && $request->lineaPresup[$start_fact+$key] == '') || $request->proyecto[$start_fact+$key] == '' || $request->cuenta[$start_fact+$key] == '' || $request->centroCosto[$start_fact+$key] == '' || $request->lineaPresup[$start_fact+$key] == '') {
                            
                        return redirect()->route('decision.index')->withErrors("ATENCION: La Decisión ha sido actualizada. Sin embargo se detectó que la nueva factura no tiene Proyecto, Cuenta, Centro de Costo y/o Línea Presupuestaria asignado. NO se guardará su detalle de factura nuevo si no llena estos campos.");
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

        $correlativo = $decision->oficina->correlativo_decision;
        DB::table('oficinas')->where('id', $request->oficina_id)->update(['correlativo_decision' => $correlativo+1]);

        return redirect()->route('decision.index')->withSuccess("Decisión creada con éxito");
    }

    public function edit($id)
    {
    	$decision = Decision::find($id);
    	$tipoDoc = TipoDocumentoCompra::get();
    	$proveedores = Proveedor::orderBy('razon_social', 'asc')->where('oficina_id', auth()->user()->oficina_id)->get();
        $provUsers = User::orderBy('first_name', 'asc')->where('oficina_id', auth()->user()->oficina_id)->where('status', 1)->get();
        $proyectos = Proyecto::orderBy('nombre', 'asc')->where('oficina_id', auth()->user()->oficina_id)->get();
        $cuentas = CuentaContable::orderBy('nombre', 'asc')->where('oficina_id', auth()->user()->oficina_id)->get();
        $centrosCosto = CentroCosto::orderBy('codigo', 'asc')->where('oficina_id', auth()->user()->oficina_id)->get();
        $lineasPresupuestarias = LineaPresupuestaria::orderBy('codigo', 'asc')->where('oficina_id', auth()->user()->oficina_id)->get();

    	return view('compras.decisiones.edit', compact('decision', 'tipoDoc', 'proveedores', 'provUsers', 'proyectos', 'cuentas', 'centrosCosto', 'lineasPresupuestarias'));
    }

    public function update(Request $request) 
    {
    	$decision = Decision::find($request->id);
    	$decision->antecedentes = $request->antecedentes;
    	$decision->decision = $request->decision;
    	$decision->proveedor_id = $request->proveedor_id;
        $decision->proveedor_user_id = $request->proveedor_user_id ? $request->proveedor_user_id : null;
    	$decision->notas = $request->notas;
    	$decision->justificacion = $request->justificacion;

    	$decision->save();

        if($decision->actividad->monto != $request->monto_total) {

            $actividad = Actividad::find($decision->actividad_id);
            $actividad->monto = $request->monto_total;
            $actividad->monto_sk = $request->monto_sk;
            $actividad->monto_usd = $request->monto_usd;
            $actividad->save();
        }

        //primero preguntamos si hay docs creados y si requieren actualizarse
        
        $documentos = ActividadDocumento::where('actividad_id', $decision->actividad_id)->get();
        $facturas = ActividadDetalle::where('actividad_id', $decision->actividad_id)->get();

        if (count($documentos) > 0) {
                
            foreach ($documentos as $key => $documento) {
                    
                $documento->fecha = $request->fecha_documento[$key+1];
                $documento->tipo_documento_compras_id = $request->tipo_documento[$key+1];
                $documento->proveedor_id = $request->proveedor_documento[$key+1] ? $request->proveedor_documento[$key+1] : null;
                $documento->save();
            }
        }

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

        //Después preguntamos si hay un documento nuevo añadidos

        $file_documento = $request->file('file_documento');

        if (count($file_documento) > 1) {

            $start = count($documentos);
            
            foreach ($file_documento as  $key => $value) {

                if ($value) {

                    if ($request->tipo_documento[$key] == '') {
                        return redirect()->route('decision.index')->withErrors("ATENCION: La Decisión ha sido actualizada. Sin embargo se detectó que uno de los nuevos documentos no tiene Tipo de Documento asignado. NO se guardará su documento nuevo si no llena este campo.");
                    }
                    else {

                        $nombre_documento = uniqid().'.'.$value->getClientOriginalExtension();
                        
                        Storage::disk('documentos_compras_actividad')->put($nombre_documento,  \File::get($value));

                        $newDoc = new ActividadDocumento;
                        $newDoc->actividad_id = $decision->actividad_id;
                        $newDoc->fecha = $request->fecha_documento[$start+$key];
                        $newDoc->tipo_documento_compras_id = $request->tipo_documento[$start+$key];
                        $newDoc->file = $nombre_documento;
                        $newDoc->proveedor_id = $request->proveedor_documento[$start+$key] ? $request->proveedor_documento[$start+$key] : null;
                        $newDoc->save();
                    }           
                }
            }
        }

        $file_factura = $request->file('file_factura');

        if (count($file_factura) > 1) {
                
            $start_fact = count($facturas);

            foreach ($file_factura as $key => $fact) {
                    
                if ($fact) {

                    if (($request->proyecto[$start_fact+$key] == '' && $request->cuenta[$start_fact+$key] == '' && $request->centroCosto[$start_fact+$key] == '' && $request->lineaPresup[$start_fact+$key] == '') || $request->proyecto[$start_fact+$key] == '' || $request->cuenta[$start_fact+$key] == '' || $request->centroCosto[$start_fact+$key] == '' || $request->lineaPresup[$start_fact+$key] == '') {
                            
                        return redirect()->route('decision.index')->withErrors("ATENCION: La Decisión ha sido actualizada. Sin embargo se detectó que la nueva factura no tiene Proyecto, Cuenta, Centro de Costo y/o Línea Presupuestaria asignado. NO se guardará su detalle de factura nuevo si no llena estos campos.");
                    }
                    else {
                        
                        $nombre_factura = uniqid().'.'.$fact->getClientOriginalExtension();
                            Storage::disk('documentos_compras_actividad')->put($nombre_factura,  \File::get($fact));

                        $newFact = new ActividadDetalle;
                        $newFact->actividad_id = $decision->actividad_id;
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

        return redirect()->route('decision.index')->withSuccess("Decisión actualizada con éxito");
    }

    public function deleteDoc($id) 
    {
        $documento = ActividadDocumento::find($id);
        $decision = $documento->decision;

        if(file_exists(public_path() . '/documentos_compras_actividad/'.$documento->file)) {

            Storage::disk('documentos_compras_actividad')->delete($documento->file);
        }
        
        $documento->delete();     
      
        return redirect()->route('decision.edit',['id' => $decision->id])->withSuccess('Documento borrado con exito');
    }

    public function show($id)
    {
    	$decision = Decision::find($id);
    	$tipoDoc = TipoDocumentoCompra::get();
    	$proveedores = Proveedor::orderBy('razon_social', 'asc')->where('oficina_id', auth()->user()->oficina_id)->get();
        $provUsers = User::orderBy('first_name', 'asc')->where('oficina_id', auth()->user()->oficina_id)->where('status', 1)->get();
        $proyectos = Proyecto::orderBy('nombre', 'asc')->where('oficina_id', auth()->user()->oficina_id)->get();
        $cuentas = CuentaContable::orderBy('nombre', 'asc')->where('oficina_id', auth()->user()->oficina_id)->get();
        $centrosCosto = CentroCosto::orderBy('codigo', 'asc')->where('oficina_id', auth()->user()->oficina_id)->get();
        $lineasPresupuestarias = LineaPresupuestaria::orderBy('codigo', 'asc')->where('oficina_id', auth()->user()->oficina_id)->get();

    	return view('compras.decisiones.show', compact('decision', 'tipoDoc', 'proveedores', 'provUsers', 'proyectos', 'cuentas', 'centrosCosto', 'lineasPresupuestarias'));
    }

    /*public function aprobacion(Request $request, $id)
    {
        $decision = Decision::find($id);

        if($request->aprobacion_1) {

            $decision->aprobacion_1 = $request->aprobacion_1;
            $decision->fecha_aprobacion_1 = date('Y-m-d');
            $decision->aprobador_1_id = auth()->user()->id;
        }
        elseif ($request->aprobacion_2) {

            $decision->aprobacion_2 = $request->aprobacion_2;
            $decision->fecha_aprobacion_2 = date('Y-m-d');
            $decision->aprobador_2_id = auth()->user()->id;
        }

        $decision->save();

        if($request->aprobacion_1 == 1) {

            return redirect()->route('decision.index')->withSuccess("La Decisión de ".$decision->actividad->admin->first_name." ".$decision->actividad->admin->last_name." ha recibido su primera aprobación");
        }
        if ($request->aprobacion_1 == 2) {
            
            return redirect()->route('decision.index')->withErrors("La Decisión de ".$decision->actividad->admin->first_name." ".$decision->actividad->admin->last_name." ha recibido su primer rechazo");
        }
        if ($request->aprobacion_1 == 3) {
            
            return redirect()->route('decision.index')->withSuccess("La Decisión de ".$decision->actividad->admin->first_name." ".$decision->actividad->admin->last_name." ha recibido su primera anulación");
        }
        if($request->aprobacion_2 == 1) {

            return redirect()->route('decision.index')->withSuccess("La Decisión de ".$decision->actividad->admin->first_name." ".$decision->actividad->admin->last_name." ha recibido su segunda aprobación");
        }
        if ($request->aprobacion_2 == 2) {
            
            return redirect()->route('decision.index')->withErrors("La Decisión de ".$decisión->actividad->admin->first_name." ".$decision->actividad->admin->last_name." ha recibido su segundo rechazo");
        }
        if($request->aprobacion_2 == 3) {

            return redirect()->route('decision.index')->withSuccess("La Decisión de ".$decision->actividad->admin->first_name." ".$decision->actividad->admin->last_name." ha recibido su segunda anulación");
        }
    }*/

    public function destroy($id)
    {
        $decision = Decision::find($id);
            
        $ordencompra = OrdenCompra::where('decision_id', $id)->first();

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
       
                foreach ($documentos_contrato as $doc) {
                    if (file_exists(public_path() . '/documentos/'.$doc->documento)) {
                        Storage::disk('documentos')->delete($doc->documento);
                        $doc->delete();
                    }
                }

                $contrato_delete->delete();
            }

            $ordencompra_delete = OrdenCompra::find($ordencompra->id);
            $ordencompra_delete->delete();
        }

        /*$documentos = $decision->documento;
       
        foreach ($documentos as $documento) {

            if (file_exists(public_path() . '/documentos_compras_actividad/'.$documento->documento)) {
                Storage::disk('documentos_compras_actividad')->delete($documento->documento);
                $documento->delete();
            }
        }*/      
       
        $decision->delete();

        return redirect()->route('decision.index')
            ->withSuccess('Decisión borrada con exito');
    }

    public function download($id)
    {
    	$decision = Decision::find($id);
    	$oficina = $decision->oficina;
        $coord = $this->userRole(4);
        $dir = $this->userRole(5);
        $dir_fin = $this->userRole(9); 

    	//return view('compras.decisiones.pdf_decision', compact('decision','oficina', 'coord', 'dir', 'dir_fin'));

		$pdf = PDF::loadView('compras.decisiones.pdf_decision', compact('decision','oficina', 'coord', 'dir', 'dir_fin'));
        
        return $pdf->download("Decisión-".date('d-m-Y')."-".$decision->actividad->admin->first_name." ".$decision->actividad->admin->last_name.".pdf");
    }

    public function downloadDocument($document)
    {
        $file = public_path().'/documentos_compras_actividad/'.$document;

        return response()->download($file);
    }

    private function userRole($id) 
    {
        $user = DB::table('users')->join('role_user','users.id', '=', 'role_user.user_id')->where('status', 1)->where('role_id', $id)->select('users.*', 'role_user.role_id')->first();

        return $user;
    }
}

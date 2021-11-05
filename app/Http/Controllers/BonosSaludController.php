<?php

namespace Vanguard\Http\Controllers;

use Illuminate\Http\Request;
use Vanguard\Http\Requests;
use Vanguard\BonoSalud;
use Vanguard\BonoSaludDocumento;
use Vanguard\User;
use Vanguard\Cargo;
use Vanguard\Permission;
use Entrust;
use Storage;
use DB;
use Mail;
use PDF;

class BonosSaludController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-bonossalud-todos|ver-bonossalud-oficina|ver-bonossalud-solo');       
    }

    public function index()
    {
    	$colegas = "";
    	$oficina_id = auth()->user()->oficina_id;

    	if(Entrust::can('ver-bonossalud-todos')) {

    		$bonos_salud = BonoSalud::orderBy('fecha_solicitud','desc')->get();
    	}
    	elseif(Entrust::can('ver-bonossalud-oficina')) {
    		$usersNot = User::whereIn('cargo_id', [6,7])->get();
            $not = array();
            foreach($usersNot as $u)
            {
                array_push($not, $u->id);
            }
    		$bonos_salud = BonoSalud::orderBy('fecha_solicitud','desc')->where('oficina_id', $oficina_id)->whereNotIn('user_id', $not)->get();
    	}
    	elseif (Entrust::can('ver-bonossalud-solo')) {
    		
    		$userSuperior = auth()->user()->cargo_id;
            $cargosInferiores = new Cargo;
            $colegasInferiores = $cargosInferiores->inferiores($userSuperior, $oficina_id);

            $bonos_salud = BonoSalud::orderBy('fecha_solicitud', 'desc')->whereIn('user_id', [auth()->user()->id, $colegasInferiores ? $colegasInferiores : ''])->get();
    	}


    	if (Entrust::can('crear-bonossalud-todos')) {
            $colegas = User::orderBy('created_at', 'desc')->where('status', 1)->whereNotIn('categoria_id', [3])->get();
        }
        elseif (Entrust::can('crear-bonossalud-oficina')) {
            $colegas = User::orderBy('created_at', 'desc')->where('oficina_id', $oficina_id)->where('status', 1)->whereNotIn('categoria_id', [3])->get();
        }

    	return view('bono_salud.index',compact('bonos_salud','colegas'));

    }

    public function create(Request $request)
    {
        if (Entrust::can(['crear-bonossalud-todos', 'crear-bonossalud-oficina'])) {

            $user = User::find($request->id);
        }
        else {

            $user = auth()->user();
        }
        
        $bono_sk = $this->calculateBonoSK($user);

        $permiso = new Permission();
        $rolesAprobAll = $permiso->rolesPermission('aprobar-bonossalud');
        $rolesAprobInf = $permiso->rolesPermission('aprobar-bonossalud-inferior');

        return view('bono_salud.create', compact('user', 'bono_sk', 'rolesAprobAll', 'rolesAprobInf'));

    }

    public function store(Request $request)
    {
        //dd($request);

        $mensaje = 'Solicitud de Bono Salud creado con exito';

        $bono_salud = new BonoSalud;
        $bono_salud->user_id = $request->user_id;
        $bono_salud->oficina_id = $request->oficina_id;
        $bono_salud->saldo_inicial = $request->saldo_inicial;
        $bono_salud->tipo_cambio_sk = $request->tipo_cambio_sk;
        $bono_salud->tipo_cambio_usd = $request->tipo_cambio_usd;
        $bono_salud->costo_moneda_local = $request->costo_moneda_local;
        $bono_salud->costo_sk = $request->costo_sk;
        $bono_salud->saldo_final = $request->saldo_final;
        $bono_salud->destino = $request->destino;
        $bono_salud->fecha_solicitud = date('Y-m-d');
        $bono_salud->status = 0;

        $bono_salud->save();

        $file = $request->file('file_documento');
        foreach ($file as  $key => $value) {

            if ($value) {
              
              $nombre_documento = uniqid().'.'.$value->getClientOriginalExtension();            
              
              Storage::disk('documentos_bono_salud')->put($nombre_documento,  \File::get($value));

              $documento = new BonoSaludDocumento;
              $documento->bono_salud_id = $bono_salud->id;
              $documento->nombre_documento = $request->nombre_documento[$key];
              $documento->documento = $nombre_documento;
              $documento->save();            
            }
        }

        $user = $bono_salud->user;
        $user->bono_salud = $request->saldo_final;
        $user->save();

        //Notificación de correo electrónico

        $status="<label style='color: green;'><b>Se ha creado una actividad de salud </b></label>";
        $data = array(
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'contrato' => $user->n_contrato,
            'cargo' => $user->cargo->cargo,
            'oficina' => $user->oficina->oficina,
            'fecha_solicitud' => $bono_salud->fecha_solicitud,
            'saldo_inicial' => $request->saldo_inicial,
            'costo_moneda_local' => $bono_salud->costo_moneda_local,
            'tipo_cambio' => $bono_salud->tipo_cambio,
            'costo_sk' => $bono_salud->costo_sk,
            'saldo_final' => $request->saldo_final,
            'destino' => $bono_salud->destino,
            'status' => $status,
        );


        //enviamos un mail a todos los que tengan permiso de enterarse de todos los bonos salud y/o los de su oficina

        $colegas = User::all();
        $coord = DB::table('users')->join('role_user','users.id', '=', 'role_user.user_id')->where('status', 1)->where('role_id', 4)->select('users.*', 'role_user.role_id')->first();

        foreach ($colegas as $key => $colega) {
            
            if($colega->can('notf-crear-bonossalud-todos') || ($colega->can('notf-crear-bonossalud-oficina') && $colega->oficina_id == $request->oficina_id) || $request->user_id == $colega->id) {

                Mail::send('emails.aprobacion.bono_salud', $data, function ($message) use ($colega) {
                    $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                    $message->subject('Solicitud de Bono Salud');
                    $message->to($colega->email,"$colega->first_name $colega->last_name");
                });
            }
        }

        Mail::send('emails.aprobacion.bono_salud', $data, function ($message) use ($coord) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Bono Salud');
            $message->to($coord->email,"$coord->first_name $coord->last_name");
        });

        return redirect()->route('bonosalud.index')->withSuccess($mensaje);
    }

    public function edit($id)
    {
        $bono_salud = BonoSalud::find($id);
        $user = $bono_salud->user;

        $permiso = new Permission();
        $rolesAprobAll = $permiso->rolesPermission('aprobar-bonossalud');
        $rolesAprobInf = $permiso->rolesPermission('aprobar-bonossalud-inferior');

        return view('bono_salud.edit', compact('bono_salud', 'user', 'rolesAprobAll', 'rolesAprobInf'));
    }

    public function update(Request $request)
    {
        $mensaje = 'Solicitud de Bono Salud actualizado con exito';

        $bono_salud = BonoSalud::find($request->id);
        $bono_salud->saldo_inicial = $request->saldo_inicial;
        $bono_salud->tipo_cambio_sk = $request->tipo_cambio_sk;
        $bono_salud->tipo_cambio_usd = $request->tipo_cambio_usd;
        $bono_salud->costo_moneda_local = $request->costo_moneda_local;
        $bono_salud->costo_sk = $request->costo_sk;
        $bono_salud->saldo_final = $request->saldo_final;
        $bono_salud->destino = $request->destino;

        $bono_salud->save();

        //dd($request->file('file_documento'));
        
        //primero preguntamos si hay docs creados y si requieren actualizarse
        
        $documentos = BonoSaludDocumento::where('bono_salud_id', $request->id)->get();

        if (count($documentos) > 0) {
                
            foreach ($documentos as $key => $documento) {
                    
                $documento->nombre_documento = $request->nombre_documento[$key+1];
                $documento->save();
            }
        }

        //Después preguntamos si hay un documento nuevo añadidos

        $file = $request->file('file_documento');

        if (count($file) > 1) {

            $start = count($documentos);
            
            foreach ($file as  $key => $value) {

                if ($value) {

                    $nombre_documento = uniqid().'.'.$value->getClientOriginalExtension();
                    Storage::disk('documentos_bono_salud')->put($nombre_documento,  \File::get($value));

                    $newDoc = new BonoSaludDocumento;
                    $newDoc->bono_salud_id = $bono_salud->id;
                    $newDoc->nombre_documento = $request->nombre_documento[$start+$key];
                    $newDoc->documento = $nombre_documento;
                    $newDoc->save();            
                }
            }
        }

        $user = $bono_salud->user;
        $user->bono_salud = $request->saldo_final;
        $user->save();

        return redirect()->route('bonosalud.index')->withSuccess($mensaje);
    }

    public function show($id)
    {
        $bono_salud = BonoSalud::find($id);
        $user = $bono_salud->user;

        $permiso = new Permission();
        $rolesAprobAll = $permiso->rolesPermission('aprobar-bonossalud');
        $rolesAprobInf = $permiso->rolesPermission('aprobar-bonossalud-inferior');

        return view('bono_salud.show', compact('bono_salud', 'user', 'rolesAprobAll', 'rolesAprobInf'));
    }

    public function deleteDoc($id) 
    {
        $documento = BonoSaludDocumento::find($id);
        $bono_salud = $documento->bono_salud;

        if(file_exists(public_path() . '/documentos_bono_salud/'.$documento->documento)) {

            Storage::disk('documentos_bono_salud')->delete($documento->documento);
        }
        
        $documento->delete();     
      
        return redirect()->route('bonosalud.edit',['id' => $bono_salud->id])->withSuccess('Documento borrado con exito');
    }

    public function aprobacion(Request $request, $id)
    {
        $bono_salud = BonoSalud::find($id);
        $user = $bono_salud->user;

        if($request->status == 1) {
            $status="<label style='color: green;'><b>Su solicitud de Bono Salud ha sido aprobada con exito </b></label>";
        }
        elseif ($request->status == 2) {
            $status="<label style='color: red;'><b>Su solicitud de Bono Salud ha sido rechazada</b></label>";
        }
        elseif ($request->status == 3) {
            $status="<label style='color: blue;'><b>Su solicitud de Bono Salud ha sido anulada</b></label>";
        }

        $bono_salud->status = $request->status;
        $bono_salud->save();

        //Notificación de correo electrónico

        $aprobador = auth()->user();
        $coord = DB::table('users')->join('role_user','users.id', '=', 'role_user.user_id')->where('status', 1)->where('role_id', 4)->select('users.*', 'role_user.role_id')->first();

        $data = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'contrato' => $user->n_contrato,
            'cargo' => $user->cargo->cargo,
            'oficina' => $user->oficina->oficina,
            'fecha_solicitud' => $request->fecha_solicitud,
            'saldo_inicial' => $request->saldo_inicial,
            'tipo_cambio' => $request->tipo_cambio,
            'costo_moneda_local' => $request->costo_moneda_local,
            'costo_sk' => $request->costo_sk,
            'saldo_final' => $request->saldo_final,
            'destino' => $request->destino,
            'status'=> $status,
        ];

        Mail::send('emails.aprobacion.bono_salud', $data, function ($message) use ($aprobador) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Bono Salud');
            $message->to($aprobador->email,"$aprobador->first_name $aprobador->last_name");
            $message->bcc($aprobador->email,"$aprobador->first_name $aprobador->last_name");
        });

        Mail::send('emails.aprobacion.bono_salud', $data, function ($message) use ($user) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Bono Salud');
            $message->to($user->email,"$user->first_name $user->last_name");
            $message->bcc($user->email,"$user->first_name $user->last_name");
        });

        Mail::send('emails.aprobacion.bono_salud', $data, function ($message) use ($coord) {
            $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
            $message->subject('Solicitud de Bono Salud');
            $message->to($coord->email,"$coord->first_name $coord->last_name");
        });

        if ($request->status == 1) {
           return redirect()->route('bonosalud.index')
            ->withSuccess("Aprobada la solicitud de bono salud de:  ".$bono_salud->user->first_name." ".$bono_salud->user->last_name);   
        } 
        elseif ($request->status == 2) {
           return redirect()->route('bonosalud.index')
            ->withErrors("Rechazada la solicitud de bono salud de:  ".$bono_salud->user->first_name." ".$bono_salud->user->last_name); 
        }
        elseif ($request->status == 3) {
           return redirect()->route('bonosalud.index')
            ->withSuccess("Anulada la solicitud de bono salud de:  ".$bono_salud->user->first_name." ".$bono_salud->user->last_name); 
        }

    }

    public function destroy($id)
    {
       $bono_salud = BonoSalud::find($id);
       $documentos = $bono_salud->documento->pluck('documento')->toArray();
       
       foreach ($documentos as $documento) {
          if (file_exists(public_path() . '/documentos_bono_salud/'.$documento)) {
              Storage::disk('documentos_bono_salud')->delete($documento);
          }
       }      
       
       $bono_salud->delete();

       return redirect()->route('bonosalud.index')
            ->withSuccess('Bono Salud borrado con exito');
    }

    public function download($id)
    {
        $bono_salud = BonoSalud::find($id);
        $user = $bono_salud->user;

        $pdf = PDF::loadView('bono_salud.pdf_bono_salud', compact('bono_salud','user'));
        
        return $pdf->download("Solicitud de Bono Salud-".date('d-m-Y')."-".$user->first_name." ".$user->last_name.".pdf");
    }

    public function downloadDocument($document)
    {
        $file = public_path().'/documentos_bono_salud/'.$document;

        return response()->download($file);
    }

    private function calculateBonoSK($user)
    {
        $year = date('Y');
        $sk_pais = $user->oficina->pais->corona_sueca;

        if (str_contains($user->fecha_inicio, $year)) {
            
            $month = date('n', strtotime($user->fecha_inicio));
            $sk_neto = ($sk_pais / 12) * (12 - $month + 1);
            //dd($sk_neto);
        }
        else {

            $sk_neto = $sk_pais;
        }

        $solicitudes_bonos = BonoSalud::whereDate('fecha_solicitud', '>=', $year.'-01-01')
                                ->whereDate('fecha_solicitud', '<=', $year.'-12-31')
                                ->where('user_id', $user->id)
                                ->whereIn('status', [0, 1])
                                ->get();

        if(count($solicitudes_bonos) > 0) {

            $bono_sk = round($sk_neto - $solicitudes_bonos->sum('costo_sk'), 2);
        }
        else {
            
            $bono_sk = $sk_neto;
        }

        $user->bono_salud = $bono_sk;
        $user->save();

        return $bono_sk;
    }
}

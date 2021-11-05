<?php

namespace Vanguard\Http\Controllers;

use Vanguard\Http\Actions\GetContentType;
use Illuminate\Http\Request;
use DB;
use Validator;
use Storage;
use Entrust;
use Mail;
use Vanguard\Http\Requests;
use Vanguard\Contrato;
use Vanguard\User;
use Vanguard\Role;
use Vanguard\Oficina;
use Vanguard\Pais;
use Vanguard\Categoria;
use Vanguard\Pagos_contrato;
use Vanguard\Upload_documento;
use Vanguard\Month;
use Vanguard\Adenda;
use Vanguard\Proveedor;
use Vanguard\PagoCompra;
use PDF;
use Dompdf\Dompdf;
use Carbon\Carbon;

class ContratosController extends Controller
{

  public function __construct() 
  {
    $this->middleware('auth');
    $this->middleware('permission:ver-contratos-todos|ver-contratos-oficina');
  }

    public function index($value='')
    { 
      
      $oficinas=Oficina::get();
      
      $contratos=Contrato::get(); 

      $categoria=categoria::where('categoria','Consultor')->first()->id;
      
      $users=User::where('categoria_id',$categoria)->get();

      $contratos=Contrato::orderBy('n_contrato', 'desc')->get();
      
      if (Entrust::can('ver-contratos-oficina')) {
        //$oficinas=$oficinas->where('id',auth()->user()->oficina->id);
        $contratos=Contrato::where('oficina_id',auth()->user()->oficina->id)->orderBy('n_contrato', 'desc')->get();        
      }

      if(Entrust::can('crear-contratos-oficina'))
      {
       $oficinas=$oficinas->where('id',auth()->user()->oficina->id); 
      }
      
      $reCont = new Contrato;
      //$representantes = $reCont->representantePais($contratos);

      return view('contratos.list',compact('users','oficinas','contratos'));
    }

    public function create(Request $request)
    {
      $user = $request->id != 0 ? User::find($request->id) : false;
      $edit = false;
      $view = false;
      $correlativo=DB::table('settings')->where('key', 'correlativo')->first();
      $correlativo = 'CS-'.str_pad($correlativo->value, 3, '0', STR_PAD_LEFT).'-'.Carbon::now()->year;
      /*$representantesPais = User::where('status', 1)->whereHas('roles', function($q) {
        $q->whereIn('name',['RepresentantePais']);
    }
    )->get();*/

      if (Entrust::hasRole('Directora')) {
        $directora=auth()->user();
        
      }else{
        $directora=Role::where('name','Directora')->first()->users->where('status',1)->first();
      }
      
      if($request->ordencompra == 1) {
        //dd($request);
        $ordencompra = 1;
        $ordencompra_id = $request->ordencompra_id;

        if ($request->proveedor_id) {
          $proveedor = Proveedor::find($request->proveedor_id);
          $proveedor_user = false;
          $pais = $proveedor->pais;
        }
        elseif ($request->proveedor_user_id) {
          $proveedor_user = User::find($request->proveedor_user_id);
          $proveedor = false;
          $pais = $proveedor_user->oficina->pais;
        }
        else {
          $proveedor = false;
          $proveedor_user = false;
          $pais = $user->oficina->pais;
        }
      } 
      else {

        $ordencompra = 0;
        $proveedor = false;
        $proveedor_user = false;
        $ordencompra_id = false;
        $pais = $user->oficina->pais;
      }

      $oficinas = new Pais;
      $oficinas = $oficinas->oficinasArray($pais);

      $representantePais = Role::where('name','RepresentantePais')->first()->users->where('status', 1)->whereIn('oficina_id', $oficinas)->first();

      return view('contratos.create',compact('user','edit', 'view', 'directora', 'correlativo', 'ordencompra', 'ordencompra_id', 'proveedor', 'proveedor_user', 'representantePais'));
    }
    
    public function store(Request $request)
    {
        function tiempo_contrato($fecha_inicio,$fecha_fin,$contrato)
        {          
          $tiempo_contrato=Carbon::parse($fecha_fin)->diffInDays(Carbon::parse($fecha_inicio));
          return $tiempo_contrato;
        }

        $validator = Validator::make($request->all(), [
             'file_documento.*' => 'max:10000'
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);                        
        }

        if ($request->contrato_id) {
          
          $contrato=Contrato::find($request->contrato_id);
          
          if ($contrato->status!=0) { 
            
            if ($request->cumplimiento || $request->cumplimiento!="") {
              
                $contrato->cumplimiento=$request->cumplimiento;
                $contrato->status=3;            
            
            }elseif ($contrato->aprobacion_directora && $contrato->aprobacion_coordinadora ){
              $contrato->cumplimiento=null;
              $contrato->status=1;               
            }                     
                   
          }else{
            $contrato->fill($request->all());
            if($request->radiooption == "directora") {
              $contrato->direct_id = $request->firmante_id;
              $contrato->representante_id = null;
            }
            elseif($request->radiooption == "representante") {
              $contrato->representante_id = $request->firmante_id;
              $contrato->direct_id = null;
            }
            $contrato->tiempo_contrato=tiempo_contrato($request->fecha_fin,$request->fecha_inicio,$contrato);
            $pagos=$contrato->pagos()->delete();
          }     
          
        }else{
          $contrato=(new Contrato)->fill($request->all());
          $contrato->tiempo_contrato=tiempo_contrato($request->fecha_fin,$request->fecha_inicio,$contrato);
          if($request->ordencompra_id) {
            $contrato->ordencompra_id = $request->ordencompra_id;
            if($request->proveedor_id) {
              $contrato->proveedor_id = $request->proveedor_id;
            }
          }
          if($request->radiooption == "directora") {
            $contrato->direct_id = $request->firmante_id;
          }
          elseif($request->radiooption == "representante") {
            $contrato->representante_id = $request->firmante_id;
          }
        }               
                
        $contrato->save();

        $file=$request->file('file_documento');
        foreach ($file as  $key => $value) {          
            if ($value){
              
              $nombre_documento=uniqid().'.'.$value->getClientOriginalExtension();            
              
              Storage::disk('documentos')->put($nombre_documento,  \File::get($value));

              $documento=new Upload_documento;
              $documento->nombre=$request->nombre_documento[$key];
              $documento->documento=$nombre_documento;
              $documento->contrato()->associate($contrato->id);
              $documento->save();
            
            }
        }

        if($request->monto){
            foreach ($request->monto as $key => $monto) {
                if ($monto!="") {
                  $pagos= new Pagos_contrato;
                  $pagos->monto=$monto;
                  $pagos->monto_l=$request->monto_l[$key];
                  $pagos->monto_producto=$request->monto_producto[$key];          
                  $pagos->contrato()->associate($contrato->id);
                  $pagos->save();
                }              
            }
        }

        $correlativo=DB::table('settings')->where('key', 'correlativo')->first();
        DB::table('settings')
            ->where('key', 'correlativo')
            ->update(['value' => $correlativo->value+1]);
        
        //Notificaciones de email
        if($contrato->user_id != null) {
          $userCont = $contrato->user;
        }
        elseif ($contrato->proveedor_id != null) {
          $userCont = $contrato->proveedor;
        }
        else {
          $userCont = $contrato->proveedorUser;
        }
        $users = User::all();

        foreach ($users as $user) {
          
          if($user->can('notf-crear-contratos-todos') || ($user->can('notf-crear-contratos-oficina') && $request->oficina_id == $user->oficina_id) || ($user->can('notf-crear-contratos-solo') && auth()->user()->id == $user->id)) {

            $data = array(
                    'n_contrato' => $contrato->n_contrato,
                    'fecha_fin' => $contrato->fecha_fin,
                    'nombre' => $contrato->proveedor_id == null ? $userCont->first_name : $userCont->razon_social,
                    'apellido' => $contrato->proveedor_id == null ? $userCont->last_name : '',
                    'sexo' => $contrato->proveedor_id == null ? $userCont->sexo : "Proveedor",
                    'status' => $contrato->status
                  );

            Mail::send('emails.contratos.aprobacion', $data, function ($message) use ($user)
            {
              $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
              $message->subject('Contrato nuevo');
              $message->to($user->email, $user->first_name);
            });

          }
        }

        



                /*$coord = User::select('first_name', 'email')->withRole('Coordinadora')->get();

                $data = array(
                    'n_contrato' => $contrato->n_contrato,
                    'fecha_fin' => $contrato->fecha_fin,
                    'nombre' => $user->first_name,
                    'apellido' => $user->last_name,
                    'sexo' => $user->sexo,
                    'status' => $contrato->status
                );

                Mail::send('emails.contratos.aprobacion', $data, function ($message) use ($coord)
                {
                    $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                    $message->subject('Contrato nuevo');
                    $message->to($coord[0]['email'], $coord[0]['first_name']);
                });*/

        return redirect()->route('contratos.list')
            ->withSuccess('Contrato generado con exito');  
    }

    public function edit($id)
    {
      $contrato=Contrato::find($id);

      if($contrato->user_id != null) {
        $user = $contrato->user;
        $proveedor = false;
        $proveedor_user = false;
        $user->fecha_string = $this->fecha_string($contrato->created_at->format('Y-m-d'));
      }
      elseif ($contrato->proveedor_id != null) {
        $user = false;
        $proveedor = $contrato->proveedor;
        $proveedor_user = false;
        $proveedor->fecha_string = $this->fecha_string($contrato->created_at->format('Y-m-d'));
      }
      else {
        $user = false;
        $proveedor = false;
        $proveedor_user = $contrato->proveedorUser;
        $proveedor_user->fecha_string = $this->fecha_string($contrato->created_at->format('Y-m-d'));
      }
      $edit=true;
      $view=false;
      //dd($contrato->actividades);
      
      if (Entrust::hasRole('Directora')) {
        $directora=auth()->user();
        # code...
      }else{
        $directora=Role::where('name','Directora')->first()->users->first();
      }

      $oficinas = new Pais;
      $oficinas = $oficinas->oficinasArray($contrato->oficina->pais);

      $representantePais = Role::where('name','RepresentantePais')->first()->users->where('status', 1)->whereIn('oficina_id', $oficinas)->first();
      
      return view('contratos.create',compact('user', 'proveedor', 'proveedor_user', 'edit','contrato','directora', 'representantePais', 'view'));
    }
    
    public function view($id)
    {
        $contrato=Contrato::find($id);

        if($contrato->user_id != null) {
          $user = $contrato->user;
          $proveedor = false;
          $proveedor_user = false;
        }
        elseif ($contrato->proveedor_id != null) {
          $user = false;
          $proveedor = $contrato->proveedor;
          $proveedor_user = false;
        }
        else {
          $user = false;
          $proveedor = false;
          $proveedor_user = $contrato->proveedorUser;
        }
        $edit=true;
        $view=true;
        if (Entrust::hasRole('Directora')) {
          $directora=auth()->user();
          # code...
        }else{
          $directora=Role::where('name','Directora')->first()->users->first();
        }

        $oficinas = new Pais;
        $oficinas = $oficinas->oficinasArray($contrato->oficina->pais);

        $representantePais = Role::where('name','RepresentantePais')->first()->users->where('status', 1)->whereIn('oficina_id', $oficinas)->first();

        return view('contratos.create',compact('user', 'proveedor', 'proveedor_user', 'edit','contrato','directora', 'representantePais', 'view'));
    }
    
    public function delete($id)
    {
       $contrato=Contrato::find($id);

       $pago = PagoCompra::where('contrato_id', $id)->first();

       if($pago != null) {
        
        $pago_delete = PagoCompra::find($pago->id);
        $pago_delete->delete();
       }

       $documentos = $contrato->documentos;
       
       foreach ($documentos as $documento) {
          if (file_exists(public_path() . '/documentos/'.$documento->documento)) {
              Storage::disk('documentos')->delete($documento->documento);
              $documento->delete();
          }
       }      
       
       $contrato->delete();
       return redirect()->route('contratos.list')
            ->withSuccess('Contrato borrado con exito');
    }

    public function delete_documento($id)
    { 
      
      $documento=Upload_documento::find($id);
      $contrato=$documento->contrato;
      if (file_exists(public_path() . '/documentos/'.$documento->documento)) {
              Storage::disk('documentos')->delete($documento->documento);
      }
      $documento->delete();     
      
      
      return redirect()->route('contrato.edit',['id' => $contrato->id])
        ->withSuccess('Documento borrado con exito');
    }

    public function fecha_string($fecha)
    {
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha_string=date("d", strtotime($fecha))." de ".$meses[date("n", strtotime($fecha))-1]." del ".date("Y", strtotime($fecha));
        return $fecha_string;         
    }
    
    public function aprobacion($id)
    {

      $contrato=Contrato::find($id);              
      $id=auth()->user()->id;
      //dd($contrato->aprobacion_coordinadora);
      if (Entrust::can('aprobar-contratos')) {
          
          if (!$contrato->aprobacion_coordinadora) {
                return redirect()->route('contratos.list')
            ->withErrors("Este Contrato aun sigue en revision debe esperar a ser confirmado");    
          } 
          $contrato->aprobacion_directora=1;
          if ($contrato->aprobacion_directora) {
              $contrato->status=1;
          }
          $contrato->fecha_aprobacion_directora=date('Y-m-d');
          $contrato->directora()->associate($id);
          $mensaje="Aprobado el contrato: $contrato->consultoria";
      }
      elseif (Entrust::can('confirmar-contratos')) {
          $contrato->aprobacion_coordinadora=1;
          $contrato->fecha_aprobacion_coordinadora=date('Y-m-d');
          $contrato->coordinadora()->associate($id);
          $mensaje="Confirmado el contrato: $contrato->consultoria";
      }         

      $contrato->save();
      return redirect()->route('contratos.list')
      ->withSuccess($mensaje); 
    }

    public function anulacion($id)
    {
      $contrato = Contrato::find($id);
      $contrato->status = 4;
      $contrato->save();

      return redirect()->route('contratos.list')->withSuccess("El contrato ".$contrato->consultoria."ha sido anulado.");
    }
    
    public function ajax_contrato($id)
    {
        $contrato=Contrato::find($id);
        echo json_encode($contrato); 
    }
    
    public function descargar_pdf($id)
    {
        ini_set('max_execution_time', 300);
        $contrato = Contrato::find($id);
        
        if($contrato->user != null) {
          $user = $contrato->user;
        }
        elseif ($contrato->proveedor_id != null) {
          $user = $contrato->proveedor;
        }
        else {
          $user = $contrato->proveedorUser;
        }
        $extraeMesInicio = date('m', strtotime($contrato->fecha_inicio));
        $extraeMesFin = date('m', strtotime($contrato->fecha_fin));

        $mesInicio = $this->mes_nombre($extraeMesInicio);
        $mesFin = $this->mes_nombre($extraeMesFin);

        //dd($mes);
        
        if (Entrust::hasRole('Directora')) 
        {
          $directora=auth()->user();        
        }
        else
        {
          $directora=Role::where('name','Directora')->first()->users->first();
        }

        $directora->firma_directora="";
        if($contrato->aprobacion_directora==1 && $directora->firma){

            $directora->firma_directora="<img src='upload/users/$directora->firma'><br><br>";  
          
        } 
        $oficinas = new Pais;
        $oficinas = $oficinas->oficinasArray($contrato->oficina->pais);

        $representantePais = Role::where('name','RepresentantePais')->first()->users->where('status', 1)->whereIn('oficina_id', $oficinas)->first();

        $user->fecha_string=$this->fecha_string($contrato->created_at->format('Y-m-d'));
        //$user->consultor=$user->sexo?'La consultora':'El consultor';
        
        if($contrato->user_id != null) {

          $user->consultor = $user->sexo ? 'La consultora' : 'El consultor';
        }
        else {

          $user->consultor = 'El Proveedor';
        }         

        $contrato->alcance=str_replace("\r\n", '<br> ', $contrato->alcance);
        $contrato->actividades=str_replace("\r\n", '<br> ', $contrato->actividades);
        $contrato->metodologia=str_replace("\r\n", '<br> ', $contrato->metodologia);
        $contrato->objetivo=str_replace("\r\n", '<br> ', $contrato->objetivo);
        $contrato->productos=str_replace("\r\n", '<br> ', $contrato->productos);
        $director= Role::where('name','Directora')->first()->users->first();
        $oficina = Oficina::where('central', 1)->first();
        
        //
       
        $pdf= new Dompdf;
        $pdf->set_option("isPhpEnabled", true); 
        $pdf=PDF::loadView('pdf.pdf_contrato', compact('contrato', 'user', 'directora', 'mesInicio', 'mesFin','pdf', 'director', 'representantePais'));
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text('490', '740', "Pag {PAGE_NUM} de {PAGE_COUNT}", null, 12, array(0, 0, 0));
        return $pdf->download("Contrato $user->first_name-$user->last_name ".date("d-m-Y H:i:s").".pdf"); 
        //return view('pdf.pdf_contrato', compact('contrato', 'user', 'directora', 'mesInicio', 'mesFin'));
        /*return view('pdf.pdf_contrato', [
                    'contrato' => $contrato, 
                    'user' => $user, 
                    'directora' => $directora, 
                    'mesInicio' => $mesInicio, 
                    'mesFin' => $mesFin
                ] ); */
    }

    public function mes_nombre($extraeMes)
    {
        $mesNombre = Month::where('id', $extraeMes)->lists('month')->toArray();
        return $mesNombre;
    }
    
    
    public function email()
    {
      $contratos = Contrato::All();

      //$coord = User::select('first_name', 'email')->withRole('Coordinadora')->get();
      //dd($coord[0]['first_name']);

        foreach ($contratos as $contrato) {
            
            if ($contrato->fecha_fin == date('Y-m-d') && $contrato->status == 5) {
                
                $user = $contrato->user;

                $coord = User::select('first_name', 'email')->withRole('Coordinadora')->get();

                $data = array(
                    'n_contrato' => $contrato->n_contrato,
                    'fecha_fin' => $contrato->fecha_fin,
                    'nombre' => $user->first_name,
                    'apellido' => $user->last_name,
                    'status' => $contrato->status
                );

                Mail::send('emails.contratos.vence_contrato', $data, function ($message) use ($coord)
                {
                    $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                    $message->subject('Aviso de vencimiento de contrato');
                    $message->to($coord[0]['email'], $coord[0]['first_name']);
                });
            }
            elseif (date('Y-m', strtotime($contrato->fecha_fin)) == date('Y-m') && date("d", strtotime($contrato->fecha_fin)) - date('d') == 5 ) {
                
                $user = $contrato->user;

                $coord = User::select('first_name', 'email')->withRole('Coordinadora')->get();

                $data = array(
                    'n_contrato' => $contrato->n_contrato,
                    'fecha_fin' => $contrato->fecha_fin,
                    'nombre' => $user->first_name,
                    'apellido' => $user->last_name,
                    'sexo' => $user->sexo,
                    'status' => $contrato->status
                );

                Mail::send('emails.contratos.falta_cinco', $data, function ($message) use ($coord)
                {
                    $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                    $message->subject('Faltan 5 dias para que expire el contrato');
                    $message->to($coord[0]['email'], $coord[0]['first_name']);
                });
            }

            $this->info('Se enviaron los emails correspondientes');
        }
    }
}

<?php

namespace Vanguard\Http\Controllers;
use Illuminate\Http\Request;
use Vanguard\Http\Requests;
use Entrust;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Worksheet_Drawing;
use PHPExcel_Worksheet_PageSetup;
use Vanguard\Empleado_planilla_normal;
use Vanguard\Planilla;
use Vanguard\Aporte;
use Vanguard\Deduccion;
use Vanguard\Acumulado;
use Vanguard\User;
use Vanguard\Pais;
use Vanguard\Campo;
use Vanguard\Role;
use Vanguard\Month;
use Mail;
use Vanguard\Permission;

class PlanillaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-planillas-todos|ver-planillas-oficina');       
    }

    public function index($value='')
    {   
        if(Entrust::can('ver-planillas-todos')){
             $planillas=Planilla::get();
        }
        elseif (Entrust::can('ver-planillas-oficina')) {
             $planillas=Planilla::where('oficina_id',auth()->user()->oficina_id)
            ->get();
        }        
        //dd($planillas->first()->oficina_id);
        
        return view('planillas.list',compact('planillas'));
    }

    public function crear(Request $requests)
    {           
        $date = $requests->fecha;
        $country = Auth()->user()->oficina->pais;
        $month = $country->month;
        
        switch($month) {
            case 1:
                return $this->spreadsheets($date, $country, 'Enero');
            break;

            case 2:
                return $this->spreadsheets($date, $country, 'Febrero');
            break;

            case 3:
                return $this->spreadsheets($date, $country, 'Marzo');
            break;

            case 4:
                return $this->spreadsheets($date, $country, 'Abril');
            break;

            case 5:
                return $this->spreadsheets($date, $country, 'Mayo');
            break;

            case 6:
                return $this->spreadsheets($date, $country, 'Junio');
            break;

            case 7:
                return $this->spreadsheets($date, $country, 'Julio');
            break;

            case 8:
                return $this->spreadsheets($date, $country, 'Agosto');
            break;

            case 9:
                return $this->spreadsheets($date, $country, 'Septiembre');
            break;
            
            case 10:
                return $this->spreadsheets($date, $country, 'Octubre');
            break;

            case 11:
                return $this->spreadsheets($date, $country, 'Noviembre'); 
            break;

            case 12:
                return $this->spreadsheets($date, $country, 'Diciembre');
            break;

            default:
                return $this->spreadsheets($date, $country, 'n/a');
            break;
        }
    }

    public function store(Request $requests)
    {
        if ($requests->id_planilla) {
           
           $planilla=Planilla::find($requests->id_planilla);

           $planilla->confirmada=$requests->confirmada;

           $planilla->save();
           $empleados_planilla = $planilla->empleados;
           $empleados_planilla = $this->filterUsers($empleados_planilla);
           $edit=true;
       
        }else{            
           
            if (Planilla::where('m_a',$requests->m_a)->where('oficina_id',auth()->user()->oficina_id)->first()) {
                return redirect()->route('planilla.normal')
                ->withErrors("Al parecer la planilla del mes: ( $requests->m_a ) ya fue elaborada");
                
            }
            
            $planilla= (new Planilla)->fill($requests->all());



            $planilla->save();

            
            $empleados_planilla=$requests->planilla;
            $edit=false;
        }

        foreach ($empleados_planilla as  $empleado) {          
            
            //SALARIO
            if ($edit) {
                $p_e=$empleado;
                $aporte=$p_e->aporte;
                $deducciones=$p_e->deduccion;
                $acumulados=$p_e->acumulado;
                $id=$p_e->id;
                $empleado=$requests->planilla[$id];    
                
            }else{
                $p_e=new Empleado_planilla_normal;
                $aporte= new Aporte;
                $deducciones=new Deduccion;
                $acumulados= new Acumulado;    
            }
            
            $p_e->nombre=$empleado['nombre'];
            $p_e->n_contrato=$empleado['n_contrato'];
            $p_e->fecha_inicio=$empleado['fecha_inicio'];
            $p_e->documento=$empleado['documento'];
            $p_e->cargo=$empleado['cargo'];
            //dd($requests->all());
            if($requests->oficina_id != 1) {
                $p_e->dias_trabajados = $empleado['dias_trabajados'];
            }else {
                $p_e->dias_trabajados = 30;
            }
            $p_e->salario_base=$empleado['salario_base'];
            $p_e->ajuste=$empleado['ajuste'];
            $p_e->total_salario = ($requests->pais_id == 7) ? $empleado['total_salario'] : $empleado['salario_base']+$empleado['ajuste'];
            $p_e->user_id=$empleado['user_id'];
            $p_e->liquido_recibir=$empleado['liquido'];            
            
            if (str_contains($requests->m_a, 'Diciembre')) {
                           
                $acumualado_meses=array_keys($empleado['aguinaldo_meses']);
                $acumualado_meses=Acumulado::where('user_id',$empleado['user_id'])->whereIn('m_a',$acumualado_meses)->get();
                 
                foreach ($acumualado_meses as $mes) {
                   
                    $mes->aguinaldo=$empleado['aguinaldo_meses'][$mes->m_a];
                    
                    ///
                    if($requests->pago_indemnizacion=="anual") {
                       $mes->indemnizacion=$empleado['indemnizacion_meses'][$mes->m_a];
                    }
                    
                    ///
                    if($requests->pago_pension=="anual"){
                        if(array_key_exists('pension_meses', $empleado)) {
                            $mes->pension=$empleado['pension_meses'][$mes->m_a];
                        } else {
                            $mes->pension= 0;
                        }
                    }
                    
                    $mes->save();
                }                
                

                $p_e->total_aguinaldo=$empleado['total_aguinaldo'];

                
                //if($requests->pago_indemnizacion=="anual") {         

                    $p_e->total_indemnizacion=$empleado['total_indemnizacion'];
                //}
                
                //if($requests->pago_pension=="anual"){
                    if(array_key_exists('total_pension', $empleado)) {
                        $p_e->total_pension=$empleado['total_pension'];
                    } else {
                        $p_e->total_pension = 0;
                    }
                //}
            }
            
            if(str_contains($requests->m_a, $planilla->oficina->pais->bono_14))
            {
                $aporteCatorceavo = new Aporte;
                $meses_catorceavo = $aporteCatorceavo->mesesCatorceavo($planilla->oficina->pais);
                $acumulados_14 = Acumulado::where('user_id', $empleado['user_id'])->whereIn('m_a', $meses_catorceavo)->get();
                
                foreach($acumulados_14 as $acum14) 
                {
                    if(array_key_exists('catorceavo_acumulado', $empleado)) {
                        $acum14->catorceavo = $empleado['catorceavo_acumulado'][$acum14->m_a];
                        $acum14->save();
                    }
                }
            }

            //APORTES /////////////////////////////////////////////
            $aporte->bonificacion_14=array_key_exists('bonificacion_14', $empleado)?  $empleado['bonificacion_14']: 0;
            $aporte->seguridad_social_patronal=array_key_exists('seguridad_social_patronal', $empleado)?  $empleado['seguridad_social_patronal']: 0;

            if($requests->pais_id == 7) {
                $aporte->auxilio_transporte = array_key_exists('auxilio_transporte', $empleado) ? $empleado['auxilio_transporte']: 0;
                $aporte->bono_salud = array_key_exists('bono_salud', $empleado) ? $empleado['bono_salud']: 0;
                $aporte->capacitacion = array_key_exists('capacitacion', $empleado) ? $empleado['capacitacion']: 0;
                $aporte->seguro_medico = array_key_exists('seguro_medico', $empleado) ? $empleado['seguro_medico']: 0;

                // salvando los nuevos campos de aportes
                $aporte->intereses_cesantias = array_key_exists('intereses_cesantias', $empleado) ? $empleado['intereses_cesantias'] : 0;
                $aporte->incap_licencia = array_key_exists('incap_licencia', $empleado) ? $empleado['incap_licencia'] : 0;
                $aporte->total_incapacidad = array_key_exists('total_incapacidad', $empleado) ? $empleado['total_incapacidad'] : 0;
                $aporte->incapacidades = array_key_exists('total_incapacidad', $empleado) ? $empleado['incapacidades'] : 0;
                $aporte->aux_trans = array_key_exists('aux_trans', $empleado) ? $empleado['aux_trans'] : 0;
                $aporte->aux_celular = array_key_exists('aux_celular', $empleado) ? $empleado['aux_celular'] : 0;
                $aporte->medios_transp = array_key_exists('medios_transp', $empleado) ? $empleado['medios_transp'] : 0;
                $aporte->otros_devengos = array_key_exists('otros_devengos', $empleado) ? $empleado['otros_devengos'] : 0;

                $aporte->total_aportes=array_key_exists('total_aportes', $empleado)?  $empleado['total_aportes']: 0;
            }else {
                $aporte->total_aportes=array_key_exists('total_aportes', $empleado)?  $empleado['total_aportes']: 0;
            }

            $aporte->total_carga_patronal=0;
            $aporte->total_carga_patronal+=$aporte->seguridad_social_patronal;               
            switch ($requests->pais_id) {
                
                case 1://APORTES  DE GUATEMALA
                    $aporte->bonificacion_incentivo=$empleado['bonificacion_incentivo'];
                    $aporte->bonificacion_docto_37_2001=$empleado['bonificacion_docto_37_2001'];
                    $aporte->reintegros=$empleado['reintegros']; 
                    
                    break;
                
                case 2://APORTES PATRONALES DE BOLIVIA
                    $aporte->seguro_universitario       =$empleado['seguro_universitario'];
                    $aporte->afp_prevision              =$empleado['afp_prevision'];
                    $aporte->afp_prevision_pnvs         =$empleado['afp_prevision_pnvs'];
                    $aporte->afp_aporte_solidario       =$empleado['aporte_afp_aporte_solidario']  ;                  
                    
                    $aporte->total_carga_patronal+=
                    
                    $aporte->seguro_universitario+
                    $aporte->afp_prevision+
                    $aporte->afp_prevision_pnvs+
                    $aporte->afp_aporte_solidario;

                    break;

                case 3://APORTES PATRONALES DE NICARAGUA
                    $aporte->INATEC              =$empleado['INATEC'];
                    $aporte->total_carga_patronal+=$aporte->INATEC;
                    
                    break;

                case 4://APORTES PATRONALES DE HONDURAS 
                    $aporte->rap=$empleado['rap_patronal'];
                    $aporte->total_carga_patronal+=$aporte->rap;                  
                    
                    break;

                case 5://APORTES PATRONALES DE PARAGUAY
                    $aporte->total_aporte_25_5   =$p_e->total_salario*(25.5/100);
                    $aporte->total_carga_patronal+=$aporte->total_aporte_25_5;
                    
                    break;

                case 6://APORTES PATRONALES DE SALVADOR
                    $aporte->afp_6_75            = $empleado['afp_6_75'];
                    $aporte->total_carga_patronal+=$aporte->afp_6_75;
                    
                    break;  
                    
                case 7: //APORTES PATRONALES COLOMBIA
                    $aporte->parafiscales = $empleado['parafiscales'];
                    $aporte->arl = $empleado['arl'];
                    $aporte->eps = $empleado['eps'];
                    $aporte->caja_opcion = $empleado['caja_opcion'];
                    $aporte->icbf = $empleado['icbf'];
                    $aporte->sena = $empleado['sena'];
                    $aporte->salud_patronal = $empleado['salud_patronal'];
                    $aporte->pension_patronal = $empleado['pension_patronal'];

                    $aporte->total_carga_patronal += $aporte->parafiscales + $aporte->arl + $aporte->eps + $aporte->caja_opcion + $aporte->icbf + $aporte->sena + $aporte->salud_patronal + $aporte->pension_patronal;
                break;

                default:
                    
                    break;
            }            

            //DEDUCCIONES /////////////////////////////////////////////
            
            $deducciones->prestamo=array_key_exists('prestamo', $empleado)?  $empleado['prestamo']: 0;          
            $deducciones->interes=array_key_exists('interes', $empleado)?  $empleado['interes']: 0 ;
            $deducciones->otras_deducciones=array_key_exists('otras_deducciones',$empleado)? $empleado['otras_deducciones']: 0 ;
            $deducciones->impuesto_renta=array_key_exists('impuesto_renta', $empleado)?  $empleado['impuesto_renta']: 0 ;            
            $deducciones->seguridad_social=array_key_exists('seguridad_social', $empleado)?  $empleado['seguridad_social']: 0 ;
            //dd($deducciones);
            switch ($requests->pais_id) {                
                
                case 2://DEDUCCIONES DE BOLIVIA
                    $deducciones->cta_ind=$empleado['cta_ind'];
                    $deducciones->riesgo=$empleado['riesgo'] ;
                    $deducciones->com_afp=$empleado['com_afp'] ;
                    $deducciones->afp_aporte_solidario=$empleado['afp_aporte_solidario'];
                    $deducciones->afp_aporte_nacional_solidario=$empleado['afp_aporte_nacional_solidario'];
                    $deducciones->rc_iva=$empleado['rc_iva'] ;
                    break;
                
                case 6://DEDUCCIONES DE SALVADOR
                    $deducciones->afp=$empleado['afp'];
                    break;
                case 4://DEDUCCIONES DE HONDURAS
                    $deducciones->rap=$empleado['rap'];
                    $deducciones->seguro_medico=$empleado['seguro_medico'];
                    break;
                case 3://DEDUCCIONES DE NICARAGUA                    
                    $deducciones->deduccion_1=$empleado['deduccion_1'];
                    $deducciones->deduccion_2=$empleado['deduccion_2'];
                    break;

                case 7:
                    $deducciones->salud = $empleado['salud'];
                    $deducciones->pen = $empleado['pen'];
                    $deducciones->fsp = array_key_exists('fsp', $empleado) ? $empleado['fsp'] : 0.00;
                    $deducciones->rte = $empleado['rte'];
                    $deducciones->otros_descuentos_celulares = $empleado['otros_descuentos_celulares'];
                break;
                default:
                    # code...
                    break;
            }

            //sumo toda las deducciones
           /* $sum=0;
            $total_d=$edit ? $deducciones->total_deducciones :0;
            foreach ($deducciones->toArray() as $key => $value) {
                $sum+=$value;
            }*/            
            $deducciones->total_deducciones=$empleado['total_deducciones'];         
                       
            //ACUMULADOS/////////////////////////////////////////////           
            //pension
            if (array_key_exists('pension_meses', $empleado)) {                
               $acumulados->pension=$empleado['pension_meses']["$requests->m_a"];
            }
            
            //aguinaldo
            $acumulados->aguinaldo=$empleado['aguinaldo_meses']["$requests->m_a"];          

            //indemnisacion            
            $acumulados->indemnizacion=$empleado['indemnizacion_meses']["$requests->m_a"]; 

            if($requests->pais_id == 7) {
                $acumulados->interes_col=$empleado['interes_col_meses']["$requests->m_a"]; 
                $acumulados->vacaciones=$empleado['vacaciones_meses']["$requests->m_a"]; 
            }

            //catorceavo mes            
            //$acumulados->catorceavo = $requests->pais_id != 7 ? $empleado['catorceavo'] : 0;
            
            $acumulados->catorceavo = array_key_exists('catorceavo', $empleado) ? $empleado['catorceavo'] : 0;

            $acumulados->total_salario=$p_e->total_salario;       
            $acumulados->m_a=$requests->m_a;

            if ($edit) {
                $p_e->save();
                $aporte->save();
                $deducciones->save();                
                $acumulados->save();
                $mensaje="Planilla Actualizada con exito";

            }else{

                $p_e->planilla()->associate($planilla->id);
                $p_e->save();

                $aporte->empleado()->associate($p_e->id);
                $aporte->planilla()->associate($planilla->id);
                $aporte->save();
                $p_e->save();

                $deducciones->empleado()->associate($p_e->id);
                $deducciones->planilla()->associate($planilla->id);
                $deducciones->save();

                $acumulados->empleado()->associate($p_e->id);      
                $acumulados->user()->associate($p_e->user_id);      
                $acumulados->oficina()->associate($planilla->oficina_id);      
                $acumulados->planilla()->associate($planilla->id); 
                $acumulados->save();

                $mensaje="Planilla creada con exito";                

            }         
            
       }

        //Notificación de correo electrónico

        if($planilla->confirmada){
                
            if(Entrust::can('notf-confirmar-planilla-todos') || (Entrust::can('notf-confirmar-planilla-oficina') && auth()->user()->oficina->id == $planilla->oficina_id)) {

                $status="<label style='color: green;'><b> ha sido revisada</b></label>";
                $user = auth()->user();

                $data = array(
                    'planilla' => $planilla->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'oficina' => $user->oficina,
                    'fecha_planilla' => $planilla->m_a,
                    'status' => $status
                );

                Mail::send('emails.aprobacion.aprobacion', $data, function ($message) use ($user) {
                    $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                    $message->subject('Aprobacion de planilla');
                    $message->to($user->email,"$user->first_name $user->last_name");
                });
            }

                /////////////
                /*if (Entrust::hasRole(['Administradora'])) {
                    $user = Role::where('id', 4)->first();
                    $status="<label style='color: green;'><b> ha sido revisada</b></label>";

                    $data = array(
                        'planilla' => $planilla->id,
                        'first_name' => auth()->user()->first_name,
                        'last_name' => auth()->user()->last_name,
                        'oficina' => auth()->user()->oficina->oficina,
                        'fecha_planilla' => $planilla->m_a,
                        'status'=>$status
                    );

                    //return view('emails.aprobacion.aprobacion',$data);

                    $user=$user->users->first();
                    Mail::send('emails.aprobacion.aprobacion', $data, function ($message) use ($user) {
                        $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                        $message->subject('Aprobacion de planilla');
                        $message->to($user->email,"$user->first_name $user->last_name");
                    });
                }elseif(Entrust::hasRole(['Coordinadora'])){
                    $user = Role::where('id', 5)->first();
                    $status="<label style='color: green;'><b> ha sido revisada</b></label>";

                    $data = array(
                        'planilla' => $planilla->id,
                        'first_name' => auth()->user()->first_name,
                        'last_name' => auth()->user()->last_name,
                        'oficina' => auth()->user()->oficina->oficina,
                        'fecha_planilla' => $planilla->m_a,
                        'status'=>$status
                    );

                    //return view('emails.aprobacion.aprobacion',$data);

                    $user=$user->users->first();
                    Mail::send('emails.aprobacion.aprobacion', $data, function ($message) use ($user) {
                        $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                        $message->subject('Aprobacion de planilla');
                        $message->to($user->email,"$user->first_name $user->last_name");
                    });

                    // Send mail to contralora
                    $contralora = Role::where('id', 6)->first();
                    $contralora = $contralora->users->first();

                    Mail::send('emails.aprobacion.aprobacion', $data, function ($message) use ($contralora) {
                        $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                        $message->subject('Aprobacion de planilla');
                        $message->to($contralora->email,"$contralora->first_name $contralora->last_name");
                    });
                }elseif(Entrust::hasRole(['Contralora'])){
                    $user = Role::where('id', 5)->first();
                    $status="<label style='color: green;'><b> ha sido revisada</b></label>";

                    $data = array(
                        'planilla' => $planilla->id,
                        'first_name' => auth()->user()->first_name,
                        'last_name' => auth()->user()->last_name,
                        'oficina' => auth()->user()->oficina->oficina,
                        'fecha_planilla' => $planilla->m_a,
                        'status'=>$status
                    );

                    //return view('emails.aprobacion.aprobacion',$data);

                    $user=$user->users->first();
                    Mail::send('emails.aprobacion.aprobacion', $data, function ($message) use ($user) {
                        $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                        $message->subject('Aprobacion de planilla');
                        $message->to($user->email,"$user->first_name $user->last_name");
                    });

                    // Send mail to contralora
                    $contralora = Role::where('id', 6)->first();
                    $contralora = $contralora->users->first();

                    Mail::send('emails.aprobacion.aprobacion', $data, function ($message) use ($contralora) {
                        $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                        $message->subject('Aprobacion de planilla');
                        $message->to($contralora->email,"$contralora->first_name $contralora->last_name");
                    });
                }*/
            }        
        return redirect()->route('planilla.normal')
            ->withSuccess($mensaje);      
    }

    public function edit($id)
    {
        $planilla=Planilla::find($id);

        
            $users=$planilla->empleados;
            $vista='planillas.nueva_normal';
       
             
        $fecha=$planilla->m_a;

        $oficina=$planilla->oficina;
        $pais=$oficina->pais;
        $administradora=$planilla->administradora;
        $months = Month::all();
        $edit=true; 
        if (str_contains($fecha, 'Diciembre')) {
                $year=str_replace("Diciembre-", "", $fecha);           
        }else{
            $year=date('Y');
        }
        
        if($pais->bono_14 != 'No Disponible' && str_contains($fecha, $pais->bono_14))
        {
            $aporteCatorceavo = new Aporte;
            $meses_catorceavo = $aporteCatorceavo->mesesCatorceavo($pais);
        }
        else 
        {
            $meses_catorceavo = false;
        }

        $users = $this->filterUsers($users);

        $campos = explode(',', $pais->campo_deducciones);

        $permiso = new Permission();
        $rolesConfirmar = $permiso->rolesPermission('confirmar-planillas');
        $rolesAprobCoord = $permiso->rolesPermission('aprobar-planillas-coord');
        $rolesAprobDir = $permiso->rolesPermission('aprobar-planillas-direc');
        
        return  view($vista,compact('planilla','users','oficina','fecha','pais','edit','administradora','year', 'campos', 'meses_catorceavo', 'rolesConfirmar', 'rolesAprobCoord', 'rolesAprobDir'));
    }
    
    public function delete($id)
    {       
        $planilla=Planilla::find($id);        
        $m_a=$planilla->m_a;
        $oficina=$planilla->oficina->oficina;
        $planilla->delete();
        return redirect()->route('planilla.normal')
            ->withSuccess("Planilla $oficina $m_a Borrada con exito");
    }
    
    public function aprobacion($id)
    {
        $planilla=Planilla::find($id);
              
        $id=auth()->user()->id;
        if (Entrust::can('aprobar-planillas-direc')) {
            $planilla->aprobacion_directora=1;
            $planilla->fecha_aprobacion_directora=date('Y-m-d');
            $planilla->directora()->associate($id);
        }
        elseif (Entrust::can('aprobar-planillas-coord')) {
            $planilla->aprobacion_coordinadora=1;
            $planilla->fecha_aprobacion_coordinadora=date('Y-m-d');
            $planilla->coordinadora()->associate($id);          

        }

        if (!$planilla->confirmada) {
                return redirect()->route('planilla.normal')
            ->withErrors("Esta planilla aun sigue en revision debe esperar a ser confirmada");    
        }  

        $planilla->save();

        //Notificación de correo electrónico

        if (Entrust::can('aprobar-planillas-direc')) {
            if($planilla->aprobacion_directora){
                $user = User::where('id', $planilla->coordinadora_id)->first();
                $admin = User::where('id', $planilla->administradora_id)->first();
                $status="<label style='color: green;'><b> ha sido aprobada por la directora</b></label>";
                $data = array(
                    'planilla' => $planilla->id,
                    'first_name' => auth()->user()->first_name,
                    'last_name' => auth()->user()->last_name,
                    'oficina' => auth()->user()->oficina->oficina,
                    'fecha_planilla' => $planilla->m_a,
                    'status'=>$status
                );
                Mail::send('emails.aprobacion.aprobacion', $data, function ($message) use ($user, $admin) {
                $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                $message->subject('Aprobacion de planilla');
                $message->to($user->email,"$user->first_name $user->last_name");
                $message->bcc($admin->email,"$admin->first_name $admin->last_name");
                });
            }
        }elseif(Entrust::can('aprobar-planillas-coord')){
             $user = Role::where('id', 5)->first();
             $status="<label style='color: green;'><b> ha sido aprobada por la coordinadora</b></label>";

                    $data = array(
                        'planilla' => $planilla->id,
                        'first_name' => auth()->user()->first_name,
                        'last_name' => auth()->user()->last_name,
                        'oficina' => auth()->user()->oficina->oficina,
                        'fecha_planilla' => $planilla->m_a,
                        'status'=>$status
                    );

                    //return view('emails.aprobacion.aprobacion',$data);

                    $user=$user->users->first();
                    Mail::send('emails.aprobacion.aprobacion', $data, function ($message) use ($user) {
                        $message->from('notificacion@weeffect-podeeir.org', "WE EFFECT");
                        $message->subject('Aprobacion de planilla');
                        $message->to($user->email,"$user->first_name $user->last_name");
                    });
            
        }
        
        return redirect()->route('planilla.normal')
            ->withSuccess("Aprobada la planilla $planilla->m_a"); 
    }

    public function anulacion($id)
    {
        $planilla = Planilla::find($id);
        //$user_id = auth()->user()->id;

        if (Entrust::can('anular-planillas-coord')) {
            $planilla->aprobacion_coordinadora = 2;
        }
        elseif (Entrust::can('anular-planillas-direc')) {
            $planilla->aprobacion_directora = 2;
        }

        $planilla->save();

        return redirect()->route('planilla.normal')
            ->withSuccess("Anulada la planilla $planilla->m_a");   
    }

    function ajustes($value='')
    { 
        
        $pais=auth()->user()->oficina->pais;
        //dd($pais->id);
       
        if(Entrust::hasRole('Administradora')){
            $paises=Pais::where('id',$pais->id)->get();      
        }else{
           $paises=Pais::get();           
        }
        return view('planillas.ajustes',compact('paises'));
        //dd($Guatemala);
        
    }

    public function guardar_ajustes(Request $requests)
    {
        
        $pais=auth()->user()->oficina->pais;
        if(Entrust::hasRole('Administradora')){
            $paises=Pais::where('id',$pais->id)->get();           
        }else{
            $paises=Pais::get();
        }
        foreach ($paises as $pais) {          
           
            
            //Visibilidad de campo           
            if (isset($requests->pais[$pais->id]['campo_deducciones'])) {               
                $campo_deducciones=$requests->pais[$pais->id]['campo_deducciones'];
                $pais->campo_deducciones= implode(',', $campo_deducciones);
            }           
            //Porcentajes
            $pais->moneda_simbolo=$requests->pais[$pais->id]['moneda_simbolo'];
            $pais->moneda_nombre=$requests->pais[$pais->id]['moneda_nombre'];
            $pais->porcentaje_seguridad_social=$requests->pais[$pais->id]['porcentaje_seguridad_social'];
            $pais->porcentaje_pension=$requests->pais[$pais->id]['porcentaje_pension'];
            $pais->save();

            //Nombre campos
            
            if ($pais->campo) {
                $campo=$pais->campo;
            }else{
                $campo=new Campo;
                $campo->pais()->associate($pais->id);
            }
            $campo->salario_base=$requests->campo[$pais->id]['salario_base'];
            $campo->ajustes=$requests->campo[$pais->id]['ajustes'];
            $campo->total_salario=$requests->campo[$pais->id]['total_salario'];
            $campo->catorceavo=$requests->campo[$pais->id]['catorceavo'];
            $campo->prestamo=$requests->campo[$pais->id]['prestamo'];
            $campo->interes=$requests->campo[$pais->id]['interes'];
            $campo->otras_deducciones=$requests->campo[$pais->id]['otras_deducciones'];
            $campo->impuestos=$requests->campo[$pais->id]['impuestos'];
            $campo->total_deducciones=$requests->campo[$pais->id]['total_deducciones'];
            $campo->seguridad_social=$requests->campo[$pais->id]['seguridad_social_p'];
            $campo->seguridad_social_patronal=$requests->campo[$pais->id]['seguridad_social'];
            $campo->liquido=$requests->campo[$pais->id]['liquido'];     
            $campo->acumulado_aguinaldo=$requests->campo[$pais->id]['acumulado_aguinaldo'];    
            $campo->acumulado_indemnizacion=$requests->campo[$pais->id]['acumulado_indemnizacion'];    
                   
            $campo->save();               

        }
        
        return redirect()->route('planilla.ajustes')
            ->withSuccess('Ajustes guardados con exito'); 
    }    

    public function descargar_planilla($id)
    {       
        $planilla=Planilla::find($id);         

        $planilla->firma_administradora=$this->value_firma($planilla->confirmada,$planilla->administradora);        
        $planilla->firma_coordinadora=$this->value_firma($planilla->aprobacion_coordinadora,$planilla->coordinadora);        
        $planilla->firma_directora=$this->value_firma($planilla->aprobacion_directora,$planilla->directora);

        $oficina=$planilla->oficina;
        $pais=$oficina->pais;
        $fecha = $planilla->m_a;
        $campo=$pais->campo;
        $mes_14 = $pais->bono_14;
        $meses = Month::all();
    
        switch ($pais->id) {
            case 1://GUATEMALA
                //$planilla->catorceavo_mes=(str_contains($planilla->m_a, 'Junio'))?'Junio':'Julio';              
                $planilla->cell_aportes_patronales=2;
                $planilla->cell_aportes=5;
                $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones));
                
                break;
            case 2://BOLIVIA
                $planilla->cell_acumulados = 2;
                $planilla->cell_aportes_patronales=5;
                $planilla->cell_aportes=0;
                $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones))+6;
                break;
            case 3://NICARAGUA
                $planilla->cell_aportes_patronales=3;
                $planilla->cell_aportes=0;
                $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones))+2;
                break;
            case 4://HONDURAS
                $planilla->cell_aportes_patronales=3;
                $planilla->cell_aportes=0;
                $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones))+2;
                break;
            case 5://PARAGUAY
                $planilla->cell_aportes_patronales=3;
                $planilla->cell_aportes=0;
                $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones));              
                break;
            case 6://SALVADOR
                $planilla->cell_aportes_patronales=3;
                $planilla->cell_aportes=0;
                $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones))+1;
                break;

            case 7://COLOMBIA
                $planilla->cell_acumulados = 4;
                $planilla->cell_aportes_patronales = 9;
                $planilla->cell_aportes = 5;
                $planilla->cell_deducciones = 6;
            break;                   
            }  

        $year = $planilla->m_a;
        $year = explode('-', $year);
        $year = $year[1];

        if($pais->bono_14 != 'No Disponible' && $pais->bono_14 = substr($fecha, 0, -5))
        {
            $empleados = $planilla->empleados;
            $data_catorceavo = $this->acumuladoCatorceavo($empleados, $pais, $year);

            $meses_catorceavo = $data_catorceavo['meses_catorceavo'];
            $acumulado_empleados = $data_catorceavo['acumulado_empleados'];
            $acumulado_meses = $data_catorceavo['acumulado_meses'];
        }
        else 
        {
            $meses_catorceavo = '';
            $acumulado_empleados = 0;
            $acumulado_meses = 0;
        }

        if(str_contains($planilla->m_a,'Diciembre'))
        {
            $data_diciembre = $this->acumuladoDiciembre($planilla, $meses, $year);
            //dd($data_diciembre);
            $pension_meses = $data_diciembre['pension_meses'];
            $aguinaldo_meses = $data_diciembre['aguinaldo_meses'];
            $total_meses_aguinaldo = $data_diciembre['total_meses_aguinaldo'];
            $total_meses_pension = $data_diciembre['total_meses_pension'];
        }
        else
        {
            $pension_meses = 0;
            $aguinaldo_meses = 0;
            $total_meses_aguinaldo = 0;
            $total_meses_pension = 0;
        }


        $pdf = PDF::loadView('pdf.pdf_main_planilla',
            [
                'planilla' =>$planilla, 
                'oficina' =>$oficina,
                'campo' =>$campo,
                'pais' =>$pais,
                'year' => $year,
                'mes_14' => $mes_14,
                'meses' => $meses,
                'meses_catorceavo' => $meses_catorceavo,
                'pension_meses' => $pension_meses,
                'aguinaldo_meses' => $aguinaldo_meses,
                'total_meses_aguinaldo' => $total_meses_aguinaldo,
                'total_meses_pension' => $total_meses_pension,
                'acumulado_empleados' => $acumulado_empleados,
                'acumulado_meses' => $acumulado_meses,
            ]    
        )->setPaper('tabloid', 'landscape');


        return $pdf->download("Planilla $planilla->m_a.pdf");
                
        /*return view('pdf.pdf_main_planilla',[
                    'planilla' =>$planilla, 
                    'oficina' =>$oficina,
                    'campo' =>$campo,
                    'pais' =>$pais,
                    'year' => $year,
                    'mes_14' => $mes_14,
                    'meses' => $meses,
                    'meses_catorceavo' => $meses_catorceavo,
                    'pension_meses' => $pension_meses,
                    'aguinaldo_meses' => $aguinaldo_meses,
                    'total_meses_aguinaldo' => $total_meses_aguinaldo,
                    'total_meses_pension' => $total_meses_pension,
                    'acumulado_empleados' => $acumulado_empleados,
                    'acumulado_meses' => $acumulado_meses,
                    
                ] );*/

        Excel::create("Planilla $oficina->oficina $planilla->m_a" , function($excel) use ($fecha,$planilla,$oficina,$campo,$pais) 
        {   
            
            
            /*$excel->sheet('Planilla', function($sheet) use ($planilla,$oficina,$campo,$pais){

               
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('img/logo-p1.png')); //your image path  
                $objDrawing->setCoordinates('A2');              
                $objDrawing->setWorksheet($sheet);
  
                $sheet->protect('password');
                $sheet->loadView('excel.planilla.totales',
                    array(  'planilla' =>$planilla, 
                            'oficina' =>$oficina,
                            'campo' =>$campo,
                            'pais' =>$pais,
                            
                    )
                )->with('no_asset', true);
                
                
            });
            
            $excel->sheet('Aportes Patronales', function($sheet) use ($planilla,$oficina,$campo,$pais){

                
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('img/logo-p1.png')); //your image path  
                $objDrawing->setCoordinates('A2');              
                $objDrawing->setWorksheet($sheet);                
                
                $sheet->loadView('excel.planilla.aportes_patronales',
                    array(  'planilla' =>$planilla, 
                            'oficina' =>$oficina,
                            'campo' =>$campo,
                            'pais' =>$pais,
                            
                    )
                )->with('no_asset', true);
                
            });*/
            if (str_contains($planilla->m_a, 'Diciembre'))
            {
                $excel->sheet('Aguinaldos', function($sheet) use ($fecha,$planilla,$oficina,$campo,$pais){

                
                    $objDrawing = new PHPExcel_Worksheet_Drawing;
                    $objDrawing->setPath(public_path('img/logo-p1.png')); //your image path  
                    $objDrawing->setCoordinates('A2');              
                    $objDrawing->setWorksheet($sheet);
      

                    $sheet->loadView('excel.planilla.aguinaldo',
                        array(  'fecha' => $fecha,  
                                'planilla' =>$planilla, 
                                'oficina' =>$oficina,
                                'campo' =>$campo,
                                'pais' =>$pais,
                                
                        )
                    )->with('no_asset', true)
                    ->setStyle(
                        array(
                            'font' => array(
                                'name'      =>  'Calibri',                    
                            )
                        )   
                    );
                });

                if(!str_contains($oficina, 'Salvador')){
                    $excel->sheet('Indemnización', function($sheet) use ($fecha,$planilla,$oficina,$campo,$pais){

                        $objDrawing = new PHPExcel_Worksheet_Drawing;
                        $objDrawing->setPath(public_path('img/logo-p1.png')); //your image path  
                        $objDrawing->setCoordinates('A2');              
                        $objDrawing->setWorksheet($sheet);
          

                        $sheet->loadView('excel.planilla.indemnizacion',
                            array(  'fecha' => $fecha,  
                                    'planilla' =>$planilla, 
                                    'oficina' =>$oficina,
                                    'campo' =>$campo,
                                    'pais' =>$pais,
                                    
                            )
                        )->with('no_asset', true)
                        ->setStyle(
                            array(
                                'font' => array(
                                    'name'      =>  'Calibri',                    
                                )
                            )   
                        );
                    });

                    $excel->sheet('Pensión', function($sheet) use ($fecha,$planilla,$oficina,$campo,$pais){

                    
                        $objDrawing = new PHPExcel_Worksheet_Drawing;
                        $objDrawing->setPath(public_path('img/logo-p1.png')); //your image path  
                        $objDrawing->setCoordinates('A2');              
                        $objDrawing->setWorksheet($sheet);
          

                        $sheet->loadView('excel.planilla.pension',
                            array(  'fecha' => $fecha,  
                                    'planilla' =>$planilla, 
                                    'oficina' =>$oficina,
                                    'campo' =>$campo,
                                    'pais' =>$pais,
                                    
                            )
                        )->with('no_asset', true)
                        ->setStyle(
                            array(
                                'font' => array(
                                    'name'      =>  'Calibri',                    
                                )
                            )   
                        );
                    });
                }
            }

            $excel->setActiveSheetIndex(0)->download('pdf');        

        });
    }

    public function value_firma($confirmacion,$img)
    {       
            
            if($confirmacion==1){
                if($img->firma && file_exists("upload/users/$img->firma")){
                    $firma="<img src='upload/users/$img->firma'><br><br>
                            <label style='background: #4caf50 ;'>CONFIRMADA</label><br>";  
                }else{
                    $firma="<label style='background: #4caf50 ;'>CONFIRMADA</label><br>";
                }
            }else{
                $firma="<label style='background: #ffeb3b;'>EN REVISION</label><br>";
            }           
            return $firma;
        
    }

    private function spreadsheets($date, $pais, $mes)
    {
        $administradora=Auth()->user();
        $oficina=Auth()->user()->oficina;
        $edit=false;
        $year=date('Y');
        $fecha=Planilla::where('m_a',$date)->where('oficina_id',$oficina->id)->first();

        if ($fecha) {
            return redirect()->route('planilla.normal')
            ->withErrors("Al parecer la planilla del mes: ( $fecha->m_a ) ya fue elaborada");            
        }
        $fecha=$date;
        $months = Month::all();
        $users=User::with('cargo')->where('oficina_id',$oficina->id)->where('status',1)->whereNotIn('categoria_id', [3])->whereHas('roles', function($q){
            $q->whereNotIn('name',['Admin','Directora','WebMaster']);
        }
        )->get();
        //dd(count($users));
        
        //ajuste del bono 14, ahora es configurable desde ajustes
        
        if($pais->bono_14 != 'No Disponible' && str_contains($date, $pais->bono_14))
        {
            $aporteCatorceavo = new Aporte;
            $meses_catorceavo = $aporteCatorceavo->mesesCatorceavo($pais);
        }
        else 
        {
            $meses_catorceavo = false;
        }
        
        
        /*$meses_catorceavo=[                    
                    'Junio-'.($year-1),
                    'Julio-'.($year-1),
                    'Agosto-'.($year-1),
                    'Septiembre-'.($year-1),
                    'Octubre-'.($year-1),
                    'Noviembre-'.($year-1),
                    'Diciembre-'.($year-1),
                    'Enero-'.$year,
                    'Febrero-'.$year,
                    'Marzo-'.$year,
                    'Abril-'.$year,
                    'Mayo-'.$year,
            ];*/
        $meses_acumulados=[         
                    'Enero-'.$year,
                    'Febrero-'.$year,
                    'Marzo-'.$year,
                    'Abril-'.$year,
                    'Mayo-'.$year,
                    'Junio-'.$year,
                    'Julio-'.$year,
                    'Agosto-'.$year,
                    'Septiembre-'.$year,
                    'Octubre-'.$year,
                    'Noviembre-'.$year,
                    'Diciembre-'.$year                    
        ];
        //verifico que sea la planilla de julio para hacer el calculo del acumulado
                      
        $planilla_enero=Planilla::where('oficina_id',$oficina->id)->where('m_a','like','%'.$mes.'%')->get();

        $planilla_empleados=count($planilla_enero)?$planilla_enero->last()->empleados:NULL;
        //dd($planilla_empleados->where('user_id',"9"));
        //dd($planilla_empleados);
        foreach ($users as $user) {
            
            $acumulado=$user->acumulado;
            
            //ajuste en la condición bono 14
            if ($pais->bono_14 != 'No Disponible' && str_contains($date, $pais->bono_14)) {
                $catorcevo_total=$acumulado->where('oficina_id',$oficina->id)->whereIn('m_a',$meses_catorceavo);                          
                $user->catorceavo_total=$catorcevo_total->sum('catorceavo');
            }

            if (str_contains($date, 'Diciembre')) {   
                $acumulado_total=$acumulado->where('oficina_id',$oficina->id)->whereIn('m_a',$meses_acumulados);               
            }

            if($pais->id==6){
                    //aguinaldo exento
                    //$aex=503.40;
                    //aguinaldo agravado
                    //$aga=$user->salario_base-$aex;  
                    //$user->ag=$aex+$aga-$impuestos);
                $user->ag=0;
            }else{       
                $user->ag=number_format($user->salario_base*(8.33/100),2,'.','');
            }

            $user->pen=number_format($user->salario_base*((float)$pais->porcentaje_pension/100),2,'.','');
            $user->inde=number_format($user->salario_base*(8.33/100),2,'.','');

            if ($pais->tipo_seguridad_social_p=='porcentaje') {

                $user->seguridad_social_patronal=number_format($user->salario_base*((float)$pais->seguridad_social_p/100),2,'.','');
                //dd($user->seguridad_social_patronal);
            }
            else{
                
                $user->seguridad_social_patronal=number_format(((float)$pais->seguridad_social_p*1),2,'.','');
            }
            
            if ($pais->tipo_seguridad_social=='porcentaje') {
                $user->seguridad_social=number_format($user->salario_base*((float)$pais->porcentaje_seguridad_social/100),2,'.','');
            }
            else{

                $user->seguridad_social=number_format((float)$pais->porcentaje_seguridad_social*1,2,'.','');

            }
            
            $usuario=$planilla_empleados?$planilla_empleados->where('user_id',$user->id)->first():null;
            
            $user->impuesto_renta=$usuario?(float)$planilla_empleados->where('user_id',$user->id)->first()->deduccion->impuesto_renta:0;                     
            $user->prestamo=$usuario?$planilla_empleados->where('user_id',$user->id)->first()->deduccion->prestamo:0;         
            $user->interes=$usuario?$planilla_empleados->where('user_id',$user->id)->first()->deduccion->interes:0;         
            $user->otras_deducciones=$usuario?$planilla_empleados->where('user_id',$user->id)->first()->deduccion->otras_deducciones:0;
            $user->dias_trabajados = $usuario ? $planilla_empleados->where('user_id', $user->id)->first()->dias_trabajados : 30;     
            $user->ajuste = $usuario ? $planilla_empleados->where('user_id', $user->id)->first()->ajuste : 0;
            $user->ag=number_format($user->salario_base+$user->ajuste,2,'.','');
            
            switch ($pais->id) {
                case 1:
                //aportes planilla enero
                $user->bonificacion_incentivo=$usuario?$planilla_empleados->where('user_id',$user->id)->first()->aporte->bonificacion_incentivo:0; 
                $user->bonificacion_docto_37_2001=$usuario?$planilla_empleados->where('user_id',$user->id)->first()->aporte->bonificacion_docto_37_2001:0; 
                $user->reintegros=$usuario?$planilla_empleados->where('user_id',$user->id)->first()->aporte->reintegros:0; 
                               
                    break;
                case 2://BOLIVIA                   

                    //deducciones                    
                    $user->rc_iva=$usuario?$planilla_empleados->where('user_id',$user->id)->first()->deduccion->rc_iva:0;                    
                    $user->cta_ind=$user->salario_base*0.10; 
                    $user->riesgo=round($user->salario_base*0.0171,2);
                    $user->com_afp=round($user->salario_base*0.005,2); 
                    $user->afp_aporte_solidario=round($user->salario_base*0.005,2);
                    $user->afp_aporte_nacional_solidario=round( (($user->salario_base>13000)?($user->salario_base-13000)*0.01 : 0),2);
                    
                    //aportes patronales
                    $user->seguro_universitario       =round( $user->salario_base*(10/100),2);
                    $user->afp_prevision              =round( $user->salario_base*(1.71/100),2);
                    $user->afp_prevision_pnvs         =round( $user->salario_base*(2/100),2);
                    $user->aporte_afp_aporte_solidario       =round( $user->salario_base*(3/100),2);

                    break;
                case 3://NICARAGUA
                    //DEDUCCIINES PLANILLAENERO
                    $user->deduccion_1='';
                    $user->deduccion_2='';
                    
                    $user->deduccion_1=$usuario?$planilla_empleados->where('user_id',$user->id)->first()->deduccion->deduccion_1:0; 
                    $user->deduccion_2=$usuario?$planilla_empleados->where('user_id',$user->id)->first()->deduccion->deduccion_2:0;
                    
                    //aportes patronal planilla enero                    /
                    $user->INATEC=$usuario?$planilla_empleados->where('user_id',$user->id)->first()->aporte->INATEC:0;
                    break;
                case 4://HONDURAS 
                    //deducciones planilla enero
                    $user->seguro_medico=$usuario?$planilla_empleados->where('user_id',$user->id)->first()->deduccion->seguro_medico:0;
                    $user->rap=round( $user->salario_base*0.015,2);
                    //aportes patronales
                    $user->rap_patronal=round( $user->salario_base*0.015,2);
                    break;
                case 5:
    
                    break;
                case 6: //EL SALVADOR
                    //deduccion
                    $user->afp=round($user->salario_base*(7.25/100),2);
                    $user->afp_6_75 = round($user->salario_base*(7.75/100),2);
                break;

                case 7: //Colombia
                    $user->total_salario = $usuario ? $planilla_empleados->where('user_id', $user->id)->first()->total_salario : 0;
                    $user->auxilio_transporte = $usuario ? $planilla_empleados->where('user_id', $user->id)->first()->aporte->auxilio_transporte : 0;
                    //nuevos campos aportes
                    $user->intereses_cesantias = $usuario ? $planilla_empleados->where('user_id', $user->id)->first()->aporte->intereses_cesantias : 0;
                    $user->incap_licencia = $usuario ? $planilla_empleados->where('user_id', $user->id)->first()->aporte->incap_licencia : 0;
                    $user->aux_celular = $usuario ? $planilla_empleados->where('user_id', $user->id)->first()->aporte->aux_celular : 0;
                    $user->medios_transp = $usuario ? $planilla_empleados->where('user_id', $user->id)->first()->aporte->medios_transp : 0;
                    $user->otros_devengos = $usuario ? $planilla_empleados->where('user_id', $user->id)->first()->aporte->otros_devengos : 0;
                    
                    $user->bono_salud = $usuario ? $planilla_empleados->where('user_id', $user->id)->first()->aporte->bono_salud : 0;
                    $user->capacitacion = $usuario ? $planilla_empleados->where('user_id', $user->id)->first()->aporte->capacitacion : 0;
                    $user->seguro_medico = $usuario ? $planilla_empleados->where('user_id', $user->id)->first()->aporte->seguro_medico : 0;
                    $user->rte = $usuario ? $planilla_empleados->where('user_id', $user->id)->first()->deduccion->rte : 0;
                    //nuevo campo deduccion
                    $user->otros_descuentos_celulares = $usuario ? $planilla_empleados->where('user_id', $user->id)->first()->deduccion->otros_descuentos_celulares : 0;
                break;
            }  
        }

        $campos = explode(',', $pais->campo_deducciones);

        $data = [];
        $user = User::with('categoria')->get();

        foreach($user as $usr) {
            if($usr->categoria->id == 1 || $usr->categoria->id == 2) {
                $data[] = $usr;
            }
        }
        
        $iteration = [];

        foreach($data as $dt) {
            foreach($users as $emp) {
                if($dt->id == $emp->id) {
                    $iteration[] = $emp;
                }
            }
        }

        $users = $iteration;

        $permiso = new Permission();
        $rolesConfirmar = $permiso->rolesPermission('confirmar-planillas');
        $rolesAprobCoord = $permiso->rolesPermission('aprobar-planillas-coord');
        $rolesAprobDir = $permiso->rolesPermission('aprobar-planillas-direc');

        return view('planillas.nueva_normal',compact('users','oficina','fecha','pais','administradora','edit','year', 'campos', 'meses_catorceavo', 'rolesConfirmar', 'rolesAprobCoord', 'rolesAprobDir'));
    }

    public function seguroSocial(Request $request)
    {
        if (Entrust::hasRole('Administradora')) {

            $pais_id = Auth()->user()->oficina->pais_id;
            $oficinas = Pais::with('oficinas')->where('id', $pais_id)->first()->oficinas;
            $mensaje = [];
            $planillas = [];

            foreach($oficinas as $ofc) {
                $date = $request->mes . '-' .$request->year;
        
                $planilla = Planilla::where('m_a', $date)
                                    ->where('oficina_id', $ofc->id)
                                    ->first();
                if($planilla) {
                    $planilla->firma_administradora=$this->value_firma($planilla->confirmada,$planilla->administradora);        
                    $planilla->firma_coordinadora=$this->value_firma($planilla->aprobacion_coordinadora,$planilla->coordinadora);        
                    $planilla->firma_directora=$this->value_firma($planilla->aprobacion_directora,$planilla->directora);

                    $oficina = $planilla->oficina;
                    $pais = $oficina->pais;
                    $fecha = $planilla->m_a;
                    $campo = $pais->campo;

                    switch ($pais->id) {
                        case 1://GUATEMALA
                            $planilla->catorceavo_mes=(str_contains($planilla->m_a, 'Junio'))?'Junio':'Julio';              
                            $planilla->cell_aportes_patronales=2;
                            $planilla->cell_aportes=5;
                            $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones));
                            
                            break;
                        case 2://BOLIVIA
                            $planilla->cell_acumulados = 2;
                            $planilla->cell_aportes_patronales=5;
                            $planilla->cell_aportes=0;
                            $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones))+6;
                            break;
                        case 3://NICARAGUA
                            $planilla->cell_aportes_patronales=3;
                            $planilla->cell_aportes=0;
                            $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones))+2;
                            break;
                        case 4://HONDURAS
                            $planilla->cell_aportes_patronales=3;
                            $planilla->cell_aportes=0;
                            $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones))+2;
                            break;
                        case 5://PARAGUAY
                            $planilla->cell_aportes_patronales=3;
                            $planilla->cell_aportes=0;
                            $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones));              
                            break;
                        case 6://SALVADOR
                            $planilla->cell_aportes_patronales=3;
                            $planilla->cell_aportes=0;
                            $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones))+1;
                            break;
            
                        case 7://COLOMBIA
                            $planilla->cell_acumulados = 4;
                            $planilla->cell_aportes_patronales = 9;
                            $planilla->cell_aportes = 5;
                            $planilla->cell_deducciones = 6;
                        break;                   
                    }

                    $planillas[] = $planilla;
                }
            }

            if(isset($planillas[0])) {
                if(count($planillas) > 1) {
                    if($planillas[0] && $planillas[1]) {
                        foreach($planillas[1]->empleados as $first) {
                            $userI = $planillas[0]->empleados->push($first);
                        }
                        $planillas[0]->empleados = $userI;

                        if(isset($planillas[2])) {
                            foreach($planillas[2]->empleados as $second) {
                                $userII = $userI->push($second);
                            }
                            $planillas[0]->empleados = $userII;
                        }
                    }
                }

                $planilla = $planillas[0];
            } else {
                $planilla = 0;
            }

            if($planilla) {
                Excel::create("Planilla Seguro Social" , function($excel) use ($fecha,$planilla,$planillas,$oficina,$campo,$pais)
                {   
                    $excel->sheet('Planilla', function($sheet) use ($planilla,$planillas,$oficina,$campo,$pais){

                    
                        $objDrawing = new PHPExcel_Worksheet_Drawing;
                        $objDrawing->setPath(public_path('img/logo-p1.png')); //your image path  
                        $objDrawing->setCoordinates('A2');              
                        $objDrawing->setWorksheet($sheet);
        
                        $sheet->loadView('excel.seguro_social.planilla_normal',
                            array(  'planilla' =>$planilla,
                                    'planillas' => $planillas,
                                    'oficina' =>$oficina,
                                    'campo' =>$campo,
                                    'pais' =>$pais
                                )
                        )->with('no_asset', true);
                    });
                    
                    $excel->setActiveSheetIndex(0)->download('xls');        
                });
            }else {
                return redirect()->route('reportes')
                                ->withErrors("Al parecer no existen planillas para esa fecha o las mismas no estan confirmadas");
            }

        }else if(Entrust::hasRole('Coordinadora') || Entrust::hasRole('Directora') || Entrust::hasRole('Contralora') || Entrust::hasRole('Admin')) {
            
            $oficinas = Pais::with('oficinas')->where('id', $request->pais)->first()->oficinas;
            $mensaje = [];
            $planillas = [];

            foreach($oficinas as $ofc) {
                $date = $request->mes . '-' .$request->year;
        
                $planilla = Planilla::where('m_a', $date)
                                    ->where('oficina_id', $ofc->id)
                                    ->first();
                if($planilla) {
                    $planilla->firma_administradora=$this->value_firma($planilla->confirmada,$planilla->administradora);        
                    $planilla->firma_coordinadora=$this->value_firma($planilla->aprobacion_coordinadora,$planilla->coordinadora);        
                    $planilla->firma_directora=$this->value_firma($planilla->aprobacion_directora,$planilla->directora);

                    $oficina = $planilla->oficina;
                    $pais = $oficina->pais;
                    $fecha = $planilla->m_a;
                    $campo = $pais->campo;

                    switch ($pais->id) {
                        case 1://GUATEMALA
                            $planilla->catorceavo_mes=(str_contains($planilla->m_a, 'Junio'))?'Junio':'Julio';              
                            $planilla->cell_aportes_patronales=2;
                            $planilla->cell_aportes=5;
                            $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones));
                            
                            break;
                        case 2://BOLIVIA
                            $planilla->cell_acumulados = 2;
                            $planilla->cell_aportes_patronales=5;
                            $planilla->cell_aportes=0;
                            $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones))+6;
                            break;
                        case 3://NICARAGUA
                            $planilla->cell_aportes_patronales=3;
                            $planilla->cell_aportes=0;
                            $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones))+2;
                            break;
                        case 4://HONDURAS
                            $planilla->cell_aportes_patronales=3;
                            $planilla->cell_aportes=0;
                            $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones))+2;
                            break;
                        case 5://PARAGUAY
                            $planilla->cell_aportes_patronales=3;
                            $planilla->cell_aportes=0;
                            $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones));              
                            break;
                        case 6://SALVADOR
                            $planilla->cell_aportes_patronales=3;
                            $planilla->cell_aportes=0;
                            $planilla->cell_deducciones=count(explode(',', $planilla->campo_deducciones))+1;
                            break;
            
                        case 7://COLOMBIA
                            $planilla->cell_acumulados = 4;
                            $planilla->cell_aportes_patronales = 9;
                            $planilla->cell_aportes = 5;
                            $planilla->cell_deducciones = 6;
                        break;                   
                    }  

                    $planillas[] = $planilla;
                }

                if(isset($planillas[0])) {
                    if(count($planillas) > 1) {
                        if($planillas[0] && $planillas[1]) {
                            if(!isset($planillas[2])){
                                foreach($planillas[1]->empleados as $first) {
                                    $userI = $planillas[0]->empleados->push($first);
                                }
                                $planillas[0]->empleados = $userI;
                            }
    
                            if(isset($planillas[2])) {
                                foreach($planillas[2]->empleados as $second) {
                                    $userII = $userI->push($second);
                                }
                                $planillas[0]->empleados = $userII;
                            }
                        }
                    }
    
                    $planilla = $planillas[0];
                } else {
                    $planilla = 0;
                }
            }
            

            if($planilla) {
                Excel::create("Planilla Seguro Social" , function($excel) use ($fecha,$planilla,$planillas,$oficina,$campo,$pais)
                {   
                    $excel->sheet('Planilla', function($sheet) use ($planilla,$planillas,$oficina,$campo,$pais){

                    
                        $objDrawing = new PHPExcel_Worksheet_Drawing;
                        $objDrawing->setPath(public_path('img/logo-p1.png')); //your image path  
                        $objDrawing->setCoordinates('A2');              
                        $objDrawing->setWorksheet($sheet);
        
                        $sheet->loadView('excel.seguro_social.planilla_normal',
                            array(  'planilla' =>$planilla, 
                                    'planillas' => $planillas,
                                    'oficina' =>$oficina,
                                    'campo' =>$campo,
                                    'pais' =>$pais,
                                )
                        )->with('no_asset', true);
                    });
                    
                    $excel->setActiveSheetIndex(0)->download('xls');        
                });
            }else {
                return redirect()->route('reportes')
                                ->withErrors("Al parecer no existen planillas para esa fecha o las mismas no estan confirmadas");
            }
        }
    }

    private function filterUsers($users)
    {
        $data = [];
        $user = User::with('categoria')->get();

        foreach($user as $usr) {
            if($usr->categoria->id == 1 || $usr->categoria->id == 2) {
                $data[] = $usr;
            }
        }

        $iteration = [];

        foreach($data as $dt) {
            foreach($users as $emp) {
                if($dt->id == $emp->user_id) {
                    $iteration[] = $emp;
                }
            }
        }

        return $iteration;
    }

    private function acumuladoCatorceavo($empleados, $pais, $year)
    {
        $aporteCatorceavo = new Aporte;
        $meses_catorceavo = $aporteCatorceavo->mesesCatorceavo($pais);
        $acumulado_empleados = array();
        $acumulado_meses = [];
        
        foreach($empleados as $empleado)
        {
            $acumulado_empleados[$empleado->user_id] = array();

            if($empleado->acumulado->catorceavo) 
            {
                $user = User::where('id', $empleado->user_id)->first();
                
                foreach($meses_catorceavo as $mes)
                {
                    $acumulado = $user->acumulado->where('m_a', $mes)->first();

                    if($acumulado != null)
                    {
                        array_push($acumulado_empleados[$empleado->user_id], $acumulado->catorceavo);
                    }
                    else
                    {
                        array_push($acumulado_empleados[$empleado->user_id], '0.00');
                    }
                }   
            }
            else 
            {
                foreach($meses_catorceavo as $mes)
                {
                    array_push($acumulado_empleados[$empleado->user_id], '0.00');
                }

            } 
        }

        $total = 0;

        //Por último llenamos los totales por meses de los catorceavos acumulados para la planilla
        
        foreach($meses_catorceavo as $mes)
        {
            $total = 0; 

            foreach($empleados as $empleado)
            {
                $user = User::where('id', $empleado->user_id)->first();

                $acumulado = $user->acumulado->where('m_a', $mes)->first();
                
                if($acumulado != null)
                {
                    $total = $total + $acumulado->catorceavo;
                }
                else 
                {
                    $total = $total + 0;
                }
            }

            array_push($acumulado_meses, $total);
        }

        return [
            'acumulado_empleados' => $acumulado_empleados,
            'meses_catorceavo' => $meses_catorceavo,
            'acumulado_meses' => $acumulado_meses
        ];        
           
    }
    
    /*private function mesesCatorceavo($pais) 
    {
        $mes_14 = Month::where('month', $pais->bono_14)->first();
        $months = Month::all();
        $year = date('Y');
        $meses_catorceavo = array();

        //calculamos los meses del año anterior
        foreach($months as $key => $month)
        {
            if($month->id > $mes_14->id)
            {
                array_push($meses_catorceavo, $month->month.'-'.($year-1));
            }
        }

        //después los de este año
        foreach($months as $month)
        {
            if($month->id <= $mes_14->id)
            {
                array_push($meses_catorceavo, $month->month.'-'.($year));   
            }
        }
        
        return $meses_catorceavo;
    }*/

    public function acumuladoDiciembre($planilla, $meses, $year)
    {
        $pension_meses = array();
        $aguinaldo_meses = array();
        $total_meses_aguinaldo = array();
        $total_meses_pension = array();

        foreach($planilla->empleados as $empleado)
        {
            $pension_meses[$empleado->user_id] = array();
            $aguinaldo_meses[$empleado->user_id] = array();

            $user = User::where('id', $empleado->user_id)->first();
                
            foreach($meses as $mes)
            {
                $acumulado = $user->acumulado->where('m_a', $mes->month.'-'.$year)->first();
                //dd($acumulado);

                if($acumulado != null)
                {
                    array_push($pension_meses[$empleado->user_id], $acumulado->pension);
                    array_push($aguinaldo_meses[$empleado->user_id], $acumulado->aguinaldo);
                }
                else
                {
                    array_push($pension_meses[$empleado->user_id], '0.00');
                    array_push($aguinaldo_meses[$empleado->user_id], '0.00');
                }
            }    
        }

        $total_aguinaldo = 0;
        $total_pension = 0;

        //Por último llenamos los totales por meses de los aguinaldos para la planilla
        
        foreach($meses as $mes)
        {
            $total_aguinaldo = 0;
            $total_pension = 0; 

            foreach($planilla->empleados as $empleado)
            {
                $user = User::where('id', $empleado->user_id)->first();

                $acumulado = $user->acumulado->where('m_a', $mes->month.'-'.$year)->first();
                
                if($acumulado != null)
                {
                    $total_aguinaldo = $total_aguinaldo + $acumulado->aguinaldo;
                    $total_pension = $total_pension + $acumulado->pension;
                }
                else 
                {
                    $total_aguinaldo = $total_aguinaldo + 0;
                    $total_pension = $total_pension + 0;
                }
            }

            array_push($total_meses_aguinaldo, $total_aguinaldo);
            array_push($total_meses_pension, $total_pension);
        }

        return [
            'pension_meses' => $pension_meses,
            'aguinaldo_meses' => $aguinaldo_meses,
            'total_meses_aguinaldo' => $total_meses_aguinaldo,
            'total_meses_pension' => $total_meses_pension,
        ];
    }
}

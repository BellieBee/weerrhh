<?php

namespace Vanguard\Http\Controllers;

use Illuminate\Http\Request;
use Entrust;
use Vanguard\Planilla;
use Vanguard\Aporte;
use Vanguard\Deduccion;
use Vanguard\Acumulado;
use Vanguard\User;
use Vanguard\Pais;
use Vanguard\Campo;
use Vanguard\Oficina;
use Vanguard\Empleado_planilla_normal;
use PDF;
use Vanguard\Vacaciones;
use Vanguard\Viajes;
use Vanguard\Permiso;
use Vanguard\BonoSalud;
use Vanguard\Contrato;
use Vanguard\Month;
use Vanguard\Feriado;
use Vanguard\Actividad;
use Vanguard\Decision;
use Vanguard\OrdenCompra;
use Vanguard\PagoCompra;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Worksheet_Drawing;
use PHPExcel_Worksheet_PageSetup;


use Vanguard\Http\Requests;

class ReportesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-reportes-todos|ver-reportes-oficina');
    }
    
    public function index()
    {
        if(Entrust::can('ver-reportes-todos')){
            $oficinas=Oficina::get();
        }elseif (Entrust::can('ver-reportes-oficina')) {
            $oficinas[]=auth()->user()->oficina;
        } 
        $paises=Pais::get();       
        
        return view('reportes.reporte',compact('oficinas','paises'));
    }

    public function reporte_planillas(Request $request)
    {
        $planillas=Planilla::whereIn('oficina_id',$request->oficinas)->get();
       

        if ($request->confirmada) {
        
            $planillas=$planillas->where('confirmada','1');
                      
        }  
        
        if (!$request->fecha_todas) {
            $mes_año=$this->fecha_reportes($request);
           $planillas=$planillas->whereIn('m_a', $mes_año); 
        }        
        
        $planillas->fecha_inicio="$request->fecha_inicio";
        $planillas->fecha_fin="$request->fecha_fin";         
        
        //si  no encuentra alguna planilla
        
        if ($planillas->count()==0){
            return redirect()->route('reportes')
            ->withErrors("Al parecer no se encontro ninguna planilla ");
        }
        
                
        if ($request->tipo=="empleados") {
            $empleados_oficinas=array_map('intval', $request->empleados_oficinas);
        }
        
        foreach ($planillas as $planilla) {

            if ($request->tipo=="empleados") {
                $planilla->empleados=$planilla->empleados->whereIn('user_id',$empleados_oficinas);
                
            }
            if (count($request->oficinas)==1) {
                $planilla->cambio_mensual=1;                        
            }else{
                $planilla->cambio_mensual=$planilla->cambio;
            }
        }
        
        
        $oficinas = Oficina::whereIn('id',array_map('intval', $request->oficinas))->get();
        
        if ($request->tipo_doc == 'pdf') {
            $pdf = PDF::loadView('reportes.planillas.pdf_reporte_'.$request->tipo, compact('planillas','oficinas'))
                ->setPaper('a4', 'landscape');
        
            return $pdf->download("Reporte $request->tipo ".date("d-m-Y H:i:s").'.pdf');
        }
        else {
            Excel::create("Reporte Empleados ".date("d-m-Y H:i:s"), function($excel) use ($oficinas, $planillas) 
            {               
                $excel->sheet('Planilla', function($sheet) use ($planillas, $oficinas){

                    $objDrawing = new PHPExcel_Worksheet_Drawing;
                    $objDrawing->setPath(public_path('img/logo-p1.png')); //your image path  
                    $objDrawing->setCoordinates('A2');              
                    $objDrawing->setWorksheet($sheet);

                    $sheet->loadView('excel.reporte_empleado.empleados',
                        array(  'planillas' => $planillas, 
                                'oficinas' => $oficinas,
                            )
                        )->with('no_asset', true);
                });
            
                $excel->setActiveSheetIndex(0)->download('xls');        
            });

            //return view('excel.reporte_empleado.empleados', compact('planillas', 'oficinas'));
        }      
    }

    public function boleta_empleados(Request $request)
    {
        $datos=explode(',', $request->user);//0->user_id   1->oficina_id
      
        $m_a=$request->mes.'-'.$request->año;
        $year = $request->año;
        $planilla=planilla::where('m_a',"$m_a")->where('oficina_id',$datos[1])->first();
        //dd($planilla);
        if (is_null($planilla)){
            return redirect()->route('reportes')
            ->withErrors("Al parecer no se encontro ninguna planilla ");
        }               
        
        if ($datos[0]=='todos') {
            $empleados=$planilla->empleados;           
        }else{
            $empleados=$planilla->empleados->where('user_id',(integer)$datos[0]);
        }
        //dd($empleados);        
        
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        
        $fecha_hoy=$planilla->oficina->oficina." ".$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y');
        $pais=auth()->user()->oficina->pais;
        $bono_14 = $pais->bono_14;

        if ($request->mes == $pais->bono_14) {
            
            $aporteCatorceavo = new Aporte;
            $meses_catorceavo = $aporteCatorceavo->mesesCatorceavo($pais);
        }
        else {

            $meses_catorceavo = false;
        }

        if($request->mes == "Diciembre") {

            $data_diciembre = $this->acumuladoDiciembre($empleados, $meses, $year);
            $pension_meses = $data_diciembre['pension_meses'];
            $aguinaldo_meses = $data_diciembre['aguinaldo_meses'];
        }
        else {
            $pension_meses = false;
            $aguinaldo_meses = false;
        }

        if ($request->tipo_doc == 'pdf') {
            
            $pdf = PDF::loadView('reportes.boleta_empleados.pdf_boleta_empleados', compact('planilla','m_a', 'year', 'meses', 'bono_14', 'pension_meses', 'aguinaldo_meses', 'empleados','fecha_hoy','pais','meses_catorceavo'));
        
            return $pdf->download("Boleta de empleados $m_a.pdf");
        }
        else {
            Excel::create("Reporte Empleados ".date("d-m-Y H:i:s"), function($excel) use ($planilla, $m_a, $year, $meses, $bono_14, $pension_meses, $aguinaldo_meses, $empleados, $fecha_hoy, $pais, $meses_catorceavo) 
            {               
                $excel->sheet('Planilla', function($sheet) use ($planilla, $m_a, $year, $meses, $bono_14, $pension_meses, $aguinaldo_meses, $empleados, $fecha_hoy, $pais, $meses_catorceavo){

                    $objDrawing = new PHPExcel_Worksheet_Drawing;
                    $objDrawing->setPath(public_path('img/logo-p1.png')); //your image path  
                    $objDrawing->setCoordinates('A2');              
                    $objDrawing->setWorksheet($sheet);

                    $sheet->loadView('excel.boleta_empleados.boleta_empleados',
                        array(  'planilla' => $planilla, 
                                'm_a' => $m_a,
                                'year' => $year,
                                'meses' => $meses,
                                'bono_14' => $bono_14,
                                'pension_meses' => $pension_meses,
                                'aguinaldo_meses' => $aguinaldo_meses,
                                'empleados' => $empleados,
                                'fecha_hoy' => $fecha_hoy,
                                'pais' => $pais,
                                'meses_catorceavo' => $meses_catorceavo
                            )
                        )->with('no_asset', true);
                });
            
                $excel->setActiveSheetIndex(0)->download('xls');        
            });
        }
        
        //return view('reportes.boleta_empleados.pdf_boleta_empleados', compact('planilla','m_a', 'year', 'meses', 'bono_14', 'pension_meses', 'aguinaldo_meses', 'empleados','fecha_hoy','pais','campo','meses_catorceavo'));
    }

    public function reportes_vacaciones_permisos(Request $request)
    { 
        $empleados_oficinas = $request->empleados_oficinas;     
        
        switch ($request->tipo) {
           
            case 'vacaciones':
               $reporte = Vacaciones::whereIn('oficina_id',$request->oficinas)->whereIn('user_id', $empleados_oficinas);
               $campo = "aprobacion_directora";
            break;

            case 'permisos':
                $reporte=Permiso::whereIn('oficina_id',$request->oficinas)->whereIn('user_id',$empleados_oficinas);
                $campo="aprobacion_coordinadora";
            break;

            case 'viajes':
                $reporte = Viajes::whereIn('oficina_id', $request->oficinas)->whereIn('user_id', $empleados_oficinas);
                $campo = "aprobacion_coordinadora";
            break;

            case 'bono_salud':
                $reporte = BonoSalud::whereIn('oficina_id', $request->oficinas)->whereIn('user_id', $empleados_oficinas);
                $campo = "status";
            break;

            default:
               # code...
               break;
        }

        if ($request->confirmada) {
        
            $reporte=$reporte->where($campo,"1");
        }       
        
        if (!$request->fecha_todas) {
            $fecha_inicio=date('Y-m-d H:i:s',strtotime($request->fecha_inicio));
            $fecha_fin=date('Y-m-d H:i:s',strtotime($request->fecha_fin.'-31'));
            if($request->tipo == 'bono_salud') {
                $reporte=$reporte->whereBetween('fecha_solicitud', [$fecha_inicio, $fecha_fin])->get();
            }
            else {           
                $reporte=$reporte->whereBetween('created_at', [$fecha_inicio, $fecha_fin])->get();  
            }

        } else{
            $fecha_inicio="Todos los meses";
            $fecha_fin="";
            $reporte=$reporte->get();
        }
    
        $reporte->fecha_inicio = $fecha_inicio;
        $reporte->fecha_fin = $fecha_fin;
        $oficinas = Oficina::whereIn('id',$request->oficinas)->get();

        if ($request->tipo_doc == 'pdf') {
            
            $pdf = PDF::loadView('reportes.permiso_vacaciones.pdf_'.$request->tipo, compact('reporte','oficinas'));
            return $pdf->download("Reporte $request->tipo ".date('d-m-Y').".pdf");
        } 
        else {
            Excel::create("Reporte $request->tipo ".date('d-m-Y'), function($excel) use ($oficinas, $reporte, $request) 
            {               
                $excel->sheet($request->tipo, function($sheet) use ($reporte, $oficinas, $request){

                    $objDrawing = new PHPExcel_Worksheet_Drawing;
                    $objDrawing->setPath(public_path('img/logo-p1.png')); //your image path  
                    $objDrawing->setCoordinates('A2');              
                    $objDrawing->setWorksheet($sheet);

                    $sheet->loadView('excel.permiso_vacaciones.'.$request->tipo,
                        array(  'reporte' => $reporte, 
                                'oficinas' => $oficinas,
                            )
                        )->with('no_asset', true);
                });
            
                $excel->setActiveSheetIndex(0)->download('xls');        
            });
        }
    }
   
    public function ajax_liquidacion(Request $request)
    {
        $datos=explode(',', $request->id);//0->user_id   1->oficina_id
        $id=$datos[0];
        $user=User::find($id);
        $user->cargo;
        $empleado=$user->planilla->last();
        $planilla=$empleado->planilla;
        $pais=$user->oficina->pais;
        $pension=$user->acumulado->sum('pension');
        $catorceavo=$user->acumulado->sum('catorceavo');
        $total_deducciones=$empleado->deduccion->total_deducciones;
       //dd($user->planilla->sortBy('planilla_id')->first());
        
        $user->fecha_ingreso=$this->fecha_string($user->created_at);
        $user->fecha_finalizacion=$this->fecha_string(date('Y-m-d'));
        echo json_encode([
            'user' => $user,
            'planilla' => $planilla,
            'empleado' => $empleado,
            'pais' => $pais,
            'pension' => $pension,
            'catorceavo' => $catorceavo,
            'total_deducciones' => $total_deducciones,

        ]);
    }
     public function boleta_liquidacion(Request $request)
    {
        $user=User::find($request->user); 
        $datos=$request;
        if (count($user->planilla)==0){
            return redirect()->route('reportes')
            ->withErrors("Este usuario no se encontro en ninguna palnilla");
        }
        $user->fecha_ingreso=$this->fecha_string($user->created_at);
        $user->fecha_finalizacion=$this->fecha_string(date('Y-m-d'));       
        

        $pdf = PDF::loadView('reportes.boleta_liquidacion.pdf_liquidacion',compact('user','datos'));

        return $pdf->download("Reporte de liquidacion $user->first_name $user->last_name ".date('d-m-Y').".pdf");

        return view('reportes.boleta_liquidacion.pdf_liquidacion',compact('user','datos'));
    
    }
    public function fecha_string($fecha)
    {
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha_string=date("d", strtotime($fecha))." de ".$meses[date("n", strtotime($fecha))-1]." del ".date("Y", strtotime($fecha));
        return $fecha_string;
         
    }
    public function fecha_reportes($request)
    {
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        
        $mes_año=[];
        $meses=[     
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',              
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];
        if($fecha_inicio==$fecha_fin){
            $Fecha=strtotime($fecha_inicio);
            $mes=date("m",strtotime($fecha_inicio) );
            $mes=$meses[$mes-1];
            $año=date("Y",strtotime($fecha_inicio) );
            array_push($mes_año, $mes.'-'.$año);           
        }
        
        $fechaaamostar = $fecha_inicio;
        while(strtotime($fecha_fin) >= strtotime($fechaaamostar))
        {
            
                //echo "$fechaaamostar<br />";
                
                $mes=date("m",strtotime($fechaaamostar) );
                $mes=$meses[$mes-1];
                $año=date("Y",strtotime($fechaaamostar) );

                array_push($mes_año, $mes.'-'.$año);
                $fechaaamostar = date("Y-M", strtotime($fechaaamostar . " + 1 month"));
            
        }
        return $mes_año;
    }
    public function contratos(Request $request)
    {
        //dd($request->all());
        $contratos=Contrato::whereIn('user_id',$request->empleados_oficinas)
        ->whereIn('oficina_id',$request->oficinas)->orderBy('created_at','desc');   

        
        if ($request->status!="todos") {
            $contratos=$contratos->where('status',"$request->status");
        } 
       //dd($contratos->get())
       ;
        if (!$request->fecha_todas) {

            $fecha_inicio=date('Y-m-d H:i:s',strtotime($request->fecha_inicio));
            $fecha_fin=date('Y-m-d H:i:s',strtotime($request->fecha_fin));           
            $contratos=$contratos->whereBetween('created_at', [$fecha_inicio, $fecha_fin])->get();  
            $rango_fechas=$request->fecha_inicio."-".$request->fecha_fin;
        } else{

            $rango_fechas="Todos los meses";
            $contratos=$contratos->get();

        }
        if (count($contratos)==0){
            return redirect()->route('reportes')
            ->withErrors("Al parecer no se encontro ningun reporte ");
        }

        $contratos->rango_fechas=$rango_fechas;
        $oficinas=Oficina::whereIn('id',$request->oficinas)->get(); 
        //dd($contratos);

        $pdf = PDF::loadView('reportes.contratos.pdf_contratos',compact('contratos','oficinas'));
        
        return $pdf->download("Reporte Contratos ".date('d-m-Y').".pdf");
        
        return view('reportes.contratos.pdf_contratos',compact('contratos','oficinas'));
    }

    public function export_integrador(Request $request)
    {
        $oficinas = Oficina::whereIn('id',$request->oficinas)->get();

        //Permisos
        $empleados_oficinas = $request->empleados_oficinas;
        $reporte = Permiso::whereIn('oficina_id',$request->oficinas)->whereIn('user_id',$empleados_oficinas);
        $campo = "aprobacion_coordinadora";  
        
        if($request->confirmada) {
            $reporte = $reporte->where($campo,"1");
        }       
        
        if (!$request->fecha_todas) {
            $fecha_inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.'-31'));           
            $reporte = $reporte->whereBetween('created_at', [$fecha_inicio, $fecha_fin])->get();  
 
        }else {
            $fecha_inicio = "Todos los meses";
            $fecha_fin = "";
            $reporte = $reporte->get();
        }
        $reporte->fecha_inicio = $fecha_inicio;
        $reporte->fecha_fin = $fecha_fin;

        $rep = count($reporte);

        //Vacaciones
        $vacaciones = Vacaciones::whereIn('oficina_id',$request->oficinas)->whereIn('user_id',$empleados_oficinas);
        $campo = "aprobacion_directora";  
        
        if($request->confirmada) {
            $vacaciones=$vacaciones->where($campo,"1");
        }       
        
        if (!$request->fecha_todas) {
            $fecha_inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.'-31'));           
            $vacaciones = $vacaciones->whereBetween('created_at', [$fecha_inicio, $fecha_fin])->get();  
 
        }else {
            $fecha_inicio = "Todos los meses";
            $fecha_fin = "";
            $vacaciones = $vacaciones->get();
        }
        $vacaciones->fecha_inicio = $fecha_inicio;
        $vacaciones->fecha_fin = $fecha_fin;

        $vac = count($vacaciones);

        //Viajes de trabajo
        $viajes = Viajes::whereIn('oficina_id',$request->oficinas)->whereIn('user_id',$empleados_oficinas);
        $campo = "aprobacion_directora";  
        
        if($request->confirmada) {
            $viajes=$viajes->where($campo,"1");
        }       
        
        if (!$request->fecha_todas) {
            $fecha_inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.'-31'));           
            $viajes = $viajes->whereBetween('created_at', [$fecha_inicio, $fecha_fin])->get();  
 
        }else {
            $fecha_inicio = "Todos los meses";
            $fecha_fin = "";
            $viajes = $viajes->get();
        }
        $viajes->fecha_inicio = $fecha_inicio;
        $viajes->fecha_fin = $fecha_fin;

        $vij = count($viajes);

        //Feriados
        $feriados = Feriado::all();     
        
        if (!$request->fecha_todas) {
            $fecha_inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin.'-31'));           
            $feriados = Feriado::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->get();
        }else {
            $fecha_inicio = "Todos los meses";
            $fecha_fin = "";
            $feriados = $feriados;
        }
        $feriados->fecha_inicio = $fecha_inicio;
        $feriados->fecha_fin = $fecha_fin;

        $fer = count($feriados);

        //return $feriados;

        if($rep || $vac || $vij || $fer) {
            Excel::create("Reporte Integrador" , function($excel) use ($reporte, $oficinas, $vacaciones, $viajes, $feriados) 
            {
                $excel->sheet('Planilla', function($sheet) use ($reporte, $oficinas, $vacaciones, $viajes, $feriados) {                
                    $objDrawing = new PHPExcel_Worksheet_Drawing;
                    $objDrawing->setPath(public_path('img/logo-p1.png')); //your image path  
                    $objDrawing->setCoordinates('A2');              
                    $objDrawing->setWorksheet($sheet);
    
                    $sheet->loadView('excel.reporte_integrador.integrador',
                        array(  'reporte' => $reporte,
                                'oficinas' => $oficinas,
                                'vacaciones' => $vacaciones,
                                'viajes' => $viajes,
                                'feriados' => $feriados
                            )
                    )->with('no_asset', true);
                });
                
                $excel->setActiveSheetIndex(0)->download('xls');        
            });
        }else {
            return redirect()->route('reportes')
                            ->withErrors("No existe informacion para mostrar");
        }
    }

    public function dias_vacaciones(Request $request)
    {
        $users = User::where('oficina_id', $request->oficina)->whereNotIn('categoria_id', [3])->where('status', 1)->get();
        $oficina = Oficina::find($request->oficina);
        $dias_planilla = $this->conteoPlanilla($users, $oficina);
        $solicitudes = $this->conteoSolicitudes($users, $oficina);

        //dd($dias_planilla);
        
        $pdf = PDF::loadView('reportes.dias_vacaciones.pdf_dias_vacaciones', compact('oficina','users', 'dias_planilla', 'solicitudes'));
        
        return $pdf->download("Reporte de Días Vacaciones-".date('d-m-Y')."-".$oficina->oficina.".pdf");
    }

    public function colegas(Request $request)
    {
        $oficina = Oficina::find($request->oficina);
        $colegas = User::where('oficina_id', $request->oficina)->whereNotIn('categoria_id', [3])->whereNotIn('id', [202])->get();

        //return view('reportes.colegas.pdf_colegas', compact('colegas', 'oficina'));

        $pdf = PDF::loadView('reportes.colegas.pdf_colegas', compact('colegas', 'oficina'))->setPaper('a4', 'landscape');

        return $pdf->download("Reporte de Colegas-".date('Y-m-d')."-".$oficina->oficina.".pdf");
    }

    public function compras(Request $request)
    {
        //dd($request);

        switch ($request->tipo) {

            case 'actividades':
                $reporte = Actividad::whereIn('oficina_id', $request->oficinas);
            break;

            case 'decisiones': 
                $reporte = Decision::whereIn('oficina_id', $request->oficinas);
            break;

            case 'ordenescompra':
                $reporte = OrdenCompra::whereIn('oficina_id', $request->oficinas);
            break;

            case 'pagos':
                $reporte = PagoCompra::whereIn('oficina_id', $request->oficinas);
            break;

            default:
               # code...
            break;
        }

        if (!$request->fecha_todas) {
            $fecha_inicio = date('Y-m-d H:i:s', strtotime($request->fecha_inicio));
            $fecha_fin = date('Y-m-d H:i:s', strtotime($request->fecha_fin));
            $reporte = $reporte->whereDate('fecha', '>=', $fecha_inicio)->whereDate('fecha', '<=', $fecha_fin)->get();
        } 
        else {
            $fecha_inicio = "Todos los meses";
            $fecha_fin = "";
            $reporte = $reporte->get();
        }
        
        $reporte->fecha_inicio = $fecha_inicio;
        $reporte->fecha_fin = $fecha_fin;
        $reporte->tipo = $request->tipo;
        $oficinas = Oficina::whereIn('id', $request->oficinas)->get();

        //return view('reportes.compras.pdf_pagos', compact('reporte', 'oficinas'));

        $pdf = PDF::loadView('reportes.compras.pdf_'.$request->tipo, compact('reporte','oficinas'))->setPaper('a4', 'landscape');
        
        return $pdf->download("Reporte $request->tipo ".date('d-m-Y').".pdf");
    }

    private function conteoPlanilla($users, $oficina)
    {
        $year = 2019;
        $total_dias = array();

        foreach ($users as $user) 
        {
            if (count($user->planilla) == 0) 
            {
                array_push($total_dias, 0);
            }
            else
            {
                $count = 0;
                $dic = 0;

                foreach ($user->planilla as $planilla)
                {
                    if((substr($planilla->planilla->m_a, 0, -5) != 'Diciembre') && (substr($planilla->planilla->m_a, -4) >= $year))
                    {
                        $mes_planilla = Month::where('month', substr($planilla->planilla->m_a, 0, -5))->first();

                        if(substr($planilla->planilla->m_a, -4) > $year)
                        {
                            $count++;   
                        } 
                        elseif ($mes_planilla->id >= $oficina->pais->mes_vac && substr($planilla->planilla->m_a, -4) == $year) 
                        {
                            $count++;
                        }
                    }
                    if((substr($planilla->planilla->m_a, 0, -5) == 'Diciembre') && (substr($planilla->planilla->m_a, -4) >= $year))
                    {
                        $dic++;
                    }
                }

                $count = $oficina->pais->vacaciones * $count;
                //dd($count);

                if($dic != 0)
                {
                    $count = $count + ($oficina->pais->vac_dic * $dic);
                    //dd($count);
                }

                array_push($total_dias, $count);
            }
        }

        return $total_dias;
    }

    private function conteoSolicitudes($users, $oficina)
    {
        $solicitudes_array = array();
        $solicitudes_user = 0;

        foreach($users as $user)
        {
            /*$solicitudes_user = Vacaciones::whereYear('created_at','=',date('Y'))
                ->where('user_id',$user->id)->sum('num_dh');*/
            $solicitudes_user = Vacaciones::whereDate('created_at', '>=', '2019-'.$oficina->pais->mes_vac.'-01')
                ->where('user_id', $user->id)
                ->sum('num_dh');

            if($solicitudes_user != null)
            {
                array_push($solicitudes_array, $solicitudes_user);
            }
            else
            {                
                array_push($solicitudes_array, 0);
            }
        }

        return $solicitudes_array;
    }

    public function acumuladoDiciembre($empleados, $meses, $year)
    {
        $pension_meses = array();
        $aguinaldo_meses = array();

        foreach($empleados as $empleado)
        {
            $pension_meses[$empleado->user_id] = array();
            $aguinaldo_meses[$empleado->user_id] = array();

            $user = User::where('id', $empleado->user_id)->first();
                
            foreach($meses as $mes)
            {
                $acumulado = $user->acumulado->where('m_a', $mes.'-'.$year)->first();

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

        return [
            'pension_meses' => $pension_meses,
            'aguinaldo_meses' => $aguinaldo_meses
        ];
    }
}

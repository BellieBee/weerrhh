<?php

namespace Vanguard\Http\Controllers;

use Illuminate\Http\Request;
use Vanguard\Http\Requests;
use Vanguard\Http\Requests\FeriadoRequest;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Worksheet_Drawing;
use Entrust;
use DateTime;
use Carbon\Carbon;
use Vanguard\Feriado;
use Vanguard\Oficina;
use Vanguard\Pais;
use Vanguard\Month;
use Vanguard\User;
use Vanguard\Permiso;
use Vanguard\Vacaciones;
use Vanguard\Viajes;

class FeriadosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-feriados-todos|ver-feriados-pais');       
    }

    public function index()
    {
        $oficina = Auth()->user()->oficina;
        $pais = $oficina->pais->id;

        if (Entrust::can('ver-feriados-todos')) 
        {
            $feriados = Feriado::join('months', 'feriados.month_id', '=', 'months.id')->join('paises', 'feriados.pais_id', '=', 'paises.id')->select('feriados.*', 'months.month', 'paises.pais')->get();
            return view('feriados.list', compact('feriados'));
            
        }
        elseif (Entrust::can('ver-feriados-pais')) {
            $feriados = Feriado::join('months', 'feriados.month_id', '=', 'months.id')->join('paises', 'feriados.pais_id', '=', 'paises.id')->select('feriados.*', 'months.month', 'paises.pais')->where('pais_id', $pais)->get();
            return view('feriados.list', compact('feriados'));
        }
        else 
        {
            return redirect()->route('dashboard');
        }	
    }

    public function calendar()
    {
        if (Entrust::can('ver-feriados-todos') || Entrust::can('ver-feriados-pais'))
        {
            return view('feriados.calendar');
        }
        else 
        {
            return redirect()->route('dashboard');
        }
    }

    public function event()
    {
        $oficina = Auth()->user()->oficina;
        $pais = $oficina->pais->id;

        if (Entrust::can('ver-feriados-todos')) {
            
            $data = array();
            $id = Feriado::all()->lists('id');
            $fecha = Feriado::all()->lists('fecha');
            $descp = Feriado::all()->lists('descripcion_feriado');
            $color = Feriado::join('paises', 'feriados.pais_id', '=', 'paises.id')->select('feriados.*', 'paises.color')->orderBy('feriados.id', 'asc')->lists('color');
            $count = count($id);

            for ($i = 0; $i < $count; $i++)
            {
                $data[$i] = array(
                    "title" => $descp[$i],
                    "start" => $fecha[$i],
                    "allDay" => true,
                    "backgroundColor" => $color[$i],
                    "url" => route('feriados.calendar', $id[$i])
                );
            }

            return response()->json($data);
        }

        elseif (Entrust::can('ver-feriados-pais')) {
            $data = array();

            $id = Feriado::where('pais_id', $pais)->lists('id');
            $fecha = Feriado::where('pais_id', $pais)->lists('fecha');
            $descp = Feriado::where('pais_id', $pais)->lists('descripcion_feriado');
            $color = Feriado::join('paises', 'feriados.pais_id', '=', 'paises.id')->select('feriados.*', 'paises.color')->where('pais_id', $pais)->lists('color');
            $count = count($id);

            for ($i = 0; $i < $count; $i++)
            {
                $data[$i] = array(
                    "title" => $descp[$i],
                    "start" => $fecha[$i],
                    "allDay" => true,
                    "backgroundColor" => $color[$i],
                    "url" => route('feriados.calendar', $id[$i])
                );
            }

            return response()->json($data);
        }
    }

    public function create()
    {
        if (Entrust::can('crear-feriado')) 
        {
            $paises = Pais::All();
            $meses = Month::All();

            return view('feriados.create', compact('paises', 'meses'));
        }

        elseif(Entrust::can('crear-feriado-pais'))
        {
            $oficina = Auth()->user()->oficina;
            $pais = $oficina->pais;
            $meses = Month::All();

            return view('feriados.create', compact('pais', 'meses'));
        }
        
    }

    public function store(FeriadoRequest $request)
    {
    	Feriado::create($request->all());

    	return redirect()->route('feriados.list')
    		->withSuccess("El día feriado fue creado con éxito");

    }

    public function edit($id)
    {
    	$feriado = Feriado::findOrFail($id);
        $paises = Pais::all();
        $meses = Month::all();
        //dd($feriado);
    	return view('feriados.edit', compact('paises', 'feriado', 'meses'));
    }

    public function update(FeriadoRequest $request, Feriado $feriado)
    {
    	$feriado = Feriado::where('id', $request['id'])->first();

    	$feriado->dia = $request['dia'];
        $feriado->month_id = $request['month_id'];
    	$feriado->pais_id = $request['pais_id'];
    	$feriado->descripcion_feriado = $request['descripcion_feriado'];
        $feriado->fecha = $request['fecha'];        
    	$feriado->save();

    	return redirect()->route('feriados.list')
    		->withSuccess("El día feriado fue actualizado con éxito");
    }

    public function destroy($id)
    {
    	$feriado = Feriado::findOrFail($id);
    	$feriado->delete();

    	return redirect()->route('feriados.list')
    		->withSuccess("El día feriado fue borrado con éxito");
    }

    public function export()
    {
        $meses = Month::All();
        $paises_consulta = Pais::All();
        $paises_nombre = $paises_consulta->lists('pais');
        $paises = $paises_nombre->toArray();
        //dd($oficinas);
        $ano = date('Y');
        $oficina = Auth()->user()->oficina;
        $pais = $oficina->pais;

        $calendario = '';
        $feriados = '';
        $dia = '';
        $leyend = '';
        $desc = '';
        
        Excel::create("Feriados del año $ano", function ($excel) use ($feriados, $dia, $leyend, $desc, $paises, $pais, $meses, $ano, $calendario, $oficina) 
        {
        	foreach ($meses as $mes) {

        		if ($mes->id) {

        			$calendario = $this->mes_dias($mes->id);
                    $feriados = $this->feriadosArray($mes->id);

                    $dia = $feriados[$mes->id]['dia'];
                    $leyend = $feriados[$mes->id]['pais'];
                    $desc = $feriados[$mes->id]['descripcion_feriado'];

                    $excel->sheet("$mes->month", function ($sheet) use ($feriados, $dia, $desc, $leyend, $paises, $pais, $mes, $ano, $calendario, $oficina) 
                    {
                        $objDrawing = new PHPExcel_Worksheet_Drawing;
                        $objDrawing->setPath(public_path('img/logo-p.png'));
                        $objDrawing->setCoordinates('A2');
                        $objDrawing->setWorksheet($sheet);
                            
                        $sheet->loadView('excel.feriado.calendario',
                            array(  'feriados' => $feriados,
                                    'dia' => $dia,
                                    'leyend' => $leyend,
                                    'desc' => $desc,
                                    'paises' => $paises,
                                    'pais' => $pais,
                                    'mes' => $mes,
                                    'ano' => $ano,
                                    'calendario' => $calendario,
                                    'oficina' => $oficina
                            )
                        )->with('no_asset', true);
                    });     
            	}	
            }
            $excel->setActiveSheetIndex(0)->download('xls');
        });
    }


    //////////////////////// INTEGRADOR //////////////////////////////////////

    public function calendarIntegral()
    {
        // $user = Auth()->user();

        // $pais = Auth()->user()->oficina->pais;

        // // return $color = Feriado::join('paises', 'feriados.pais_id', '=', 'paises.id')
        // //                 ->select('feriados.*', 'paises.color')
        // //                 ->where('pais_id', $pais->id)
        // //                 ->orderBy('feriados.id', 'asc')
        // //                 ->get();

        // $table = Feriado::select('id', 'fecha', 'descripcion_feriado')
        //         ->where('pais_id', $pais->id)
        //         ->union(Permiso::select('id', 'fecha_inicio', 'tipo')
        //         ->where('user_id', $user->id))
        //         ->union(Permiso::select('id', 'fecha_fin', 'tipo')
        //         ->where('user_id', $user->id))
        //         ->union(Vacaciones::select('id', 'fechas', 'dh')
        //         ->where('user_id', $user->id))
        //         ->get();

        // $vars = [];

        // foreach($table as $key => $days) {
        //     $dates = $days->fecha;
        //     $dates = explode(',', $dates);
            
        //     if(count($dates) >= 2) {
        //         foreach($dates as $dat) {
        //             $vars[] = array(
        //                 'id' => 1,
        //                 'fecha' => $dat,
        //                 'descripcion_feriado' => 'Vacaciones'
        //             );
        //         }
        //         $days->fecha = '0000-00-00';
        //     }
        // }

        // return $table;

        // $count = count($vars);

        // if($count != 0) {
        //    // $table = $table->push($vars[0]);
        //    $table = array_push($table, 'hola');
        // }

        // return $table;

        // foreach($table as $key => $news) {

        //     $variable = $news->fecha;
            
        //     // if($news->fecha) {
        //     //     $date = $news->fecha;
        //     //     $news->fecha = Carbon::parse($date)->toDateTimeString();
        //     // }
        // }

        // return print_r($table);

        // $new_table = $table->all();

        // $collect = [];
        

        // foreach($new_table as $key => $news) {

        //     $collect[$key] = $news;
            
        //     // if($news->fecha) {
        //     //     $date = $news->fecha;
        //     //     $news->fecha = Carbon::parse($date)->toDateTimeString();
        //     // }
        // }

        // //return $new_table;

        // return $collect;

        // return $new_table;

        // return $table;

        // return $vars;








        // ////////////////////////////////////////////////

        // $new_table = [];

        // foreach($vars as $var) {
        //     $table = $table->push($var);
        // }

        // return $table;

        // foreach($table as $news) {
        //     if($news->fecha) {
        //         $date = $news->fecha;
        //         $news->fecha = Carbon::parse($date)->toDateTimeString();
        //     }
        // }

        // return var_dump($date);

        // return $table;

        $pais = Auth()->user()->oficina->pais;
        if (Entrust::hasRole('Administradora') || Entrust::hasRole('Coordinadora') || Entrust::hasRole('Admin') || Entrust::hasRole('Directora') || Entrust::hasRole('Contralora'))
        {
            return view('reportes.integrador.calendar', ['pais' => $pais]);
        }
        else 
        {
            return redirect()->route('dashboard');
        }
    }

    public function eventIntegral()
    {
        $user = Auth()->user();
        $oficina = $user->oficina;
        $pais = $oficina->pais->id;

        if (Entrust::hasRole('Administradora') || Entrust::hasRole('Admin')) {

            $data = array();

            $table = Feriado::select('id', 'fecha', 'descripcion_feriado')
                            ->where('pais_id', $pais)
                            ->union(Permiso::select('id', 'fecha_inicio', 'tipo')
                            ->where('user_id', $user->id))
                            ->union(Permiso::select('id', 'fecha_fin', 'tipo')
                            ->where('user_id', $user->id))
                            ->union(Vacaciones::select('id', 'fechas', 'dh')
                            ->where('user_id', $user->id))
                            ->get();

            foreach($table as $days) {
                $dates = $days->fecha;
                $dates = explode(',', $dates);
                if(count($dates) >= 2) {
                    $days->fecha = $dates[0];
                    $days->descripcion_feriado = 'Vacaciones';
                }
            }

            foreach($table as $news) {
                $date = $news->fecha;
                $news->fecha = Carbon::parse($date)->toDateTimeString();
            }

            $id = $table->lists('id');
            $fecha = $table->lists('fecha');
            $descp = $table->lists('descripcion_feriado');
            $count = count($id);

            for ($i = 0; $i < $count; $i++)
            {
                $data[$i] = array(
                    "title" => $descp[$i],
                    "start" => $fecha[$i],
                    "url" => route('integrador.calendar', $id[$i])
                );
            }

            return response()->json($data);
        }

        elseif (Entrust::hasRole('Coordinadora') || Entrust::hasRole('Directora') || Entrust::hasRole('Contralora')) {
            
            $data = array();
            $table = Feriado::select('id', 'fecha', 'descripcion_feriado')
                            ->union(Permiso::select('id', 'fecha_inicio', 'tipo'))
                            ->union(Permiso::select('id', 'fecha_fin', 'tipo'))
                            ->union(Vacaciones::select('id', 'fechas', 'dh'))
                            ->get();

            foreach($table as $days) {
                $dates = $days->fecha;
                $dates = explode(',', $dates);
                if(count($dates) >= 2) {
                    $days->fecha = $dates[0];
                    $days->descripcion_feriado = 'Vacaciones';
                }
            }

            foreach($table as $news) {
                $date = $news->fecha;
                $news->fecha = Carbon::parse($date)->toDateTimeString();
            }

            $id = $table->lists('id');
            $fecha = $table->lists('fecha');
            $descp = $table->lists('descripcion_feriado');
            $count = count($id);

            for ($i = 0; $i < $count; $i++)
            {
                $data[$i] = array(
                    "title" => $descp[$i],
                    "start" => $fecha[$i],
                    "url" => route('feriados.calendar', $id[$i])
                );
            }

            return response()->json($data);
        }
    }


    ///////////////////////////////////////////////////////////////////////////////

    function mes_dias($mes)
    {
    	$dias_mes = cal_days_in_month(CAL_GREGORIAN, $mes, date('Y'));
		$semana = 1;

		for ($i = 1; $i <= $dias_mes; $i++) {
	
			$dia_semana = date('N', strtotime(date('Y-' . $mes) . '-' . $i ));
	
			$calendario[$semana][$dia_semana] = $i;

			if ( $dia_semana == 7 )
				$semana++;
		}

		return $calendario;
    }

    function feriadosArray($mes)
    {
        $feriados = Feriado::All();
        $oficina = Auth()->user()->oficina;
        $pais_admin = $oficina->pais->id;

        if(Entrust::hasRole('Administradora') || Entrust::hasRole('Admin'))
        {
            $feriado_dia = $feriados->where('month_id', $mes)->where('pais_id', $pais_admin)->lists('dia');
            $dia = $feriado_dia->toArray();

            $feriado_pais = $feriados->where('month_id', $mes)->where('pais_id', $pais_admin)->lists('pais_id');
            $pais = $feriado_pais->toArray();

            $feriado_desc = $feriados->where('month_id', $mes)->where('pais_id', $pais_admin)->lists('descripcion_feriado');
            $desc = $feriado_desc->toArray();
        }
        elseif(Entrust::hasRole('Coordinadora'))
        {
            $feriado_dia = $feriados->where('month_id', $mes)->lists('dia');
            $dia = $feriado_dia->toArray();

            $feriado_pais = $feriados->where('month_id', $mes)->lists('pais_id');
            $pais = $feriado_pais->toArray();

            $feriado_desc = $feriados->where('month_id', $mes)->lists('descripcion_feriado');
            $desc = $feriado_desc->toArray();
        }


        $feriado_mes[$mes] = array(
            'dia' => $dia,
            'pais' => $pais,
            'descripcion_feriado' => $desc 
        );

        return $feriado_mes;
    }

    public function actualizarFeriados()
    {
        $feriados = Feriado::All();

        foreach ($feriados as $fecha) {
            
            $actualizaFecha = date_add(date_create($fecha->fecha), date_interval_create_from_date_string('1 year'));
            $fecha->fecha = $actualizaFecha;
            $fecha->save();
        }

        $this->info('Las fechas de los feriados han sido actualizadas correctamente');
    }

}

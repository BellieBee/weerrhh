<?php

namespace Vanguard\Http\Controllers;

use Illuminate\Http\Request;

use Vanguard\Http\Requests;
use Vanguard\Adenda;
use Vanguard\Contrato;
use Carbon\Carbon;
class AdendaController extends Controller
{
    public function create(Request $request)
    {	
    	$contrato=Contrato::find($request->contrato_id);

     	$adenda=(new Adenda)->fill($request->all());
		$adenda->fecha_contrato=$contrato->fecha_fin;		
     	//$adenda->fecha_cumplimineto=$contrato->cumplimiento; 
     	$adenda->save();

     	$contrato->fecha_fin=$request->fecha_contrato_nueva;    
        //$contrato->cumplimiento=$request->fecha_cumplimiento_nueva;
        $contrato->cumplimiento="";
     	$contrato->cumplimiento="";
     	$contrato->fecha_aprobacion_coordinadora="";
        $contrato->aprobacion_coordinadora=0;

        $contrato->fecha_aprobacion_directora="";
        $contrato->aprobacion_directora=0;

        $contrato->status=0;

     	$fecha_fin=$contrato->fecha_fin;
        $fecha_inicio=$contrato->fecha_inicio;
        $contrato->tiempo_contrato=Carbon::parse($fecha_fin)->diffInDays(Carbon::parse($fecha_inicio));
     	$contrato->save();

     	return redirect()->route('contratos.list')
            ->withSuccess('Adenda registrada con exito');  

    }
    public function ajax_list($id)
    {
    	$adendas=Adenda::where('contrato_id',$id)->orderBy('id','desc')->get();
    	$tabla="";
    	foreach ($adendas as $adenda) {
    		$tabla.="
    		<tr>
    			<td  align='center' valign='middle'>$adenda->created_at</td>    			
    			<td>$adenda->fecha_contrato -> $adenda->fecha_contrato_nueva</td>
    			<!--<td>$adenda->fecha_cumplimineto -> $adenda->fecha_cumplimiento_nueva </td>-->    			
    			<td>$adenda->motivo</td> 
    			<td>
    				<a href='".url("/adenda/delete/$adenda->id")."'  class='btn btn_color btn_rojo' 
                        title='Eliminar Adenda '
                        data-toggle='tooltip'
                        data-placement='top'
                        data-method='DELETE'
                        data-confirm-title='Eliminar adenda '
                        data-confirm-text='La fecha de finalizacion se cambiara a la fecha que tenia antes o a la adendas mas reciente'
                        data-confirm-delete='Borrar'
                        >
                        <i class='glyphicon glyphicon-trash'></i>
                    </a>
                </td>
                <!--<td>
                    <a class='btn btn_color btn_rojo' href='".url("/adenda/descargar/$adenda->id")."'>
                        PDF
                    </a>    
                </td>-->   			
    		</tr>
    		";
    	}
    	echo json_encode($tabla);
    }
    public function delete($id)
    {
    	$adenda=Adenda::find($id);    	
    	$contrato=$adenda->contrato;
    	
    	if(count($contrato->adendas)==1) {
    		$contrato->fecha_fin=$adenda->fecha_contrato;
    		$contrato->cumplimiento=$adenda->fecha_cumplimiento;
    		$adenda->delete();
    	}else{
    		$adenda->delete();
    		$contrato->fecha_fin=$contrato->adendas->last()->fecha_contrato_nueva;
    		$contrato->cumplimiento=$contrato->adendas->last()->fecha_cumplimiento_nueva;
    	}        
    	
    	$contrato->save();
    	//$contrato->aprobacion_directora=0;
    	//$contrato->aprobacion_coordinadora=0;
    	//dd($contrato);
    	return redirect()->route('contratos.list')
            ->withSuccess('Adenda Eliminada con exito');  
    }
}

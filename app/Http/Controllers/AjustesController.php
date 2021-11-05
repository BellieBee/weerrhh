<?php

namespace Vanguard\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Vanguard\Http\Requests;

use Entrust;
use Vanguard\Oficina;
use Vanguard\Aporte;
use Vanguard\Deduccion;
use Vanguard\Acumulado;
use Vanguard\User;
use Vanguard\Pais;
use Vanguard\Campo;
use Vanguard\Cargo;
use Vanguard\Profesion;
use Vanguard\Motivo_permiso;
use Vanguard\Month;
use Vanguard\Vacaciones;
use Vanguard\TipoCompra;
use Vanguard\TipoDocumentoCompra;

class AjustesController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ver-ajustes-todos|ver-ajustes-oficina');
        
    }
    public function index($value='')
    {
   	$pais=auth()->user()->oficina->pais;
        //dd($pais->id);
       	$cargos=Cargo::orderBy('cargo')->get();
        $profesiones=Profesion::orderBy('profesion')->get();
        $motivos=Motivo_permiso::orderBy('motivo')->get();
        $months = Month::get();
        $tiposCompra = TipoCompra::get();
        $tiposDoc = TipoDocumentoCompra::get();
        $correlativo=DB::table('settings')->where('key', 'correlativo')->first();
        //dd($motivos);
        if(Entrust::can('ver-ajustes-todos')){
          $paises=Pais::get();           
          $oficinas=Oficina::get(); 
        }
        elseif(Entrust::can('ver-ajustes-oficina')){
            $paises=Pais::where('id',auth()->user()->oficina->pais->id)->get();      
            $oficinas=Oficina::where('id',auth()->user()->oficina->id)->get();      
        }

        return view('ajustes.ajustes',compact('paises','oficinas','cargos','profesiones','motivos', 'months', 'tiposCompra', 'tiposDoc', 'correlativo'));
        
    }
    public function store(Request $requests)
    {

        $pais=auth()->user()->oficina->pais;
        $cargos=Cargo::get();
        $profesiones=Profesion::get();
        $motivos=Motivo_permiso::get();
        $tiposCompra = TipoCompra::get();
        $mesDesde = 0;
        if(Entrust::can('ver-ajustes-todos')){
            $paises=Pais::get();           
            $oficinas=Oficina::get();           
        }
        elseif(Entrust::can('ver-ajustes-oficina')){
            $paises=Pais::where('id',auth()->user()->oficina->pais->id)->get();      
            $oficinas=Oficina::where('id',auth()->user()->oficina->id)->get();      
        }
        foreach ($paises as $pais) {          
           
  
            //Visibilidad de campo           
            if (isset($requests->pais[$pais->id]['campo_deducciones'])) {               
                $campo_deducciones=$requests->pais[$pais->id]['campo_deducciones'];
                $pais->campo_deducciones= implode(',', $campo_deducciones);
            } else{
                $pais->campo_deducciones="";
            }
            
            
              
            //Porcentajes
            $pais->moneda_simbolo=$requests->pais[$pais->id]['moneda_simbolo'];
            $pais->moneda_nombre=$requests->pais[$pais->id]['moneda_nombre'];
            $pais->salario_minimo = $requests->pais[$pais->id]['salario_minimo'];
            $pais->bono_14 = $requests->pais[$pais->id]['bono_14'];
            $pais->porcentaje_pension=$requests->pais[$pais->id]['porcentaje_pension'];
            
            $pais->tipo_seguridad_social=$requests->pais[$pais->id]['tipo_seguridad_social'];
            $pais->porcentaje_seguridad_social=$requests->pais[$pais->id]['porcentaje_seguridad_social'];
            
            $pais->tipo_seguridad_social_p=$requests->pais[$pais->id]['tipo_seguridad_social_p'];
            $pais->seguridad_social_p=$requests->pais[$pais->id]['seguridad_social_p'];

            if($pais->id == 7) {
                $pais->porcentaje_fsp = $requests->pais[$pais->id]['porcentaje_fsp'];
                $pais->porcentaje_arl = $requests->pais[$pais->id]['porcentaje_arl'];
                $pais->porcentaje_parafiscales = $requests->pais[$pais->id]['porcentaje_parafiscales'];
                $pais->porcentaje_eps = $requests->pais[$pais->id]['porcentaje_eps'];
                $pais->porcentaje_caja_opcion = $requests->pais[$pais->id]['porcentaje_caja_opcion'];
                $pais->porcentaje_icbf = $requests->pais[$pais->id]['porcentaje_icbf'];
                $pais->porcentaje_sena = $requests->pais[$pais->id]['porcentaje_sena'];
                $pais->porcentaje_incapacidad = $requests->pais[$pais->id]['porcentaje_incapacidad'];
            }

            $pais->n_horas=$requests->pais[$pais->id]['n_horas'];
            $pais->n_dias=$requests->pais[$pais->id]['n_dias'];

            $pais->vacaciones=$requests->pais[$pais->id]['vacaciones'];
            $pais->vac_dic=$requests->pais[$pais->id]['vac_dic'];

            if($requests->pais[$pais->id]['mes_vac'] != $pais->mes_vac)
            {
              $mesDesde = $requests->pais[$pais->id]['mes_vac'];
              $vacaciones = new Vacaciones;
              $vacaciones->acumulate($mesDesde, $pais);
            }

            $pais->mes_vac = $requests->pais[$pais->id]['mes_vac'];
            $pais->month = $requests->pais[$pais->id]['meses'];

            //campos del Bono Salud

            $pais->tasa_conv_usd = $requests->pais[$pais->id]['tasa_conv_usd'];
            $pais->tasa_conv_corona = $requests->pais[$pais->id]['tasa_conv_corona'];
            $pais->corona_sueca = $requests->pais[$pais->id]['corona_sueca'];

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
            $campo->seguridad_social=$requests->campo[$pais->id]['seguridad_social'];
            $campo->seguridad_social_patronal=$requests->campo[$pais->id]['seguridad_social_p'];
            $campo->liquido=$requests->campo[$pais->id]['liquido'];     
            $campo->acumulado_aguinaldo=$requests->campo[$pais->id]['acumulado_aguinaldo'];    
            $campo->acumulado_indemnizacion=$requests->campo[$pais->id]['acumulado_indemnizacion'];    
                   
            $campo->save();               

        }

        foreach ($oficinas as $oficina ) {
        	 $oficina->telf=$requests->oficina[$oficina->id]['telf'];	
        	 $oficina->nit=$requests->oficina[$oficina->id]['nit'];	
        	 $oficina->num_patronal=$requests->oficina[$oficina->id]['num_patronal'];	
        	 $oficina->direccion=$requests->oficina[$oficina->id]['direccion'];

           //campos de Compras
            $oficina->imp_pago_compra = $requests->oficina[$oficina->id]['imp_pago_compra'];
            $oficina->isr_pago_compra = $requests->oficina[$oficina->id]['isr_pago_compra'];
            $oficina->correlativo_solicitud = $requests->oficina[$oficina->id]['correlativo_solicitud'];
            $oficina->correlativo_decision = $requests->oficina[$oficina->id]['correlativo_decision'];
            $oficina->correlativo_compra = $requests->oficina[$oficina->id]['correlativo_compra'];	
        	 $oficina->save();
        }
        
        foreach ($cargos as $cargo) {
        	 $cargo->cargo=$requests->cargo[$cargo->id]['cargo'];
           $cargo->superior_id = $requests->cargo[$cargo->id]['superior_id'];
			     $cargo->save();
        	
        }

        foreach ($profesiones as $profesion) {
        	 $profesion->profesion=$requests->profesion[$profesion->id]['profesion'];
			     $profesion->save();        	
        }

        foreach ($profesiones as $profesion) {
           $profesion->profesion=$requests->profesion[$profesion->id]['profesion'];
           $profesion->save();          
        }

        foreach ($motivos as $motivo) {
           $motivo->motivo=$requests->motivo_permiso[$motivo->id]['motivo'];
           $motivo->save();          
        }

        foreach ($tiposCompra as $tipo) {
          $tipo->valor_inicial = $requests->tiposCompra[$tipo->id]['valor_inicial'];
          $tipo->valor_final = $requests->tiposCompra[$tipo->id]['valor_final'];
          $tipo->save();
        }

        
        return redirect()->route('ajustes')
            ->withSuccess('Ajustes guardados con exito'); 
    } 
    public function delete_cargo($id)
    {
       	$cargo=Cargo::find($id);
       	
       	if ($id==21) {
       		return redirect()->route('ajustes')
            ->withErrors('No se puede eliminar el cargo: "Sin cargo" ');
       	}
       	
       	if ($cargo->user) {
       		
       		foreach ($cargo->user as $user) {
       			$user->cargo_id=21;
       			$user->save();       				
       		}
       	}
   		$cargo->delete();

		return redirect()->route('ajustes')
            ->withSuccess('Cargo eliminado con exito'); 
    }  
    public function delete_profesion($id)
    {
       	$profesion=Profesion::find($id);
       	
       	if ($id==21) {
       		return redirect()->route('ajustes')
            ->withErrors('No se puede eliminar la Profesion: "Sin profesion" ');
       	}
       	
       	if ($profesion->user) {
       		
       		foreach ($profesion->user as $user) {
       			$user->profesion_id=21;
       			$user->save();       				
       		}
       	}
   		  $profesion->delete();

		    return redirect()->route('ajustes')
            ->withSuccess('Profesion eliminada con exito'); 
    } 
    public function delete_motivo($id)
    {
        $motivo=Motivo_permiso::find($id)->delete();

        return redirect()->route('ajustes')
                ->withSuccess('Motivo de Permiso eliminada con exito'); 
    }
    function create_cargo_profesion(Request $requests)
    {
      	switch ($requests->tipo) {
      		case 'cargo':
      			$cargo=new Cargo;      			
      			$cargo->cargo=$requests->cargo;
            $cargo->superior_id = $requests->superior_cargo;
      			$cargo->save();
            $mensaje="El Cargo $requests->cargo ";
      			break;
      		case 'profesion':
      			$profesion=new Profesion;
      			$profesion->profesion=$requests->profesion;
      			$profesion->save();
            $mensaje="La Profesion $requests->profesion ";
      			break;
          case 'motivo_permiso':
            $motivo=new Motivo_permiso;
            $motivo->motivo=$requests->motivo_permiso;
            $motivo->save();
            $mensaje="El motivo de permiso $requests->motivo_permiso ";
            break;       		
      	}
      	
      	return redirect()->route('ajustes')
            ->withSuccess("$mensaje a sido agregado con exito"); 
      	
    }

    function create_correlativo(Request $requests)
    {
        $correlativo=DB::table('settings')->where('key', 'correlativo')->first();
        DB::table('settings')
            ->where('key', 'correlativo')
            ->update(['value' => $requests->correlativo]);
      	return redirect()->route('ajustes')
            ->withSuccess("correlativo actualizado con exito");

    }

    public function create_tipodoc_compra(Request $request)
    {
      $doc = new TipoDocumentoCompra;
      $doc->nombre = $request->tipo_doc_compra;
      $doc->save();

      return redirect()->route('ajustes')->withSuccess("Tipo de Documento de Compra añadido con éxito");
    }

    public function delete_tipodoc($id)
    {
      $doc = TipoDocumentoCompra::find($id);

      if(count($doc->actividadDoc) > 0) {
        
        return redirect()->route('ajustes')->withErrors("El Tipo de Documento de Compra no puede ser borrado porque está siendo usado en una o más actividades");
      }
      else {
        
        $doc->delete();
        $doc->save();

        return redirect()->route('ajustes')
          ->withSuccess('Tipo de Documento de compra eliminado con exito');
      }
    }

}

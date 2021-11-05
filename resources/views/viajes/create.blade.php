@extends('layouts.app')

@section('page-title', 'Solicitud de Viaje de trabajo')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
			Solicitud de Viaje de trabajo             
        </h1>
    </div>
</div>

@include('partials.messages')

	<form class="form-horizontal vacaciones_form" action="{{url('/store/viajes')}}" method="post">
		{!! csrf_field() !!}
		<input type="hidden" name="user_id" value="{{$user->id}}">
		<input type="hidden" name="oficina_id" value="{{$user->oficina_id}}">
		@if($edit)
		<input type="hidden" name="viajes_id" value="{{$viajes->id}}">
		@endif
		
	<div class="row">
			
		<div class="form-group">
		    <label class="control-label col-sm-2">Nombre y Apellido:</label>
		    <div class="col-sm-4">
		      	<input type="text" id="a1" class="form-control" disabled name="nombre" value="{{$user->first_name}} {{$user->last_name}}" >
		    </div>
		</div>		

		<div class="form-group">
		    <label class="control-label col-sm-2">Nº contrato:</label>
		    <div class="col-sm-4">
		      	<input type="text" id="a3" class="form-control" disabled  value="{{$user->n_contrato}} " >
		    </div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Cargo:</label>
		    <div class="col-sm-4">
		      	<input type="text" id="a2" class="form-control" disabled value="{{$user->cargo->cargo}}" >
		    </div>
		</div>		

		<div class="form-group">
		    <label class="control-label col-sm-2">Oficina:</label>
		    <div class="col-sm-4">
		      	<input type="text" id="a4" class="form-control" disabled value="{{$user->oficina->oficina}}">
		    </div>
		</div>	

		<div class="form-group">
		    <label class="control-label col-sm-2" >Fecha Viaje:</label>		    
		    <div class="col-sm-3">
		      	<div class='input-group date' id='datetimepicker1'>
	                <input type='text' class="form-control" name="fechas" required 
	                value="{{$edit? $viajes->fechas : '' }}" />
	                <span class="input-group-addon">
	                    <span class="glyphicon glyphicon-calendar"></span>
	                </span>	                
	            </div>
		    </div>				
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2"> Tiempo de Viaje </label>
		    
		    <div class="col-sm-2">
		      	<input name="num_dh" step="0.01" type="number" min="0" class="form-control" max="100" required placeholder="Nº de dias" value="{{$edit? $viajes->num_dh : '' }}">
		    </div>

		    <div class="col-sm-2">
		      	<label class="radio-inline">
		      		<input type="radio" name="dh" value="Horas" {{$edit && $viajes->dh=="Horas"?'checked':''}}>Horas
		      	</label>
				<label class="radio-inline">
					<input type="radio" name="dh" value="Dias" checked {{ $edit && $viajes->dh=="Dias"?'checked':''}} >Días
				</label>
		    </div>
		    
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2" >Destino:</label>		    
		    <div class="col-sm-4">
				<input type='text' class="form-control" name="destino" required 
				value="{{$edit? $viajes->destino : '' }}" />               
		    </div>				
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2" >Comentarios:</label>		    
		    <div class="col-sm-4">
				<input type='text' class="form-control" name="comentarios" required 
				value="{{$edit? $viajes->comentarios : '' }}" />               
		    </div>				
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Fecha de solicitud:</label>
		    <div class="col-sm-4">
		      	<input type="text" id="a4" class="form-control" disabled value="{{date('Y-m-d')}}">
		    </div>
		</div>

		<div class="col-md-12 text-right">

			@if(Entrust::can(['crear-vacaciones-todos','crear-vacaciones-oficina','crear-vacaciones-solo','editar-viajes']) && $aprobacion_directora == 0)
				<button type="submit" class="btn btn_color" >
		           {{$edit?'Guardar Cambios':' Crear Solicitud de viajes'}}
		        </button>
			@endif

	        {{--@if(	
	        		(Entrust::hasRole('Administradora') && $edit) || 
	        		(Entrust::hasRole(['Coordinadora','Directora','Contralora']) && auth()->user()->id==$user->id)
	        		 && $aprobacion_directora==0 || 
	        		!$edit
	        	)		        
		        
		        <button type="submit" class="btn btn_color" >
		           {{$edit?'Guardar Cambios':' Crear Solicitud de viajes'}}
		        </button>		        
	        
	        @endif--}}
	        {{--@if(Entrust::can('aprobar-viajes') && $edit && $aprobacion_directora==0)
		        <a href='{{url("/aprobacion/viajes/$viajes->id?aprobacion=1")}}' class="btn btn_color aprobar">
					<i class="fa fa-check-square"></i> Aprobar viajes
				</a> 
				<a href='{{url("/aprobacion/viajes/$viajes->id?aprobacion=2")}}' class="btn btn_color btn_rojo aprobar">
					 Rechazar
				</a> 
	        @endif--}}
    	</div>
	</form>


@stop

@section('scripts')
{!! HTML::script('assets/js/moment.min.js') !!}
{!! HTML::script('assets/js/moment_es.js') !!}

{!! HTML::script('assets/js/bootstrap-datepicker.min.js') !!}
{!! HTML::script('assets/js/bootstrap-datepicker.es.min.js') !!}


<script type="text/javascript">
	
	
    
    $('#datetimepicker1')
	.datepicker({
	    multidate:Math.round(100),
	   language:'es',	
	   format: 'dd-mm-yyyy',
	   	    
	});
	

	$(document).on('submit', '.vacaciones_form', function(event) {	
			event.preventDefault();	
			//calculo_totales_inputs();			
			$(this)
			.find(':input[type=submit]')
		    .attr('disabled', true)
		    .html('<i class="glyphicon glyphicon-refresh"></i> Guardando datos')
		    .css('color', '#000')
		    ;
		 	this.submit();		    
		});

	$(document).on('click', 'a.aprobar', function(event) {	
			//event.preventDefault();	
			//calculo_totales_inputs();			
			$(this)			
		    .attr('disabled', true)
		    .html('<i class="glyphicon glyphicon-refresh"></i> Procesando')
		    .css('color', '#000')
		    ;		    	    
		});

	/*$(document).on('change', 'input[name="dh"]', function(event) {	
		
		
		max_dias=({{round($user->acumulado_vacaciones)}})
		max_horas=({{round($user->acumulado_vacaciones*24)}})
		dh=$(this).val();	
		
		if (dh=="Horas") {
			$('input[name="num_dh"]').attr('max', max_horas);			
		}else{
			$('input[name="num_dh"]').attr('max', max_dias);		
		}


	});*/	
	
</script>
@stop
@section('styles')
    {!! HTML::style('assets/css/bootstrap-datepicker.min.css') !!}
    {!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop
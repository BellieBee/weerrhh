@extends('layouts.app')

@section('page-title', 'Solicitud de Vacaciones')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Solicitud de Vacaciones            
        </h1>
    </div>
</div>

@include('partials.messages')

	<form class="form-horizontal vacaciones_form" action="{{url('/store/vacaciones')}}" method="post">
		{!! csrf_field() !!}
		<input type="hidden" name="user_id" value="{{$user->id}}">
		<input type="hidden" name="oficina_id" value="{{$user->oficina_id}}">
		@if($edit)
		<input type="hidden" name="vacaciones_id" value="{{$vacaciones->id}}">
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
		    <label class="control-label col-sm-2" >Fecha Vacaciones:</label>		    
		    <div class="col-sm-3">
		      	<div class='input-group date' id='datetimepicker1'>
	                <input type='text' class="form-control" name="fechas" required 
	                value="{{$edit? $vacaciones->fechas : '' }}" />
	                <span class="input-group-addon">
	                    <span class="glyphicon glyphicon-calendar"></span>
	                </span>	                
	            </div>
		    </div>
		    <label class="control-label col-sm-2" >Días permitidos: {{$user->acumulado_vacaciones}} dias</label>				
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2"> Tiempo de Vacaciones </label>
		    
		    <div class="col-sm-2">
		      	<input name="num_dh" step="0.01" type="number" min="0" class="form-control" max="{{$user->acumulado_vacaciones}}" required placeholder="Nº de dias" value="{{$edit? $vacaciones->num_dh : '' }}">
		    </div>

		    <div class="col-sm-2">
		      	<!--<label class="radio-inline">
		      		<input type="radio" name="dh" value="Horas" {{$edit && $vacaciones->dh=="Horas"?'checked':''}}>Horas
		      	</label>-->
				<label class="radio-inline">
					<input type="radio" name="dh" value="Dias" checked {{ $edit && $vacaciones->dh=="Dias"?'checked':''}} >Días
				</label>
		    </div>
		    
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Fecha de solicitud:</label>
		    <div class="col-sm-4">
		      	<input type="text" id="a4" class="form-control" disabled value="{{$edit ? $vacaciones->created_at->format('d-m-Y') : date('Y-m-d')}}">
		    </div>
		</div>

		<input type="hidden" name="acumulado" value="{{$user->acumulado_vacaciones}}" >

		<div class="form-group">
			<label class="col-sm-2 control-label">Personas que pueden aprobar:</label>
			<div class="col-sm-4">
				<table class="table table-bordered" style="margin-bottom: 0px;">
					@foreach ($rolesAprobAll as $rol)
						@if ($rol->id != 8)
							@if ($rol->id == 4 || $rol->id == 5 || $rol->id == 9 || $rol->id == 1)
								<tr>
									<th>{{$rol->display_name}}:</th>
									<td>
										<ul>
											@foreach ($rol->users as $u)
												<li>{{$u->first_name}} {{$u->last_name}}</li>
											@endforeach
										</ul>
									</td>
								</tr>
							@elseif ($rol->id == 10)
								<tr>
									<th>{{$rol->display_name}}:</th>
									<td>
										<ul>
											@foreach ($rol->users->where('status', 1) as $u)
												@if ($u->oficina->pais->id == $user->oficina->pais->id)	
													<li>{{$u->first_name}} {{$u->last_name}}</li>
												@endif
											@endforeach
										</ul>
									</td>
								</tr> {{--Ponemos esto porque se supone que Representante no toca Director RRHH ni nada mas--}}
							@elseif (count($rol->users->where('oficina_id', $user->oficina_id)->where('status', 1)) > 0)
								<tr>
									<th>{{$rol->display_name}}:</th>
									<td>
										<ul>
											@foreach ($rol->users->where('oficina_id', $user->oficina_id)->where('status', 1) as $u)
												<li>{{$u->first_name}} {{$u->last_name}}</li>
											@endforeach
										</ul>
									</td>
								</tr>
							@endif	 
						@endif
					@endforeach
					@foreach ($rolesAprobInf as $rol)
						@if ($rol->id != 8)
							@if (count($rol->users->where('status', 1)->where('cargo_id', $user->cargo->superior_id)) > 0 && ($rol->id == 2 || $rol->id == 7 || $rol->id == 3))
								<tr>
									<th>{{$rol->display_name}}:</th>
									<td>
										<ul>
											@foreach ($rol->users->where('status', 1)->where('cargo_id', $user->cargo->superior_id) as $u)
												<li>{{$u->first_name}} {{$u->last_name}}</li>
											@endforeach
										</ul>
									</td>
								</tr>
							@endif 
						@endif
					@endforeach
				</table>
			</div>
		</div>
		
		<div class="col-md-12 text-right">

			@if(Entrust::can(['crear-vacaciones-todos','crear-vacaciones-oficina','crear-vacaciones-solo','editar-vacaciones']) && $aprobacion_directora == 0)

				<button type="submit" class="btn btn_color" >
		           {{$edit?'Guardar Cambios':' Crear Solicitud de Vacaciones'}}
		        </button>
			@endif


	        {{--@if(	
	        		(Entrust::hasRole('Administradora') && $edit) || 
	        		(Entrust::hasRole(['Coordinadora','Directora','Contralora']) && auth()->user()->id==$user->id)
	        		 && $aprobacion_directora==0 || 
	        		!$edit
	        	)		        
		        
		        <button type="submit" class="btn btn_color" >
		           {{$edit?'Guardar Cambios':' Crear Solicitud de Vacaciones'}}
		        </button>		        
	        
	        @endif--}}


	        @if($edit && $aprobacion_directora==0 && (Entrust::can('aprobar-vacaciones') || (Entrust::can('aprobar-vacaciones-inferior') && auth()->user()->id != $vacaciones->user_id)))
	        	<a href='{{url("/aprobacion/vacaciones/$vacaciones->id?aprobacion=1")}}' class="btn btn_color aprobar">
					<i class="fa fa-check-square"></i> Aprobar Vacaciones
				</a> 
				<a href='{{url("/aprobacion/vacaciones/$vacaciones->id?aprobacion=2")}}' class="btn btn_color btn_rojo aprobar">
					Rechazar
				</a> 
	        @endif

	        @if($edit && $aprobacion_directora == 1 && (Entrust::can('anular-vacaciones') || (Entrust::can('anular-vacaciones-inferior') && auth()->user()->id != $vacaciones->user_id)))
	        	<a href='{{url("/aprobacion/vacaciones/$vacaciones->id?aprobacion=3")}}' class="btn btn_color btn_rojo aprobar">
	        		Anular
	        	</a>
	        @endif
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
	    multidate:Math.round({{$user->acumulado_vacaciones}}),
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
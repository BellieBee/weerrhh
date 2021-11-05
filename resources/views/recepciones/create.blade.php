@extends('layouts.app')

@section('page-title', 'Rcecpciones')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            {{$edit?'Ver':'Crear'}} Recepcion            
        </h1>
    </div>
</div>

@include('partials.messages')

	<form class="form-horizontal form_recepciones" action="{{url('/recepciones/store')}}" method="post">
		{!! csrf_field() !!}
		<input type="hidden" name="user_id" value="{{$user->id}}">
		<input type="hidden" name="oficina_id" value="{{$user->oficina_id}}">
		@if($edit)
		<input type="hidden" name="recepcion_id" value="{{$recepcion->id}}">
		@endif
		
	<div class="row">
			
		<div class="form-group">
		    <label class="control-label col-sm-2">Nombre y Apellido:</label>
		    <div class="col-sm-4">
		      	<input type="text" class="form-control" disabled value="{{$user->first_name}} {{$user->last_name}}" >
		    </div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Oficina:</label>
		    <div class="col-sm-4">
		      	<input type="text" class="form-control" disabled value="{{$user->oficina->oficina}}">
		    </div>
		</div>
		<div class="form-group">
		    <label class="control-label col-sm-2">Correo del empleado:</label>
		    <div class="col-sm-4">
		      	<input type="text" class="form-control" disabled value="{{$user->email}}">
		    </div>
		</div>

		<div class="form-group">
				    <label class="control-label col-sm-2">Documentos:</label>
				    
				    <div class="col-sm-8">
				      	<table class=" table table-bordered">
				      		<thead>
				      			<tr>
				      				<th>Titulo del documento</th>
				      				<th>Tipo de documento</th>
				      				<th >Prioridad</th>
				      				<th style="width: 50%;">Descripcion (breve)</th>
				      				<th><button id="documentos" class="btn btn_color agregar_documento"><b>+</b></button></th>
				      			</tr>
				      		</thead>
				      		<tbody class="clone_documentos">
				      			<tr style="display: none;">
				      				
				      				<td>
				      					<input name="titulo[]" class="form-control " disabled  type="text">
				      				</td>
				      				<td>
						      			<input name="tipo[]" placeholder="carta,factura,documento" class="form-control "  type="text">
						      		</td>
				      				<td>
				      					<select name="prioridad[]" class="form-control">
				      						<option>Baja</option>
				      						<option>Media</option>
				      						<option>Alta</option>
				      					</select>
				      				</td>

				      				<td>
				      					<input name="descripcion[]" class="form-control" type="text">
				      				</td>
				      				
				      				<td> 
				      					<button class="btn btn_color btn_rojo remover_documento">
				      						<i class="glyphicon glyphicon-trash"></i>
				      					</button> 
				      				</td>
				      				
				      			</tr>
				      			@if($edit)
					      			@foreach($recepcion->documentos as $documento)
					      				<tr>
						      				<td>
						      					<input name="titulo[]" class="form-control"  type='text' value="{{$documento->titulo}}">
						      				</td>
						      				<td>
						      					<input name="tipo[]" placeholder="carta,factura,documento" class="form-control "  type="text" value="{{$documento->tipo}}">
						      				</td>
						      				<td>
						      					<select name="prioridad[]" class="form-control" >						      						
						      						<option {{($documento->prioridad=="Baja")?"selected":""}}>Baja</option>
						      						<option {{($documento->prioridad=="Media")?"selected":""}}>Media</option>
						      						<option {{($documento->prioridad=="Alta")?"selected":""}}>Alta</option>
						      					</select>
						      				</td>

						      				<td>
						      					<input name="descripcion[]" class="form-control" type="text" value="{{$documento->descripcion}}">
						      				</td>
						      				
						      				<td> 
						      					<button class="btn btn_color btn_rojo remover_documento">
						      						<i class="glyphicon glyphicon-trash"></i>
						      					</button> 
						      				</td>
						      				
						      			</tr>	
					      			@endforeach
				      			@endif
				      			@for($i = 1; $i <= 1; $i++)
				      			<tr>
				      				<td>
				      					<input name="titulo[]" class="form-control "  type="text">
				      				</td>
				      				<td>
				      					<input name="tipo[]" placeholder="carta,factura,documento" class="form-control "  type="text">
				      				</td>

				      				<td>
				      					<select name="prioridad[]" class="form-control">
				      						<option>Baja</option>
				      						<option>Media</option>
				      						<option>Alta</option>
				      					</select>
				      				</td>

				      				<td>
				      					<input name="descripcion[]" class="form-control" type="text">
				      				</td>
				      				
				      				<td> 
				      					<button class="btn btn_color btn_rojo remover_documento">
				      						<i class="glyphicon glyphicon-trash"></i>
				      					</button> 
				      				</td>
				      				
				      			</tr>		      			
				      			@endfor			
				      		</tbody>	      		
				      	</table>
				    </div>		    
				</div>	

		<div class="form-group">
		    <label class="control-label col-sm-2" >Fecha en que se recibe:</label>		    
		    <div class="col-sm-3">
		      	<div class='input-group date' id='datetimepicker1'>
	                <input type='text' class="form-control" name="fecha_recibe" required 
	                value="{{$edit? $recepcion->fecha_recibe : date('Y-m-d') }}" />
	                <span class="input-group-addon">
	                    <span class="glyphicon glyphicon-calendar"></span>
	                </span>	                
	            </div>
		    </div>		    				
		</div>				

		<div class="col-md-12 text-right">

	        @if(Entrust::hasRole(['Administradora']) && (!$edit || ($edit && !$recepcion->recogido)))		        
		        
		        <button type="submit" class="btn btn_color" >
		           {{$edit?'Guardar Cambios':' Guardar recepcion y Enviar correo'}}
		        </button>		        
	        
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
	   
	   language:'es',	
	   format: 'yyyy-mm-dd',
	   	    
	});
	///////////////////////////////////////////////////////////////////////////////////////
	@if(($edit && $recepcion->recogido) || Entrust::hasRole(['Directora','Contralora','Coordinadora']))
		$('.form_recepciones input, .form_recepciones select,.remover_documento,.agregar_documento').attr('disabled', true);
	@endif
	///////////////////////////////////////////////////////////////////////////////////////
	$(document).on('click', '.agregar_documento', function(event) {
			event.preventDefault();

			id=$(this).attr('id');
			clone=$('.clone_'+id+' tr:first').clone(true);			
			clone.show().find('input').val('').attr('disabled', false);;
			$('.clone_'+id).append(clone);
	});
	///////////////////////////////////////////////////////////////////////////////////////
	$(document).on('click', '.remover_documento', function(event) {				
		$(this).closest('tr').remove();				
	});
	///////////////////////////////////////////////////////////////////////////////////////
	$(document).on('submit', '.form_recepciones', function(event) {	
			event.preventDefault();	
			//calculo_totales_inputs();			
			$(this)
			.find(':input[type=submit]')
		    .attr('disabled', true)
		    .html('<i class="glyphicon glyphicon-refresh"></i> Enviando Correo y guardando datos')
		    .css('color', '#000')
		    ;
		    this.submit();		    
		});	
	
</script>
@stop
@section('styles')
    {!! HTML::style('assets/css/bootstrap-datepicker.min.css') !!}
    {!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop
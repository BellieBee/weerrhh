@extends('layouts.app')

@section('page-title', 'Solicitud de Actividad de Salud')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Solicitud de Actividad de Salud            
        </h1>
    </div>
</div>

@include('partials.messages')

	<form class="form-horizontal bonos_form" action="{{route('bonosalud.store')}}" method="post" enctype="multipart/form-data">
		{!! csrf_field() !!}
		<input type="hidden" name="user_id" value="{{$user->id}}">
		<input type="hidden" name="oficina_id" value="{{$user->oficina_id}}">
		
	<div class="row">
			
		<div class="form-group">
		    <label class="control-label col-sm-2">Nombre y Apellido:</label>
		    <div class="col-sm-4">
		      	<input type="text" id="a1" class="form-control" disabled name="nombre" value="{{$user->first_name}} {{$user->last_name}}" >
		    </div>
		</div>		

		<div class="form-group">
		    <label class="control-label col-sm-2">Nº contrato:</label>
		    <div class="col-sm-2">
		      	<input type="text" id="a3" class="form-control" disabled  value="{{$user->n_contrato}} " >
		    </div>
		    <label class="control-label col-sm-2">Cargo:</label>
		    <div class="col-sm-2">
		      	<input type="text" id="a2" class="form-control" disabled value="{{$user->cargo->cargo}}" >
		    </div>
		</div>		

		<div class="form-group">
		    <label class="control-label col-sm-2">Oficina:</label>
		    <div class="col-sm-2">
		      	<input type="text" id="a4" class="form-control" disabled value="{{$user->oficina->oficina}}">
		    </div>
		    <label class="control-label col-sm-2">Fecha de solicitud:</label>
		    <div class="col-sm-2">
		      	<input type="text" name="fecha_solicitud" class="form-control" disabled value="{{date('Y-m-d')}}">
		    </div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Saldo inicial SK:</label>
		    <div class="col-sm-2">
		      	<input type="number" name="saldo_inicial" class="form-control saldo_inicial" disabled value="{{$bono_sk}}" id="saldo_inicial" step="0.0000001">
		    </div>
		    <label class="control-label col-sm-2"> Costo Moneda Local: </label>   
		    <div class="col-sm-2">
		      	<input name="costo_moneda_local" type="number" min="0" step="0.0000001" class="form-control costo_moneda_local" value="0.00" id="costo_moneda_local">
		    </div>
		</div>	

		<div class="form-group">
		    <label class="control-label col-sm-2" >Tipo Cambio SK:</label>		    
		    <div class="col-sm-2">
		      	<input type="number" step="0.0000001" name="tipo_cambio_sk" class="form-control tipo_cambio_sk" value="{{$user->oficina->pais->tasa_conv_corona}}" id="tipo_cambio_sk">
		    </div>
		    <label class="control-label col-sm-2" >Tipo Cambio USD:</label>		    
		    <div class="col-sm-2">
		      	<input type="number" step="0.0000001" name="tipo_cambio_usd" class="form-control tipo_cambio_usd" value="{{$user->oficina->pais->tasa_conv_usd}}" id="tipo_cambio_usd">
		    </div>			
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2" >Costo (sk):</label>
		    <div class="col-sm-2">
		      	<input name="costo_sk" type="number" step="0.0000001" min="0" class="form-control" disabled id="costo_sk">
		    </div>
		    <label class="control-label col-sm-2" >Saldo Final (sk):</label>
		    <div class="col-sm-2">
		      	<input name="saldo_final" type="number" min="0" step="0.0000001" class="form-control" disabled id="saldo_final">
		    </div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Destino:</label>
		    <div class="col-sm-4">
		      	<input type="text" name="destino" class="form-control">
		    </div>
		</div>		

		<div class="form-group">
			<label class="control-label col-sm-2">Documento:</label>
				    
			<div class="col-sm-6 ">
				    	
				<table class=" table table-bordered">

				    <thead>
				      	<tr>
				      		<th>N°</th>				      				
				      		<th>Nombre del documento</th>
				      		<th>Documento ( cada documento no debe pesar mas de 10mb)</th>
				      		<th><button id="documentos" class="btn btn_color agregar_documento"><b>+</b></button></th>
				      	</tr>
				    </thead>
				    <tbody class="clone_documentos">
				      	<tr style="display: none;">		      				
				      		<td class="num_docs" align="center"></td>
				      		<td>
				      			<input name="nombre_documento[]"  class="form-control"  type="text" >
				      		</td>

				      		<td>
				      			<input name="file_documento[]" class="form-control" type="file" >
				      		</td>			      				
				      				
				      		<td> 
				      			<button class="btn btn_color btn_rojo remover_doc">
				      				<i class="glyphicon glyphicon-trash"></i>
				      			</button> 
				      		</td>
				      				
				      	</tr>
				      			
				      	@for($i = 1; $i <= 1; $i++)
				      		<tr>
				      			<td class="num_docs" align="center">{{$i}}</td>

				      			<td>
				      				<input name="nombre_documento[]" class="form-control monto"  type="text">
				      			</td>

				      			<td>
				      				<input name="file_documento[]" class="form-control" type="file" 
				      					>
				      			</td>
	
				      			<td> 
				      				<button class="btn btn_color btn_rojo remover_doc">
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
							@if (count($rol->users->where('status', 1)->where('cargo_id', $user->cargo->superior_id)) > 0 /*&& ($rol->id == 2 || $rol->id == 7 || $rol->id == 3)*/)
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

			@if(Entrust::can(['crear-bonossalud-todos','crear-bonossalud-oficina','crear-bonossalud-solo']))
				<button type="submit" class="btn btn_color submit" >
		           Crear Solicitud de Bono Salud
		        </button>
			@endif

	        {{--@if(Entrust::can('aprobar-bonossalud') || (Entrust::can('aprobar-bonossalud-inferior') && auth()->user->id != $bono->user_id) && $edit && $bono->status == 0)
	        <a href='{{url("bonosalud/$bono->id/aprobacion?status=1")}}' class="btn btn_color aprobar">
				<i class="fa fa-check-square"></i> Aprobar Bono Salud
			</a> 
			<a href='{{url("bonosalud/$bono->id/aprobacion?aprobacion=2")}}' class="btn btn_color btn_rojo aprobar">
				 Rechazar
			</a> 
	        @endif --}}
    	</div>
	</form>

	<div class="modal fade" id="modal_size_file" tabindex="-1" role="dialog" aria-hidden="true">
    	<div class="modal-dialog modal-sm" role="document">
    		<div class="modal-content">
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>        
    				<h4 class="modal-title">Archivo muy grande</h4>                            
    			</div>
    			<div class="modal-body text-center">
    				<h5 class="red-text center ">
    					<i class="glyphicon glyphicon-floppy-remove" style="font-size: 75px; color:#f44336;"></i><br><br>
    					<label class="informacion"></label>
    				</h5>
    			</div>
    			
    		</div>
    	</div>
    </div>


@stop

@section('scripts')
{!! HTML::script('assets/js/moment.min.js') !!}
{!! HTML::script('assets/js/moment_es.js') !!}

{!! HTML::script('assets/js/bootstrap-datepicker.min.js') !!}
{!! HTML::script('assets/js/bootstrap-datepicker.es.min.js') !!}


<script type="text/javascript">	

	$(document).on('submit', '.bonos_form', function(event) {	
		event.preventDefault();

		$("#saldo_inicial").attr('disabled', false);
		$("#costo_sk").attr('disabled', false);
		$("#saldo_final").attr('disabled', false);	
		
		$(this)
			.find(':input[type=submit]')
		    .attr('disabled', true)
		    .html('<i class="glyphicon glyphicon-refresh"></i> Guardando datos')
		    .css('color', '#000');
		
		this.submit();
	});

	{{-- $(document).on('click', 'a.aprobar', function(event) {	
			//event.preventDefault();	
			//calculo_totales_inputs();			
			$(this)			
		    .attr('disabled', true)
		    .html('<i class="glyphicon glyphicon-refresh"></i> Procesando')
		    .css('color', '#000')
		    ;		    	    
		}); --}}

	$(document).on('click', '.agregar_documento', function(event) {
		event.preventDefault();

		id = $(this).attr('id');
		clone = $('.clone_'+id+' tr:first').clone(true);			
		clone.show().find('input').val('');
		$('.clone_'+id).append(clone);

		count_docs();			
	});

	$(document).on('click', '.remover_doc', function(event) {			
		$(this).closest('tr').remove();				
	});

	function count_docs(arg) {
		num_docs = 0;

		$('.num_docs').each(function (index, el) {

			$(this).text(num_docs);
				num_docs++;
		});
	}

	$(document).on('change', '#costo_moneda_local, #tipo_cambio_sk, #tipo_cambio_usd', function(event) {
		event.preventDefault();

		saldo_inicial = $("#saldo_inicial").val();
		costo_moneda_local = $("#costo_moneda_local").val();
		tipo_cambio_sk = $("#tipo_cambio_sk").val();
		tipo_cambio_usd = $("#tipo_cambio_usd").val();

		costo_sk = (costo_moneda_local / tipo_cambio_usd) * tipo_cambio_sk;
		saldo_final = saldo_inicial - costo_sk;

		$("#costo_sk").val(costo_sk.toFixed(2));
		$("#saldo_final").val(saldo_final.toFixed(2));

		if(saldo_final < 0) {
			$(".submit").attr('disabled', true);
		}
	});

	
</script>
@stop
@section('styles')
    {!! HTML::style('assets/css/bootstrap-datepicker.min.css') !!}
    {!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop
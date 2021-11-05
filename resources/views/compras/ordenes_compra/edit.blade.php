@extends('layouts.app')

@section('page-title', 'Orden de Compra')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Actualizar Orden de Compra            
        </h1>
    </div>
    <div class="col-lg-12">
    	<h4>Solicitud {{$ordencompra->actividad_id != null ? "Actividad ".$ordencompra->actividad->correlativo : "Decisión ".$ordencompra->decision->correlativo}}</h4><br>
    </div>
</div>

@include('partials.messages')

<form class="form-horizontal orden_form" action="{{route('ordencompra.update', $ordencompra->id)}}" method="post" enctype="multipart/form-data">
	{!! csrf_field() !!}
		
	<div class="row">

		<div class="form-group">
		    <label class="control-label col-sm-2">Nº Solicitud:</label>
		    <div class="col-sm-2">
		      	<input type="text" id="a1" class="form-control" disabled  value="{{$ordencompra->correlativo}} " >
		    </div>
		    <label class="control-label col-sm-2">Fecha:</label>
		    <div class="col-sm-2">
		      	<input type="text" id="a2" class="form-control" disabled  value="{{date('d-m-Y', strtotime($ordencompra->fecha))}} " >
		    </div>
		</div>	

		<div class="form-group">
			<label class="control-label col-sm-2">Tramitante:</label>
		    <div class="col-sm-4">
		      	<input type="text" id="a4" class="form-control" disabled name="nombre" value="{{$ordencompra->solicitante->first_name}} {{$ordencompra->solicitante->last_name}}" >
		    </div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Oficina:</label>
		    <div class="col-sm-4">
		      	<input type="text" id="a5" class="form-control" disabled  value="{{$ordencompra->oficina->oficina}}">
		    </div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Proveedor:</label>
		    <div class="col-sm-4">
		    	@if($ordencompra->proveedor_id != 0)
					<input type="text" class="form-control" name="proveedor" value="{{$ordencompra->proveedor->razon_social}}" disabled>
				@else
					<input type="text" class="form-control" name="proveedor" value="{{$ordencompra->proveedorUser->first_name}} {{$ordencompra->proveedorUser->last_name}}" disabled>
				@endif
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Descripción:</label>
		    <div class="col-sm-6">
				<textarea class="form-control" required name="descripcion">{{$ordencompra->descripcion}}</textarea>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-1">Detalle de compra:</label>
			<div class="col-sm-10">
				<table class=" table table-bordered">

				    <thead>
				      	<tr>
				      		<th>Proyecto</th>				      				
				      		<th>Cuenta</th>
				      		<th>Factura</th>
							<th>Centro Costo</th>
							<th>Línea Presupuestaria</th>
				      		<th>Archivo</th>
				      		<th>Monto</th>
				      	</tr>
				    </thead>
				    <tbody>
				    	@if($ordencompra->actividad_id != null)
				    		@php $detalles = $ordencompra->actividad->detalle @endphp
				    	@else
				    		@php $detalles = $ordencompra->decision->actividad->detalle @endphp
				    	@endif
				    	@foreach($detalles as $key => $detalle)
					      	<tr>		      		
					      		<td>
						      		<input type="text" class="form-control" name="proyecto[]" value="[{{$detalle->proyecto->nombre}}] {{$detalle->proyecto->descripcion}}" disabled>
					      		</td>	
								  				      		
					      		<td>
					      			<input type="text" class="form-control" name="cuenta[]" value="[{{$detalle->cuenta->nombre}}] {{$detalle->cuenta->descripcion}}" disabled>
					      		</td>

					      		<td>
				      				<input class="form-control" type="text" name="factura[]" value="{{$detalle->factura}}" placeholder="nro de factura" disabled>
				      			</td>
								
								<td>
									<input class="form-control" type="text" name="centroCosto[]" value="[{{$detalle->centroCosto->codigo}}] {{$detalle->centroCosto->descripcion}}" disabled>
								</td>

								<td>
									<input class="form-control" type="text" name="lineaPresup[]" value="[{{$detalle->lineaPresupuestaria->codigo}}] {{$detalle->lineaPresupuestaria->descripcion}}" disabled>
								</td>

					      		<td>
					      			<a href='{{route('actividad.downloadDocument',$detalle->file)}}'	 
				      					class="btn btn_color btn_download"
				      					style="background: #5cb85c;">
				      					<i class="glyphicon glyphicon-download-alt"></i>
				      				</a>
					      		</td>

					      		<td>
					      			<input type="number" step="0.0001" class="form-control" name="monto[]" value="{{$detalle->monto}}" disabled>
					      		</td>		
					      	</tr>
					    @endforeach
				    </tbody>	      		
				</table>
			</div>
		</div>

		<div class="form-group monto_group">
			<label class="control-label col-sm-2">Monto:</label>
			<div class="col-sm-2">
			    @if ($ordencompra->actividad_id != null) {{$ordencompra->actividad->tipo_moneda_id != 0 ? $ordencompra->actividad->tipo_moneda->moneda_simbolo : ''}} @else {{$ordencompra->decision->actividad->tipo_moneda_id != 0 ? $ordencompra->decision->actividad->tipo_moneda->moneda_simbolo : ''}} @endif <input type="number" step="0.0000001" class="form-control monto" name="monto" id="monto" value="{{$ordencompra->monto}}"> @if ($ordencompra->actividad_id != null) {{$ordencompra->actividad->tipo_moneda_id != 0 ? $ordencompra->actividad->tipo_moneda->moneda_nombre : ''}} @else {{$ordencompra->decision->actividad->tipo_moneda_id != 0 ? $ordencompra->decision->actividad->tipo_moneda->moneda_nombre : ''}} @endif
			</div>
			<label class="control-label col-sm-2">Tipo Moneda:</label>		    
		    <div class="col-sm-2">
		      	($) <input type="number" step="0.0000001" class="form-control tipo_cambio_usd" name="tipo_cambio_usd" id="tipo_cambio_usd" value="{{$ordencompra->oficina->pais->tasa_conv_usd}}"> (SK) <input type="number" step="0.0000001" class="form-control tipo_cambio_corona" name="tipo_cambio_corona" id="tipo_cambio_corona" value="{{$ordencompra->oficina->pais->tasa_conv_corona}}"> 
		    </div>		
		</div>

			<div class="form-group monto_group">
			    <label class="control-label col-sm-2">Monto ($):</label>
			    <div class="col-sm-2">
			      	<input type="number" step="0.0000001" class="form-control monto_usd" id="monto_usd" name="monto_usd" value="{{$ordencompra->monto_usd}}">
			    </div>
			    <label class="control-label col-sm-2">Monto (SK):</label>	   <div class="col-sm-2">
			      	<input type="number" step="0.0000001" class="form-control monto_sk" id="monto_sk" name="monto_sk" value="{{$ordencompra->monto_sk}}">
			    </div>			
			</div>
		
		<div class="col-md-12 text-right">

			@if(Entrust::can('editar-ordencompra'))
				<button type="submit" class="btn btn_color submit" >
		           Actualizar Orden de Compra
		        </button>
			@endif

			@if($ordencompra->aprobacion_solicitante == 0 && auth()->user()->id == $ordencompra->solicitante_id)
				<a href='{{url("ordencompra/$ordencompra->id/aprobacion?aprobacion_solicitante=1")}}' class="btn btn_color aprobar">
					<i class="fa fa-check-square"></i> Aprobación Solicitante
				</a> 
				<a href='{{url("ordencompra/$ordencompra->id/aprobacion?aprobacion_solicitante=2")}}' class="btn btn_color btn_rojo aprobar">
					Rechazar
				</a>
			@endif

			{{--@if(Entrust::can('aprobar-ordencompra-1') && $ordencompra->aprobacion_1 == 0 || (Entrust::can('aprobar-ordencompra-1-inferior') && auth()->user()->id != $ordencompra->solicitante_id) && $ordencompra->aprobacion_1 == 0)
	        	<a href='{{url("ordencompra/$ordencompra->id/aprobacion?aprobacion_1=1")}}' class="btn btn_color aprobar">
					<i class="fa fa-check-square"></i> 1ra Aprobación
				</a> 
				<a href='{{url("ordencompra/$ordencompra->id/aprobacion?aprobacion_1=2")}}' class="btn btn_color btn_rojo aprobar">
					Rechazar
				</a> 
	        @endif

	        @if(Entrust::can('aprobar-ordencompra-2') && $ordencompra->aprobacion_1 == 1 && $ordencompra->aprobacion_2 == 0 || (Entrust::can('aprobar-ordencompra-2-inferior') && auth()->user()->id != $ordencompra->solicitante_id) && $ordencompra->aprobacion_1 == 1 && $ordencompra->aprobacion_2 == 0)
	        	<a href='{{url("ordencompra/$ordencompra->id/aprobacion?aprobacion_2=1")}}' class="btn btn_color aprobar">
					<i class="fa fa-check-square"></i> 2ra Aprobación
				</a> 
				<a href='{{url("ordencompra/$ordencompra->id/aprobacion?aprobacion_2=2")}}' class="btn btn_color btn_rojo aprobar">
					Rechazar
				</a>
	        @endif--}}

	        @if($ordencompra->aprobacion_solicitante == 1 && auth()->user()->id == $ordencompra->solicitante_id)
	        	<a href='{{url("ordencompra/$ordencompra->id/aprobacion?aprobacion_solicitante=3")}}' class="btn btn_color btn_rojo aprobar">
					Anular Aprobación del Solicitante
				</a>
			@endif

			{{--@if($ordencompra->aprobacion_solicitante == 3 && $ordencompra->aprobacion_1 == 1 && Entrust::can('anular-ordencompra-1') || (Entrust::can('anular-ordencompra-1-inferior') && auth()->user()->id != $ordencompra->solicitante_id)))
					<a href='{{url("ordencompra/$ordencompra->id/aprobacion?aprobacion_1=3")}}' class="btn btn_color btn_rojo aprobar">
					Anular 1ra Aprobación
				</a>
			@endif

			@if($ordencompra->aprobacion_solicitante == 3 && $ordencompra->aprobacion_1 == 3 && $ordencompra->aprobacion_2 == 1 && Entrust::can('anular-ordencompra-2') || (Entrust::can('anular-ordencompra-2-inferior') && auth()->user()->id != $ordencompra->solicitante_id)))
					<a href='{{url("ordencompra/$ordencompra->id/aprobacion?aprobacion_2=3")}}' class="btn btn_color btn_rojo aprobar">
						Anular 2da Aprobación
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

	$(document).on('submit', '.orden_form', function(event) {	
		event.preventDefault();	
		
		$(this)
			.find(':input[type=submit]')
		    .attr('disabled', true)
		    .html('<i class="glyphicon glyphicon-refresh"></i> Guardando datos')
		    .css('color', '#000');
		
		this.submit();
	});

	$(document).on('change', '.monto, .tipo_cambio_usd, .tipo_cambio_corona', function (event) {

		event.preventDefault();

		monto = $("#monto").val();
		tipo_cambio_usd = $("#tipo_cambio_usd").val();
		tipo_cambio_corona = $("#tipo_cambio_corona").val();

		monto_usd = parseFloat(monto) / parseFloat(tipo_cambio_usd);
		monto_sk = (parseFloat(monto) / parseFloat(tipo_cambio_usd)) * parseFloat(tipo_cambio_corona);

		$("#monto_usd").val(monto_usd.toFixed(2));
		$("#monto_sk").val(monto_sk.toFixed(2));

	});
	
</script>
@stop
@section('styles')
    {!! HTML::style('assets/css/bootstrap-datepicker.min.css') !!}
    {!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop
@extends('layouts.app')

@section('page-title', 'Orden de Compra')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Crear Orden de Compra            
        </h1>
    </div>
    <div class="col-lg-12">
    	<h4>Solicitud {{$es_actividad == 1 ? "Actividad ".$actividad->correlativo : "Decisión ".$decision->correlativo}}</h4><br>
    </div>
</div>

@include('partials.messages')

<form class="form-horizontal orden_form" action="{{route('ordencompra.store')}}" method="post" enctype="multipart/form-data">
	{!! csrf_field() !!}
	<input type="hidden" name="correlativo" value="{{$correlativo}}">
	@if($es_actividad == 1)
		<input type="hidden" name="actividad_id" value="{{$actividad->id}}">
		<input type="hidden" name="cuenta_id" value="{{$actividad->cuenta_id != null ? $cuenta->id : null}}">
	@else
		<input type="hidden" name="decision_id" value="{{$decision->id}}">
	@endif
	<input type="hidden" name="oficina_id" value="{{$user->oficina_id}}">
	<input type="hidden" name="solicitante_id" value="{{$user->id}}">
	@if($proveedor)
		<input type="hidden" name="proveedor_id" value="{{$proveedor->id}}">
		<input type="hidden" name="proveedor_user_id" value="0">
	@else
		<input type="hidden" name="proveedor_id" value="0">
		<input type="hidden" name="proveedor_user_id" value="{{$provUser->id}}">
	@endif
		
	<div class="row">

		<div class="form-group">
		    <label class="control-label col-sm-2">Nº Solicitud:</label>
		    <div class="col-sm-2">
		      	<input type="text" id="a1" class="form-control" disabled  value="{{$correlativo}} " >
		    </div>
		    <label class="control-label col-sm-2">Fecha:</label>
		    <div class="col-sm-2">
		      	<input type="text" id="a2" class="form-control" disabled  value="{{date('d-m-Y')}} " >
		    </div>
		</div>	

		<div class="form-group">
			<label class="control-label col-sm-2">Tramitante:</label>
		    <div class="col-sm-4">
		      	<input type="text" id="a4" class="form-control" disabled name="nombre" value="{{auth()->user()->first_name}} {{auth()->user()->last_name}}" >
		    </div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Oficina:</label>
		    <div class="col-sm-4">
		      	<input type="text" id="a5" class="form-control" disabled  value="{{$user->oficina->oficina}}">
		    </div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Proveedor:</label>
		    <div class="col-sm-4">
		    	@if($proveedor)
					<input type="text" class="form-control" name="proveedor" value="{{$proveedor->razon_social}}" disabled>
				@else
					<input type="text" class="form-control" name="proveedor" value="{{$provUser->first_name}} {{$provUser->last_name}}" disabled>
				@endif
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Descripción:</label>
		    <div class="col-sm-6">
				<textarea class="form-control" required name="descripcion"></textarea>
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
				    	@if($es_actividad == 1)
				    		@php $detalles = $actividad->detalle @endphp
				    	@else
				    		@php $detalles = $decision->actividad->detalle @endphp
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
			    @if ($es_actividad == 1) {{$actividad->tipo_moneda_id != 0 ? $actividad->tipo_moneda->moneda_simbolo : ''}} @else {{$decision->actividad->tipo_moneda_id != 0 ? $decision->actividad->tipo_moneda->moneda_simbolo : ''}} @endif <input type="number" step="0.0000001" class="form-control monto" name="monto" id="monto" value="{{$es_actividad == 1 ? $monto : $decision->actividad->detalle->sum('monto')}}"> @if ($es_actividad == 1) {{$actividad->tipo_moneda_id != 0 ? $actividad->tipo_moneda->moneda_nombre : ''}} @else {{$decision->actividad->tipo_moneda_id != 0 ? $decision->actividad->tipo_moneda->moneda_nombre : ''}} @endif
			</div>
			<label class="control-label col-sm-2">Tipo Moneda:</label>		    
		    <div class="col-sm-2">
		      	($) <input type="number" step="0.0000001" class="form-control tipo_cambio_usd" name="tipo_cambio_usd" id="tipo_cambio_usd" value="{{$user->oficina->pais->tasa_conv_usd}}"> (SK) <input type="number" step="0.0000001" class="form-control tipo_cambio_corona" name="tipo_cambio_corona" id="tipo_cambio_corona" value="{{$user->oficina->pais->tasa_conv_corona}}"> 
		    </div>		
		</div>

			<div class="form-group monto_group">
			    <label class="control-label col-sm-2">Monto ($):</label>
			    <div class="col-sm-2">
			      	<input type="number" step="0.0000001" class="form-control monto_usd" id="monto_usd" name="monto_usd" value="{{$es_actividad == 1 ? $monto_usd : $decision->actividad->monto_usd}}">
			    </div>
			    <label class="control-label col-sm-2">Monto (SK):</label>	   <div class="col-sm-2">
			      	<input type="number" step="0.0000001" class="form-control monto_sk" id="monto_sk" name="monto_sk" value="{{$es_actividad == 1 ? $monto_sk : $decision->actividad->monto_sk}}">
			    </div>			
			</div>
		
		<div class="col-md-12 text-right">

			@if(Entrust::can(['crear-ordencompra-todos','crear-ordencompra-oficina', 'crear-ordencompra-solo']))
				<button type="submit" class="btn btn_color submit" >
		           Crear Orden de Compra
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
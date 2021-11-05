@extends('layouts.app')

@section('page-title', 'Crear Solicitud de Pago')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Crear Solicitud de Pago            
        </h1>
    </div>
    <div class="col-lg-12">
    	<h4>
    		@if($es_actividad == 1)
    			Solicitud Actividad {{$actividad->correlativo}}
    		@elseif($es_actividad == 0)
    			Solicitud Orden de Compra {{$ordencompra->correlativo}}
    		@elseif($es_actividad == 2)
    			Solicitud Contrato {{$contrato->n_contrato}}
    		@endif
    	</h4><br>
    </div>
</div>

@include('partials.messages')

<form class="form-horizontal orden_form" action="{{route('pago.store')}}" method="post" enctype="multipart/form-data">
	{!! csrf_field() !!}
	@if($es_actividad == 1)
		<input type="hidden" name="actividad_id" value="{{$actividad->id}}">
		<input type="hidden" name="proveedor_id" value="{{$actividad->proveedor_id}}">
		<input type="hidden" name="proveedor_user_id" value="{{$actividad->proveedor_user_id}}">
		<input type="hidden" name="user_id" value="{{$actividad->user_id}}">
		<input type="hidden" name="cuenta_id" value="{{$actividad->cuenta_id != null ? $actividad->cuenta_id : null}}">
	@elseif($es_actividad == 0)
		<input type="hidden" name="ordencompra_id" value="{{$ordencompra->id}}">
		<input type="hidden" name="proveedor_id" value="{{$ordencompra->proveedor_id}}">
		<input type="hidden" name="proveedor_user_id" value="{{$ordencompra->proveedor_user_id}}">
		<input type="hidden" name="user_id" value="{{$ordencompra->solicitante_id}}">
		<input type="hidden" name="cuenta_id" value="{{$ordencompra->cuenta_id != null ? $ordencompra->cuenta_id : null}}">
	@elseif($es_actividad == 2)
		<input type="hidden" name="contrato_id" value="{{$contrato->id}}">
		<input type="hidden" name="user_id" value="{{$contrato->ordencompra->solicitante_id}}">
		<input type="hidden" name="proveedor_id" value="{{$contrato->ordencompra->proveedor_id}}">
		<input type="hidden" name="proveedor_user_id" value="{{$contrato->ordencompra->proveedor_user_id}}">
		<input type="hidden" name="cuenta_id" value="{{$contrato->ordencompra->cuenta_id != null ? $contrato->ordencompra->cuenta_id : null}}">
	@endif

	<input type="hidden" name="oficina_id" value="{{$oficina->id}}">
	<input type="hidden" name="tramitante_id" value="{{auth()->user()->id}}">
		
	<div class="row">

		<div class="form-group">
		    <label class="control-label col-sm-2">Fecha:</label>
		    <div class="col-sm-2">
		      	<input type="text" class="form-control" disabled  value="{{date('d-m-Y')}} " >
		    </div>
		    <label class="control-label col-sm-2">Oficina:</label>
		    <div class="col-sm-2">
		      	<input type="text" class="form-control" disabled  value="{{$oficina->oficina}}">
		    </div>
		</div>	

		<div class="form-group">
			<label class="control-label col-sm-2">Tramitante:</label>
		    <div class="col-sm-4">
		      	<input type="text" class="form-control" disabled name="nombre" value="{{auth()->user()->first_name}} {{auth()->user()->last_name}}" >
		    </div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-2">Usuario a pagar:</label>
			<div class="col-sm-4">
				@if($es_actividad == 1)
					<input type="text" class="form-control" disabled value="{{$actividad->user->first_name}} {{$actividad->user->last_name}}">
				@elseif($es_actividad == 0)
					<input type="text" class="form-control" disabled value="{{$ordencompra->solicitante->first_name}} {{$ordencompra->solicitante->last_name}}">
				@elseif($es_actividad == 2)
					<input type="text" class="form-control" disabled value="{{$contrato->ordencompra->solicitante->first_name}} {{$contrato->ordencompra->solicitante->last_name}}">
				@endif
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Proveedor:</label>
		    <div class="col-sm-4">
		    	@if($es_actividad == 1)
		    		@if($actividad->proveedor_id != 0)
						<input type="text" class="form-control" name="proveedor" value="{{$actividad->proveedor->razon_social}}" disabled>
					@else
						<input type="text" class="form-control" name="proveedor" value="{{$actividad->proveedorUser->first_name}} {{$actividad->proveedorUser->last_name}}" disabled>
					@endif
				@elseif($es_actividad == 0)
					@if($ordencompra->proveedor_id != 0)
						<input type="text" class="form-control" name="proveedor" value="{{$ordencompra->proveedor->razon_social}}" disabled>
					@else
						<input type="text" class="form-control" name="proveedor" value="{{$ordencompra->proveedorUser->first_name}} {{$ordencompra->proveedorUser->last_name}}" disabled>
					@endif
				@elseif($es_actividad == 2)
					@if($contrato->ordencompra->proveedor_id != 0)
						<input type="text" class="form-control" name="proveedor" value="{{$contrato->ordencompra->proveedor->razon_social}}" disabled>
					@else
						<input type="text" class="form-control" name="proveedor" value="{{$contrato->ordencompra->proveedorUser->first_name}} {{$contrato->ordencompra->proveedorUser->last_name}}" disabled>
					@endif
				@endif
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-2">Tipo de Pago:</label>
			<div class="col-sm-4">
			    <select class="form-control" name="tipopago_id" required>
			      	<option disabled selected></option>
			      	@foreach($tiposPagos as $pago)
			      		<option value="{{$pago->id}}">{{$pago->nombre}}</option>
			      	@endforeach
			    </select>
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Concepto:</label>
		    <div class="col-sm-6">
				<textarea class="form-control" required name="concepto"></textarea>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-2">Moneda de Pago:</label>
			<div class="col-sm-4">
				{{--<input type="text" name="moneda_pago" class="form-control">--}}
				@if ($es_actividad == 1) 
					<input type="text" class="form-control" name="tipo_moneda" value="{{$actividad->tipo_moneda_id != 0 ? $actividad->tipo_moneda->moneda_nombre : ''}}" disabled>
				@elseif ($es_actividad == 0)
					@if ($ordencompra->actividad_id != null)
						<input type="text" class="form-control" name="tipo_moneda" value="{{$ordencompra->actividad->tipo_moneda_id != 0 ? $ordencompra->actividad->tipo_moneda->moneda_nombre : ''}}" disabled>
					@else
						<input type="text" class="form-control" name="tipo_moneda" value="{{$ordencompra->decision->actividad->tipo_moneda_id != 0 ? $ordencompra->decision->actividad->tipo_moneda->moneda_nombre : ''}}" disabled>
					@endif
				@else
					<input type="text" class="form-control" name="tipo_moneda" value="{{$contrato->ordencompra->decision->actividad->tipo_moneda_id != 0 ? $contrato->ordencompra->decision->actividad->tipo_moneda->moneda_nombre : ''}}" disabled>
				@endif
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
				    		@php $detalles = $actividad->detalle; @endphp
				    	@elseif($es_actividad == 0)
				    		@if($ordencompra->actividad_id != null)
				    			@php $detalles = $ordencompra->actividad->detalle; @endphp
				    		@else
				    			@php $detalles = $ordencompra->decision->actividad->detalle; @endphp
				    		@endif
				    	@else
				    		@if($contrato->ordencompra->actividad_id != null)
				    			@php $detalles = $contrato->ordencompra->actividad->detalle; @endphp
				    		@else
				    			@php $detalles = $contrato->ordencompra->decision->actividad->detalle; @endphp
				    		@endif
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

		@if(str_contains($oficina->oficina, "UE"))
			<div class="form-group">
				<label class="control-label col-sm-2">Exonerar impuesto:</label>
				<div class="col-sm-2">
					<input type="checkbox" class="form-check-input exo_imp" id="exo_imp" name="exo_imp">
				</div>
			</div>
		@endif

		<div class="form-group monto_group">
			<label class="control-label col-sm-2">Monto neto:</label>
			<div class="col-sm-2">
				@if($es_actividad == 1)
			    	<input type="number" step="0.0000001" class="form-control monto" name="monto" id="monto" value="{{$actividad->monto}}">
			    @elseif($es_actividad == 0)
			    	<input type="number" step="0.0000001" class="form-control monto" name="monto" id="monto" value="{{$ordencompra->monto}}">
			    @elseif($es_actividad == 2)
			    	<input type="number" step="0.0000001" class="form-control monto" name="monto" id="monto" value="{{$contrato->monto_total}}">
			    @endif
			</div>
			<label class="control-label col-sm-2">Tipo Moneda:</label>		    
		    <div class="col-sm-2">
		      	($) <input type="number" step="0.0000001" class="form-control tipo_cambio_usd" name="tipo_cambio_usd" id="tipo_cambio_usd" value="{{$oficina->pais->tasa_conv_usd}}"> (SK) <input type="number" step="0.0000001" class="form-control tipo_cambio_corona" name="tipo_cambio_corona" id="tipo_cambio_corona" value="{{$oficina->pais->tasa_conv_corona}}"> 
		    </div>		
		</div>

		<div class="form-group monto_group">
			<label class="control-label col-sm-2">Impuesto (%):</label>
			<div class="col-sm-2">
				<input type="number" step="0.0000001" class="form-control impuesto" id="impuesto" name="impuesto" value="{{$oficina->imp_pago_compra}}">
			</div>
			<label class="control-label col-sm-2">Descuento ISR (%):</label>
			<div class="col-sm-2">
				<input type="number" step="0.0000001" class="form-control isr" id="isr" name="isr" value="{{$oficina->isr_pago_compra}}">
			</div>
		</div>

		<div class="form-group monto_group">
			<label class="control-label col-sm-2">Monto ($):</label>
			<div class="col-sm-2">
			    <input type="number" step="0.00000000001" class="form-control monto_usd" id="monto_usd" name="monto_usd" value="{{$monto_usd}}">
			</div>
			<label class="control-label col-sm-2">Monto (SK):</label>	   
			<div class="col-sm-2">
			    <input type="number" step="0.00000000001" class="form-control monto_sk" id="monto_sk" name="monto_sk" value="{{$monto_sk}}">
			</div>			
		</div>

		<div class="form-group monto_group">
			<label class="control-label col-sm-2">Monto total:</label>
			<div class="col-sm-2">
				<input type="number" step="0.00000000001" class="form-control" id="monto_total" name="monto_total" value="{{$monto_total}}" disabled>
			</div>
		</div>
		
		<div class="col-md-12 text-right">

			@if(Entrust::can(['crear-pago-todos','crear-pago-oficina', 'crear-pago-solo']))
				<button type="submit" class="btn btn_color submit" >
		           Guardar Solicitud de Pago
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

		$("#monto_total").attr('disabled', false);
		$("#impuesto").attr('disabled', false);	
		
		$(this)
			.find(':input[type=submit]')
		    .attr('disabled', true)
		    .html('<i class="glyphicon glyphicon-refresh"></i> Guardando datos')
		    .css('color', '#000');
		
		this.submit();
	});

	$(document).on('change', '#proveedores', function (event) {

		event.preventDefault();

		proveedores = $("#proveedores").val();

		if (proveedores == 0) {

			$("#colegas_proveedores").attr('hidden', false);
		}
		else {

			$("#colegas_proveedores").attr('hidden', true);
		}
	});

	$(document).on('change', '.exo_imp, .monto, .tipo_cambio_usd, .tipo_cambio_corona, .impuesto, .isr', function (event) {

		event.preventDefault();

		monto = $("#monto").val();
		tipo_cambio_usd = $("#tipo_cambio_usd").val();
		tipo_cambio_corona = $("#tipo_cambio_corona").val();

		if ($(".exo_imp").is(':checked')) {

			$(".impuesto").attr({
				disabled: true,
				value: '0.00'
			});
		}
		else {

			$(".impuesto").attr({
				disabled: false,
				value: '{{$oficina->imp_pago_compra}}',
			});
			
		}

		impuesto = $("#impuesto").val();
		isr = $("#isr").val();
		imp_deducido = parseFloat(monto) * (parseFloat(impuesto) / 100);
		isr_deducido = parseFloat(monto) * (parseFloat(isr) / 100);
		monto_total = (parseFloat(monto) + imp_deducido) - isr_deducido;

		monto_usd = monto_total / parseFloat(tipo_cambio_usd);
		monto_sk = (monto_total / parseFloat(tipo_cambio_usd)) * parseFloat(tipo_cambio_corona);

		$("#monto_usd").val(monto_usd.toFixed(6));
		$("#monto_sk").val(monto_sk.toFixed(6));
		$("#monto_total").val(monto_total.toFixed(6));

	});
	
</script>
@stop
@section('styles')
    {!! HTML::style('assets/css/bootstrap-datepicker.min.css') !!}
    {!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop
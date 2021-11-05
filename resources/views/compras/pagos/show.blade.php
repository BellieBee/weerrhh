@extends('layouts.app')

@section('page-title', 'Ver Solicitud de Pago')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Ver Solicitud de Pago            
        </h1>
    </div>
    <div class="col-lg-12">
    	<h4>
    		@if($pago->actividad_id != null)
    			Solicitud de Actividad {{$pago->actividad->correlativo}}
    		@elseif($pago->orden_compra_id != null)
    			Solicitud de Orden de Compra {{$pago->ordencompra->correlativo}}
    		@elseif($pago->contrato_id != null)
    			Solicitud de Contrato {{$pago->contrato->n_contrato}}
    		@endif
    	</h4>
    </div>
</div>

@include('partials.messages')

<form class="form-horizontal orden_form" action="{{route('pago.update', $pago->id)}}" method="post" enctype="multipart/form-data">
	{!! csrf_field() !!}

	@if($pago->contrato_id == null)
		<input type="hidden" name="proveedor_id" value="{{$pago->proveedor_id}}">
		<input type="hidden" name="proveedor_user_id" value="{{$pago->proveedor_user_id}}">
		<input type="hidden" name="cuenta_id" value="{{$pago->cuenta_id}}">
	@endif
		
	<div class="row">

		<div class="form-group">
		    <label class="control-label col-sm-2">Fecha:</label>
		    <div class="col-sm-2">
		      	<input type="text" class="form-control" disabled  value="{{date('d-m-Y', strtotime($pago->fecha))}} " >
		    </div>
		    <label class="control-label col-sm-2">Oficina:</label>
		    <div class="col-sm-2">
		      	<input type="text" class="form-control" disabled  value="{{$pago->oficina->oficina}}">
		    </div>
		</div>	

		<div class="form-group">
			<label class="control-label col-sm-2">Tramitante:</label>
		    <div class="col-sm-4">
		      	<input type="text" class="form-control" disabled name="nombre" value="{{$pago->tramitante->first_name}} {{$pago->tramitante->last_name}}" >
		    </div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-2">Usuario a pagar:</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" disabled value="{{$pago->user->first_name}} {{$pago->user->last_name}}">
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Proveedor:</label>
		    <div class="col-sm-4">
		    	@if($pago->contrato_id == null)
		    		@if($pago->proveedor_id != 0)
						<input type="text" class="form-control" name="proveedor" value="{{$pago->proveedor->razon_social}}" disabled>
					@else
						<input type="text" class="form-control" name="proveedor" value="{{$pago->proveedorUser->first_name}} {{$pago->proveedorUser->last_name}}" disabled>
					@endif
				@else
					<select class="form-control proveedores" id="proveedores" name="proveedor_id">
						@if($pago->proveedor_id != 0)
							<option value="{{$pago->proveedor_id}}">{{$pago->proveedor->razon_social}}</option>
							<option disabled>Seleccione una opción</option>
							<option value="0">Seleccionar un Colega</option>
						@else
							<option value="0">Seleccionar un Colega</option>
							<option disabled>Seleccione una opción</option>
						@endif
			      		@foreach($proveedores as $proveedor)
			      			@if($proveedor->id != $pago->proveedor_id)
			      				<option value="{{$proveedor->id}}">{{$proveedor->razon_social}}</option>
			      			@endif
			      		@endforeach			      		
					</select>
				@endif
			</div>
		</div>

		@if($pago->contrato_id != null)
			<div class="form-group" id="colegas_proveedores" {{$pago->proveedor_id == 0 ? '' : 'hidden'}}>
				<label class="control-label col-sm-2">Colega Proveedor:</label>
				<div class="col-sm-4">
				    <select class="form-control" name="proveedor_user_id">
				    	@if($pago->proveedor_user_id != null)
				    		<option value="{{$pago->proveedor_user_id}}">{{$pago->proveedorUser->first_name}} {{$pago->proveedorUser->last_name}}</option>
				    		<option disabled>Seleccione una opción</option>
				    	@else
				      		<option disabled>Seleccione una opción</option>
				      	@endif
				      	@foreach($users as $user)
				      		@if($pago->proveedor_user_id != $user->id)
				      			<option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
				      		@endif
				      	@endforeach
				    </select>
				</div>
			</div>
		@endif

		<div class="form-group">
			<label class="control-label col-sm-2">Tipo de Pago:</label>
			<div class="col-sm-4">
			    <select class="form-control" name="tipopago_id" required>
			    	<option value="{{$pago->tipopago_id}}">{{$pago->tipopago->nombre}}</option>
			      	@foreach($tiposPagos as $tipo)
			      		@if($tipo->id != $pago->tipopago_id)
			      			<option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
			      		@endif
			      	@endforeach
			    </select>
			</div>
		</div> 

		<div class="form-group">
		    <label class="control-label col-sm-2">Concepto:</label>
		    <div class="col-sm-6">
				<textarea class="form-control" required name="concepto">{{$pago->concepto}}</textarea>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-2">Moneda de Pago:</label>
			<div class="col-sm-4">
				{{--<input type="text" name="moneda_pago" class="form-control" value="{{$pago->moneda_pago}}">--}}
				@if ($pago->actividad_id != null) 
					<input type="text" class="form-control" name="tipo_moneda" value="{{$pago->actividad->tipo_moneda_id != 0 ? $pago->actividad->tipo_moneda->moneda_nombre : ''}}" disabled>
				@elseif ($pago->orden_compra_id != null)
					@if ($pago->ordencompra->actividad_id != null)
						<input type="text" class="form-control" name="tipo_moneda" value="{{$pago->ordencompra->actividad->tipo_moneda_id != 0 ? $pago->ordencompra->actividad->tipo_moneda->moneda_nombre : ''}}" disabled>
					@else
						<input type="text" class="form-control" name="tipo_moneda" value="{{$pago->ordencompra->decision->actividad->tipo_moneda_id != 0 ? $pago->ordencompra->decision->actividad->tipo_moneda->moneda_nombre : ''}}" disabled>
					@endif
				@else
					<input type="text" class="form-control" name="tipo_moneda" value="{{$pago->contrato->ordencompra->decision->actividad->tipo_moneda_id != 0 ? $pago->contrato->ordencompra->decision->actividad->tipo_moneda->moneda_nombre : ''}}" disabled>
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
				    	@if($pago->actividad_id != null)
				    		@php $detalles = $pago->actividad->detalle; @endphp
				    	@elseif($pago->ordencompra != null)
				    		@if($pago->ordencompra->actividad_id != null)
				    			@php $detalles = $pago->ordencompra->actividad->detalle; @endphp
				    		@else
				    			@php $detalles = $pago->ordencompra->decision->actividad->detalle; @endphp
				    		@endif
				    	@else
				    		@if($pago->contrato->ordencompra->actividad_id != null)
				    			@php $detalles = $pago->contrato->ordencompra->actividad->detalle; @endphp
				    		@else
				    			@php $detalles = $pago->contrato->ordencompra->decision->actividad->detalle; @endphp
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

		@if(str_contains($pago->oficina->oficina, "UE"))
			<div class="form-group">
				<label class="control-label col-sm-2">Exonerar impuesto:</label>
				<div class="col-sm-2">
					<input type="checkbox" class="form-check-input exo_imp" id="exo_imp" name="exo_imp" {{$pago->exo_imp = 1 ? 'checked' : ''}}>
				</div>
			</div>
		@endif

		<div class="form-group monto_group">
			<label class="control-label col-sm-2">Monto:</label>
			<div class="col-sm-2">
			    <input type="number" step="0.0000001" class="form-control monto" name="monto" id="monto" value="{{$pago->monto}}">
			</div>
			<label class="control-label col-sm-2">Tipo Moneda:</label>		    
		    <div class="col-sm-2">
		      	($) <input type="number" step="0.0000001" class="form-control tipo_cambio_usd" name="tipo_cambio_usd" id="tipo_cambio_usd" value="{{$pago->oficina->pais->tasa_conv_usd}}"> (SK) <input type="number" step="0.0000001" class="form-control tipo_cambio_corona" name="tipo_cambio_corona" id="tipo_cambio_corona" value="{{$pago->oficina->pais->tasa_conv_corona}}"> 
		    </div>		
		</div>

		<div class="form-group monto_group">
			<label class="control-label col-sm-2">Impuesto (%):</label>
			<div class="col-sm-2">
				<input type="number" step="0.0000001" class="form-control impuesto" id="impuesto" name="impuesto" value="{{$pago->impuesto}}">
			</div>
			<label class="control-label col-sm-2">Descuento ISR (%):</label>
			<div class="col-sm-2">
				<input type="number" step="0.0000001" class="form-control isr" id="isr" name="isr" value="{{$pago->isr}}">
			</div>
		</div>

		<div class="form-group monto_group">
			<label class="control-label col-sm-2">Monto ($):</label>
			<div class="col-sm-2">
			    <input type="number" step="0.00000000001" class="form-control monto_usd" id="monto_usd" name="monto_usd" value="{{$pago->monto_usd}}">
			</div>
			<label class="control-label col-sm-2">Monto (SK):</label>	   
			<div class="col-sm-2">
			    <input type="number" step="0.00000000001" class="form-control monto_sk" id="monto_sk" name="monto_sk" value="{{$pago->monto_sk}}">
			</div>			
		</div>

		<div class="form-group monto_group">
			<label class="control-label col-sm-2">Monto total:</label>
			<div class="col-sm-2">
				<input type="number" step="0.00000000001" class="form-control" id="monto_total" name="monto_total" value="{{$pago->monto_total}}" disabled>
			</div>
		</div>

		<div class="col-md-12 text-right">
			@if(Entrust::can('aprobar-pago') && $pago->revision == 0 || (Entrust::can('aprobar-pago-inferior') && auth()->user()->id != $pago->user_id) && $pago->revision == 0)
	        	<a href='{{url("pago/$pago->id/aprobacion?aprobacion=1")}}' class="btn btn_color aprobar">
					<i class="fa fa-check-square"></i> Aprobar Solicitud de Pago
				</a> 
				<a href='{{url("pago/$pago->id/aprobacion?aprobacion=2")}}' class="btn btn_color btn_rojo aprobar">
					Rechazar
				</a> 
	        @endif

	        @if($pago->revision == 1 && Entrust::can('anular-pago') || (Entrust::can('anular-pago-inferior') && auth()->user()->id != $pago->user_id))
	        	<a href='{{url("pago/$pago->id/aprobacion?aprobacion=3")}}' class="btn btn_color btn_rojo aprobar">
					Anular Pago
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

	$(document).on('change', '.proveedor', function (event) {

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
				value: '{{$pago->impuesto}}',
			});
			
		}

		impuesto = $("#impuesto").val();
		isr = $("#isr").val();
		imp_deducido = parseFloat(monto) * (parseFloat(impuesto) / 100);
		isr_deducido = parseFloat(monto) * (parseFloat(isr) / 100);
		monto_total = (parseFloat(monto) + imp_deducido) - isr_deducido;

		monto_usd = monto_total / parseFloat(tipo_cambio_usd);
		monto_sk = (monto_total / parseFloat(tipo_cambio_usd)) * parseFloat(tipo_cambio_corona);

		$("#monto_usd").val(monto_usd.toFixed(2));
		$("#monto_sk").val(monto_sk.toFixed(2));
		$("#monto_total").val(monto_total.toFixed(2));

	});
	
</script>
@stop
@section('styles')
    {!! HTML::style('assets/css/bootstrap-datepicker.min.css') !!}
    {!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop
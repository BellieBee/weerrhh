@extends('layouts.app')

@section('page-title', 'Actualizar Solicitud de Pago')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Actualizar Solicitud de Pago            
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

@php 
	if($pago->actividad_id != null) { 
		$actividad = $pago->actividad;
	} 
	elseif($pago->orden_compra_id != null) { 
		$ordencompra = $pago->ordencompra;
		$actividad = $ordencompra->actividad;
		
	} 
	else { 
		$contrato = $pago->contrato;
		$ordencompra = $contrato->ordencompra;
		$decision = $ordencompra->decision;
		$actividad = $decision->actividad;
	} 
@endphp

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

		<div class="form'group">
			<label class="control'label col-sm-2">Tabla de Aprobaciones:</label>
			<div class="col-sm-8">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Registro</th>				      				
							<th>Ver</th>
							<th>Personal Autorizado</th>
							<th>Estado</th>
							<th>Aprobación</th>
						</tr>
				 	</thead>
					<tbody>
						<tr>
							<td>Actividad</td>
							<td>
								<a href='{{route('actividad.show', $actividad->id)}}' 
									class="btn btn_color aprobar"> Ver
								</a>		
							</td>
							<td>
								<b>Aprobación 1</b>
								@foreach ($rolesAprobAct1 as $rol)
									@if ($rol->id != 8)
										@if ($rol->id == 4 || $rol->id == 5 || $rol->id == 9 || $rol->id == 1)
											<ul>
												@foreach ($rol->users as $u)
													<li>{{$u->first_name}} {{$u->last_name}}</li>
												@endforeach
											</ul>
										@elseif ($rol->id == 10)
											<ul>
												@foreach ($rol->users->where('status', 1) as $u)
													@if ($u->oficina->pais->id == $pago->tramitante->oficina->pais->id)	
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endif
												@endforeach
											</ul>
										@else (count($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1)) > 0)
											<ul>
												@foreach ($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1) as $u)
													<li>{{$u->first_name}} {{$u->last_name}}</li>
												@endforeach
											</ul>
										@endif	
									@endif
								@endforeach
								@foreach ($rolesAprobAct1Inf as $rol)
									@if ($rol->id != 8)
										@if (count($rol->users->where('status', 1)->where('cargo_id', $pago->tramitante->cargo->superior_id)) > 0 /*&& ($rol->id == 2 || $rol->id == 7 || $rol->id == 3)*/)
											<ul>
												@foreach ($rol->users->where('status', 1)->where('cargo_id', $pago->tramitante->cargo->superior_id) as $u)
													<li>{{$u->first_name}} {{$u->last_name}}</li>
												@endforeach
											</ul>
										@endif 
									@endif
								@endforeach
								<b>Aprobación 2</b>
								@foreach ($rolesAprobAct2 as $rol)
									@if ($rol->id != 8)
										@if ($rol->id == 4 || $rol->id == 5 || $rol->id == 9 || $rol->id == 1)
											<ul>
												@foreach ($rol->users as $u)
													<li>{{$u->first_name}} {{$u->last_name}}</li>
												@endforeach
											</ul>
										@elseif ($rol->id == 10)
											<ul>
												@foreach ($rol->users->where('status', 1) as $u)
													@if ($u->oficina->pais->id == $pago->tramitante->oficina->pais->id)	
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endif
												@endforeach
											</ul>
										@else (count($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1)) > 0)
											<ul>
												@foreach ($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1) as $u)
													<li>{{$u->first_name}} {{$u->last_name}}</li>
												@endforeach
											</ul>
										@endif	
									@endif
								@endforeach
								@foreach ($rolesAprobAct2Inf as $rol)
									@if ($rol->id != 8)
										@if (count($rol->users->where('status', 1)->where('cargo_id', $pago->tramitante->cargo->superior_id)) > 0 /*&& ($rol->id == 2 || $rol->id == 7 || $rol->id == 3)*/)
											<ul>
												@foreach ($rol->users->where('status', 1)->where('cargo_id', $pago->tramitante->cargo->superior_id) as $u)
													<li>{{$u->first_name}} {{$u->last_name}}</li>
												@endforeach
											</ul>
										@endif 
									@endif
								@endforeach
							</td>
							<td>	
								@if($actividad->aprobacion_1 == 0)                            
									<span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span><br><br>       
								@elseif($actividad->aprobacion_1 == 1)          
									<span style="font-size:90%;font-weight:normal;" class="label label-success "><i class="fa fa-check"></i> Aprobado</span><br><br>
								@elseif($actividad->aprobacion_1 == 2)
									<span style="font-size:90%;font-weight:normal;" class="label label-danger "><i class="fa fa-close"></i> Rechazado</span><br><br>
								@elseif($actividad->aprobacion_1==3)
										<span style="font-size:90%;font-weight:normal;" class="label label-info "><i class="fa fa-close"></i> Anulado</span><br><br>
								@endif
								@if($actividad->aprobacion_2 == 0)                            
                        			<span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>          
                   				 @elseif($actividad->aprobacion_2 == 1)          
                        			<span style="font-size:90%;font-weight:normal;" class="label label-success "><i class="fa fa-check"></i> Aprobado</span>
                   				@elseif($actividad->aprobacion_2 == 2)
                        			<span style="font-size:90%;font-weight:normal;" class="label label-danger "><i class="fa fa-close"></i> Rechazado</span>
                    			@elseif($actividad->aprobacion_2 == 3)
                       				 <span style="font-size:90%;font-weight:normal;" class="label label-info "><i class="fa fa-close"></i> Anulado</span>
                   				 @endif
							</td>
							<td>
								@if(Entrust::can('aprobar-actividad-1') && $actividad->aprobacion_1 == 0 || (Entrust::can('aprobar-actividad-1-inferior') && auth()->user()->id != $actividad->user_id) && $actividad->aprobacion_1 == 0)
									<label class="radio-inline">
										<input type="radio" class="radio_tipo radio_actividad" name="actividad_aprobacion_1" value="1"> 1ra Aprobación
									</label>
									<label class="radio-inline">
										<input type="radio" class="radio_tipo radio_actividad" name="actividad_aprobacion_1" value="2"> Rechazar
									</label><br>
								@endif
								@if($actividad->aprobacion_1 == 1 && Entrust::can('anular-actividad-1') || (Entrust::can('anular-actividad-1-inferior') && auth()->user()->id != $actividad->user_id))
									<label class="radio-inline">
										<input type="radio" class="radio_tipo radio_actividad" name="actividad_aprobacion_1" value="3"> Anular
									</label><br>
								@endif
								@if(Entrust::can('aprobar-actividad-2') && $actividad->aprobacion_1 == 1 && $actividad->aprobacion_2 == 0 || (Entrust::can('aprobar-actividad-2-inferior') && auth()->user()->id != $actividad->user_id) && $actividad->aprobacion_1 == 1 && $actividad->aprobacion_2 == 0)
									<label class="radio-inline">
										<input type="radio" class="radio_tipo radio_actividad" name="actividad_aprobacion_2" value="1"> 2da Aprobación
									</label>
									<label class="radio-inline">
										<input type="radio" class="radio_tipo radio_actividad" name="actividad_aprobacion_2" value="2"> Rechazar
									</label>
								@endif
								@if($actividad->aprobacion_1 == 3 && $actividad->aprobacion_2 == 1 && Entrust::can('anular-actividad-2') || (Entrust::can('anular-actividad-2-inferior') && auth()->user()->id != $actividad->user_id))
									<label class="radio-inline">
										<input type="radio" class="radio_tipo radio_actividad" name="actividad_aprobacion_2" value="3"> Anular
									</label>
								@endif
							</td>
					  	</tr>
						@if ($pago->contrato_id != null)
							<tr>
								<td>Decisión</td>
								<td>
									<a href='{{route('decision.show', $decision->id)}}'
										class="btn btn_color aprobar"> Ver
									</a>
								</td>
								<td>
									<b>Aprobación 1</b>
									@foreach ($rolesAprobDec1 as $rol)
										@if ($rol->id != 8)
											@if ($rol->id == 4 || $rol->id == 5 || $rol->id == 9 || $rol->id == 1)
												<ul>
													@foreach ($rol->users as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@elseif ($rol->id == 10)
												<ul>
													@foreach ($rol->users->where('status', 1) as $u)
														@if ($u->oficina->pais->id == $pago->tramitante->oficina->pais->id)	
															<li>{{$u->first_name}} {{$u->last_name}}</li>
														@endif
													@endforeach
												</ul>
											@else (count($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1)) > 0)
												<ul>
													@foreach ($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1) as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@endif	
										@endif
									@endforeach
									@foreach ($rolesAprobDec1Inf as $rol)
										@if ($rol->id != 8)
											@if (count($rol->users->where('status', 1)->where('cargo_id', $pago->tramitante->cargo->superior_id)) > 0 /*&& ($rol->id == 2 || $rol->id == 7 || $rol->id == 3)*/)
												<ul>
													@foreach ($rol->users->where('status', 1)->where('cargo_id', $pago->tramitante->cargo->superior_id) as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@endif 
										@endif
									@endforeach
									<b>Aprobación 2</b>
									@foreach ($rolesAprobDec2 as $rol)
										@if ($rol->id != 8)
											@if ($rol->id == 4 || $rol->id == 5 || $rol->id == 9 || $rol->id == 1)
												<ul>
													@foreach ($rol->users as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@elseif ($rol->id == 10)
												<ul>
													@foreach ($rol->users->where('status', 1) as $u)
														@if ($u->oficina->pais->id == $pago->tramitante->oficina->pais->id)	
															<li>{{$u->first_name}} {{$u->last_name}}</li>
														@endif
													@endforeach
												</ul>
											@else (count($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1)) > 0)
												<ul>
													@foreach ($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1) as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@endif	
										@endif
									@endforeach
									@foreach ($rolesAprobDec2Inf as $rol)
										@if ($rol->id != 8)
											@if (count($rol->users->where('status', 1)->where('cargo_id', $pago->tramitante->cargo->superior_id)) > 0 /*&& ($rol->id == 2 || $rol->id == 7 || $rol->id == 3)*/)
												<ul>
													@foreach ($rol->users->where('status', 1)->where('cargo_id', $pago->tramitante->cargo->superior_id) as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@endif 
										@endif
									@endforeach
								</td>
								<td>
									@if($decision->aprobacion_1==0)
                        				<span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span><br><br>         
                    				@elseif($decision->aprobacion_1==1)          
                        				<span style="font-size:90%;font-weight:normal;" class="label label-success "><i class="fa fa-check"></i> Aprobado</span><br><br>
                    				@elseif($decision->aprobacion_1==2)
                        				<span style="font-size:90%;font-weight:normal;" class="label label-danger "><i class="fa fa-close"></i> Rechazado</span><br><br>
                    				@elseif($decision->aprobacion_1==3)
                        				<span style="font-size:90%;font-weight:normal;" class="label label-info "><i class="fa fa-close"></i> Anulado</span><br><br>
                    				@endif
									@if($decision->aprobacion_2==0)                            
										<span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>          
									@elseif($decision->aprobacion_2==1)          
										<span style="font-size:90%;font-weight:normal;" class="label label-success "><i class="fa fa-check"></i> Aprobado</span>
									@elseif($decision->aprobacion_2==2)
										<span style="font-size:90%;font-weight:normal;" class="label label-danger "><i class="fa fa-close"></i> Rechazado</span>
									@elseif($decision->aprobacion_2==3)
										<span style="font-size:90%;font-weight:normal;" class="label label-info "><i class="fa fa-close"></i> Anulado</span>
									@endif
								</td>
								<td>
									@if(Entrust::can('aprobar-decision-1') && $decision->aprobacion_1 == 0 || (Entrust::can('aprobar-decision-1-inferior') && auth()->user()->id != $decision->actividad->admin->id) && $decision->aprobacion_1 == 0)
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_decision" name="decision_aprobacion_1" value="1"> 1ra Aprobación
										</label>
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_decision" name="decision_aprobacion_1" value="2"> Rechazar
										</label><br>
									@endif
									@if($decision->aprobacion_1 == 1 && Entrust::can('anular-decision-1') || (Entrust::can('anular-decision-1-inferior') && auth()->user()->id != $decision->actividad->admin->id))
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_decision" name="decision_aprobacion_1" value="3"> Anular
										</label><br>
									@endif
									@if(Entrust::can('aprobar-decision-2') && $decision->aprobacion_1 == 1 && $decision->aprobacion_2 == 0 || (Entrust::can('aprobar-decision-2-inferior') && auth()->user()->id != $decision->actividad->admin->id) && $decision->aprobacion_1 == 1 && $decision->aprobacion_2 == 0)
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_decision" name="decision_aprobacion_2" value="1"> 2da Aprobación
										</label>
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_decision" name="decision_aprobacion_2" value="2"> Rechazar
										</label>
									@endif
									@if($decision->aprobacion_1 == 3 && $decision->aprobacion_2 == 1 && Entrust::can('anular-decision-2') || (Entrust::can('anular-decision-2-inferior') && auth()->user()->id != $decision->actividad->admin->id))
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_decision" name="decision_aprobacion_2" value="3"> Anular
										</label>
									@endif
								</td>
							</tr>
						@endif
						@if ($pago->orden_compra_id != null || $pago->contrato_id != null)
							<tr>
								<td>Orden de Compra</td>
								<td>
									<a href='{{route('ordencompra.show', $ordencompra->id)}}'
										class="btn btn_color aprobar"> Ver
									</a>
								</td>
								<td>
									<b>Aprobación Solicitante</b>
									<u>
										<li>{{$pago->tramitante->first_name}} {{$pago->tramitante->last_name}}</li>
									</u>
									<br>
									<b>Aprobación 1</b>
									@foreach ($rolesAprobOrd1 as $rol)
										@if ($rol->id != 8)
											@if ($rol->id == 4 || $rol->id == 5 || $rol->id == 9 || $rol->id == 1)
												<ul>
													@foreach ($rol->users as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@elseif ($rol->id == 10)
												<ul>
													@foreach ($rol->users->where('status', 1) as $u)
														@if ($u->oficina->pais->id == $pago->tramitante->oficina->pais->id)	
															<li>{{$u->first_name}} {{$u->last_name}}</li>
														@endif
													@endforeach
												</ul>
											@else (count($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1)) > 0)
												<ul>
													@foreach ($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1) as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@endif	
										@endif
									@endforeach
									@foreach ($rolesAprobOrd1Inf as $rol)
										@if ($rol->id != 8)
											@if (count($rol->users->where('status', 1)->where('cargo_id', $pago->tramitante->cargo->superior_id)) > 0 /*&& ($rol->id == 2 || $rol->id == 7 || $rol->id == 3)*/)
												<ul>
													@foreach ($rol->users->where('status', 1)->where('cargo_id', $pago->tramitante->cargo->superior_id) as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@endif 
										@endif
									@endforeach
									<b>Aprobación 2</b>
									@foreach ($rolesAprobOrd2 as $rol)
										@if ($rol->id != 8)
											@if ($rol->id == 4 || $rol->id == 5 || $rol->id == 9 || $rol->id == 1)
												<ul>
													@foreach ($rol->users as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@elseif ($rol->id == 10)
												<ul>
													@foreach ($rol->users->where('status', 1) as $u)
														@if ($u->oficina->pais->id == $pago->tramitante->oficina->pais->id)	
															<li>{{$u->first_name}} {{$u->last_name}}</li>
														@endif
													@endforeach
												</ul>
											@else (count($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1)) > 0)
												<ul>
													@foreach ($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1) as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@endif	
										@endif
									@endforeach
									@foreach ($rolesAprobOrd2Inf as $rol)
										@if ($rol->id != 8)
											@if (count($rol->users->where('status', 1)->where('cargo_id', $pago->tramitante->cargo->superior_id)) > 0 /*&& ($rol->id == 2 || $rol->id == 7 || $rol->id == 3)*/)
												<ul>
													@foreach ($rol->users->where('status', 1)->where('cargo_id', $pago->tramitante->cargo->superior_id) as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@endif 
										@endif
									@endforeach
								</td>
								<td>
									@if($ordencompra->aprobacion_solicitante == 0)              
										<span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span><br><br>   
									@elseif($ordencompra->aprobacion_solicitante == 1)          
										<span style="font-size:90%;font-weight:normal;" class="label label-success ">Aprobado</span><br><br>
									@elseif($ordencompra->aprobacion_solicitante == 2)
										<span style="font-size:90%;font-weight:normal;" class="label label-danger ">Rechazado</span><br><br>
									@elseif($ordencompra->aprobacion_solicitante == 3)
										<span style="font-size:90%;font-weight:normal;" class="label label-info ">Anulado</span><br><br>
									@endif
									@if($ordencompra->aprobacion_1 == 0)              
                            			<span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span><br><br>   
                        			@elseif($ordencompra->aprobacion_1 == 1)          
                            			<span style="font-size:90%;font-weight:normal;" class="label label-success ">Aprobado</span><br><br>
                        			@elseif($ordencompra->aprobacion_1 == 2)
                            			<span style="font-size:90%;font-weight:normal;" class="label label-danger ">Rechazado</span><br><br>
                        			@elseif($ordencompra->aprobacion_1 == 3)
                            			<span style="font-size:90%;font-weight:normal;" class="label label-info ">Anulado</span><br><br>
                       				@endif
									@if($ordencompra->aprobacion_2 == 0)              
										<span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>   
									@elseif($ordencompra->aprobacion_2 == 1)          
										<span style="font-size:90%;font-weight:normal;" class="label label-success ">Aprobado</span>
									@elseif($ordencompra->aprobacion_2 == 2)
										<span style="font-size:90%;font-weight:normal;" class="label label-danger ">Rechazado</span>
									@elseif($ordencompra->aprobacion_2 == 3)
										<span style="font-size:90%;font-weight:normal;" class="label label-info ">Anulado</span>
									@endif
								</td>
								<td>
									@if($ordencompra->aprobacion_solicitante == 0 && auth()->user()->id == $ordencompra->solicitante_id)
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_orden" name="orden_aprobacion_sol" value="1"> Aprobación Solicitante
										</label>
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_orden" name="orden_aprobacion_sol" value="2"> Rechazar
										</label><br>
									@endif
									@if($ordencompra->aprobacion_solicitante == 1 && auth()->user()->id == $ordencompra->solicitante_id)
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_orden" name="orden_aprobacion_sol" value="3"> Anular Aprobación Solicitante
										</label><br>
									@endif
									@if(Entrust::can('aprobar-ordencompra-1') && $ordencompra->aprobacion_1 == 0 || (Entrust::can('aprobar-ordencompra-1-inferior') && auth()->user()->id != $ordencompra->solicitante_id) && $ordencompra->aprobacion_1 == 0)
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_orden" name="orden_aprobacion_1" value="1"> 1ra Aprobación
										</label>
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_orden" name="orden_aprobacion_1" value="2"> Rechazar
										</label><br>
									@endif
									@if($ordencompra->aprobacion_solicitante == 3 && $ordencompra->aprobacion_1 == 1 && Entrust::can('anular-ordencompra-1') || (Entrust::can('anular-ordencompra-1-inferior') && auth()->user()->id != $ordencompra->solicitante_id)))
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_orden" name="orden_aprobacion_1" value="3"> Anular
										</label><br>
									@endif
									@if(Entrust::can('aprobar-ordencompra-2') && $ordencompra->aprobacion_1 == 1 && $ordencompra->aprobacion_2 == 0 || (Entrust::can('aprobar-ordencompra-2-inferior') && auth()->user()->id != $ordencompra->solicitante_id) && $ordencompra->aprobacion_1 == 1 && $ordencompra->aprobacion_2 == 0)
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_orden" name="orden_aprobacion_2" value="1"> 2da Aprobación
										</label>
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_orden" name="orden_aprobacion_2" value="2"> Rechazar
										</label>
									@endif
									@if($ordencompra->aprobacion_solicitante == 3 && $ordencompra->aprobacion_1 == 3 && $ordencompra->aprobacion_2 == 1 && Entrust::can('anular-ordencompra-2') || (Entrust::can('anular-ordencompra-2-inferior') && auth()->user()->id != $ordencompra->solicitante_id)))
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_orden" name="orden_aprobacion_2" value="3"> Anular
										</label>
									@endif
								</td>
							</tr>
						@endif
						@if ($pago->contrato_id != null)
							<tr>
								<td>Contrato</td>
								<td>
									<a href='{{route('contrato.view', $contrato->id)}}' class="btn btn_color aprobar"> Ver </a>
								</td>
								<td>
									<b>Revisión Coordinadora</b>
									@foreach ($rolesAprobConCoord as $rol)
										@if ($rol->id != 8)
											@if ($rol->id == 4 || $rol->id == 5 || $rol->id == 9 || $rol->id == 1)
												<ul>
													@foreach ($rol->users as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@elseif ($rol->id == 10)
												<ul>
													@foreach ($rol->users->where('status', 1) as $u)
														@if ($u->oficina->pais->id == $pago->tramitante->oficina->pais->id)	
															<li>{{$u->first_name}} {{$u->last_name}}</li>
														@endif
													@endforeach
												</ul>
											@else (count($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1)) > 0)
												<ul>
													@foreach ($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1) as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@endif	
										@endif
									@endforeach
									<b>Aprobación Directora</b>
									@foreach ($rolesAprobConDir as $rol)
										@if ($rol->id != 8)
											@if ($rol->id == 4 || $rol->id == 5 || $rol->id == 9 || $rol->id == 1)
												<ul>
													@foreach ($rol->users as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@elseif ($rol->id == 10)
												<ul>
													@foreach ($rol->users->where('status', 1) as $u)
														@if ($u->oficina->pais->id == $pago->tramitante->oficina->pais->id)	
															<li>{{$u->first_name}} {{$u->last_name}}</li>
														@endif
													@endforeach
												</ul>
											@else (count($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1)) > 0)
												<ul>
													@foreach ($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1) as $u)
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endforeach
												</ul>
											@endif	
										@endif
									@endforeach
								</td>
								<td>
									@if($contrato->aprobacion_coordinadora == 1)
                    					<span  class="label label-success label_status "><i class="fa fa-check"></i> Confirmada</span><br><br>            
                   					@else
                    					<span  class="label label-warning label_status ">Pendiente</span><br><br>
                   					@endif
									@if($contrato->aprobacion_directora == 1)
									   <span  class="label label-success label_status "><i class="fa fa-check"></i> Aprobado</span>            
									@else
									   <span  class="label label-warning label_status ">Pendiente</span>
									@endif
								</td>
								<td>
									@if (Entrust::can('confirmar-contratos') && $contrato->aprobacion_coordinadora == 0 && $contrato->aprobacion_directora == 0)
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_contrato" name="contrato_aprobacion_coord" value="1"> Revisión Coordinadora
										</label>
									@elseif(Entrust::can('aprobar-contratos') && $contrato->aprobacion_directora == 0 && $contrato->aprobacion_coordinadora == 1)
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_contrato" name="contrato_aprobacion_dir" value="1"> Aprobación Directora
										</label> 
									@endif
									@if($contrato->status == 1 && Entrust::can('anular-contratos'))
										<label class="radio-inline">
											<input type="radio" class="radio_tipo radio_contrato" name="contrato_anular" value="4"> Anular Contrato
										</label>
									@endif
								</td>
							</tr>
						@endif
						<tr>
							<td>Pago</td>
							<td>Visualizado</td>
							<td>
								<b>Aprobación</b>
								@foreach ($rolesAprobPa as $rol)
									@if ($rol->id != 8)
										@if ($rol->id == 4 || $rol->id == 5 || $rol->id == 9 || $rol->id == 1)
											<ul>
												@foreach ($rol->users as $u)
													<li>{{$u->first_name}} {{$u->last_name}}</li>
												@endforeach
											</ul>
										@elseif ($rol->id == 10)
											<ul>
												@foreach ($rol->users->where('status', 1) as $u)
													@if ($u->oficina->pais->id == $pago->tramitante->oficina->pais->id)	
														<li>{{$u->first_name}} {{$u->last_name}}</li>
													@endif
												@endforeach
											</ul>
										@else (count($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1)) > 0)
											<ul>
												@foreach ($rol->users->where('oficina_id', $pago->tramitante->oficina_id)->where('status', 1) as $u)
													<li>{{$u->first_name}} {{$u->last_name}}</li>
												@endforeach
											</ul>
										@endif	
									@endif
								@endforeach
								@foreach ($rolesAprobPaInf as $rol)
									@if ($rol->id != 8)
										@if (count($rol->users->where('status', 1)->where('cargo_id', $pago->tramitante->cargo->superior_id)) > 0 /*&& ($rol->id == 2 || $rol->id == 7 || $rol->id == 3)*/)
											<ul>
												@foreach ($rol->users->where('status', 1)->where('cargo_id', $pago->tramitante->cargo->superior_id) as $u)
													<li>{{$u->first_name}} {{$u->last_name}}</li>
												@endforeach
											</ul>
										@endif 
									@endif
								@endforeach
							</td>
							<td>
								@if($pago->revision == 0)              
                            		<span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>   
                        		@elseif($pago->revision == 1)          
                            		<span style="font-size:90%;font-weight:normal;" class="label label-success ">Aprobado</span>
                        		@elseif($pago->revision == 2)
                            		<span style="font-size:90%;font-weight:normal;" class="label label-danger ">Rechazado</span>
                        		@elseif($pago->revision == 3)
                           			<span style="font-size:90%;font-weight:normal;" class="label label-info ">Anulado</span>
                        		@endif
							</td>
							<td>
								@if(Entrust::can('aprobar-pago') && $pago->revision == 0 || (Entrust::can('aprobar-pago-inferior') && auth()->user()->id != $pago->user_id) && $pago->revision == 0)
	        						<a href='{{url("pago/$pago->id/aprobacion?aprobacion=1")}}' class="btn btn_color aprobar">
										Aprobar Solicitud de Pago
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
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="col-md-12 text-right">

			@if(Entrust::can(['crear-pago-todos','crear-pago-oficina', 'crear-pago-solo']))
				<button type="submit" class="btn btn_color submit" >
		           Actualizar Solicitud de Pago
		        </button>
			@endif

			{{--@if(Entrust::can('aprobar-pago') && $pago->revision == 0 || (Entrust::can('aprobar-pago-inferior') && auth()->user()->id != $pago->user_id) && $pago->revision == 0)
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

	/*$(document).on('click', '.actividad', function(event) {
		event.preventDefault();
		tipo_aprobacion = $(this).attr('class');

		if(tipo_aprobacion.includes("actividad")) {
			url = '{{url("actividad/aprobacion")}}';
			id = {{$actividad->id}};
			if($(this).attr('id') == 'actividad_1') {
				aprobacion_1 = 1;
			}
			else {
				aprobacion_1 = 2;
			}
		}

		$.ajax({
			type:'post',
            url: url,
            dataType: 'json',
            data: {"id": id, "aprobacion_1": aprobacion_1},
            success: function(data) {
                 
                console.log(data);
            },
            error : function() {
                alert('error...');
                //console.log(data);
            },
		});*/
	});
	
</script>
@stop
@section('styles')
    {!! HTML::style('assets/css/bootstrap-datepicker.min.css') !!}
    {!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop
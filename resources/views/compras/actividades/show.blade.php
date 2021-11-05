@extends('layouts.app')

@section('page-title', 'Solicitud de Actividad')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Solicitud de Actividad            
        </h1>
    </div>
</div>

@include('partials.messages')

	<form class="form-horizontal bonos_form" action="{{route('actividad.update', $actividad->id)}}" method="post" enctype="multipart/form-data">
		{!! csrf_field() !!}
		
	<div class="row">

		<div class="form-group">
		    <label class="control-label col-sm-2">Nº Solicitud:</label>
		    <div class="col-sm-2">
		      	<input type="text" id="a1" class="form-control" disabled  value="{{$actividad->correlativo}} " >
		    </div>
		    <label class="control-label col-sm-2">Fecha:</label>
		    <div class="col-sm-2">
		      	<input type="text" id="a2" class="form-control" disabled  value="{{date('Y-m-d', strtotime($actividad->fecha_solicitud))}} " >
		    </div>
		</div>
			
		<div class="form-group">
		    <label class="control-label col-sm-2">Solicitante:</label>
		    <div class="col-sm-4">
		      	<input type="text" id="a3" class="form-control" disabled name="nombre" value="{{$user->first_name}} {{$user->last_name}}" >
		    </div>
		</div>	

		<div class="form-group">
			<label class="control-label col-sm-2">Tramitante:</label>
		    <div class="col-sm-4">
		      	<input type="text" id="a4" class="form-control" disabled name="nombre" value="{{$actividad->admin->first_name}} {{$actividad->admin->last_name}}" >
		    </div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Oficina:</label>
		    <div class="col-sm-4">
		      	<input type="text" id="a5" class="form-control" disabled  value="{{$user->oficina->oficina}}">
		    </div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Actividad:</label>
		    <div class="col-sm-6">
				<textarea class="form-control" required disabled name="actividad">
					{{$actividad->actividad}}
				</textarea>
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Comentarios:</label>
		    <div class="col-sm-6">
				<textarea class="form-control" disabled name="comentarios">
					{{$actividad->comentarios}}
				</textarea>
			</div>
		</div>	

		<div class="form-group">
		    <label class="control-label col-sm-2" >Tipo de proceso de compra:</label>
		    <div class="col-sm-4">
		    	@if($actividad->bono_salud_id != null)
		    		<input type="hidden" name="tipo_compra" value="1">
					<input type="text" class="form-control" value="Pago directo de servicios" disabled>
				@else
			      	<select class="form-control" name="tipo_compra" id="tipo_compra">
			      		<option value="{{$actividad->tipo_compra_id}}">{{$actividad->tipo_compra->valor_generico}}</option>
			      		@foreach($tipoCompra as $tipo)
			      			@if($tipo->id != $actividad->tipo_compra_id)
			      				<option value="{{$tipo->id}}">{{$tipo->valor_generico}}</option>
			      			@endif
			      		@endforeach
			      	</select>
			    @endif
		    </div>
		</div>
		

		@if($actividad->tipo_compra_id == 1 || $actividad->tipo_compra_id == 2)
			<div class="form-group proveedores">
				<label class="control-label col-sm-2" >Proveedor:</label>
				<div class="col-sm-4">
				    <select class="form-control" name="proveedor_id" 
				    	id="proveedores">
				    	@if($actividad->proveedor_id != 0)
				        	<option value="{{$actividad->proveedor_id}}">{{$actividad->proveedor->razon_social}}</option>
				        	<option value="0">Seleccionar un Colega</option>
				        @elseif($actividad->proveedor_id == 0)
				        	<option value="0">Seleccionar un Colega</option>
				        @endif
				      	@foreach($proveedores as $proveedor)
				      		@if($proveedor->id != $actividad->proveedor_id)
				      			<option value="{{$proveedor->id}}">{{$proveedor->razon_social}}</option>
				      		@endif
				      	@endforeach
				    </select>
				</div>
			</div>
		@endif


		<div class="form-group" id="colegas_proveedores" {{$actividad->proveedor_id != 0 ? 'hidden' : ''}}>
			<label class="control-label col-sm-2">Colega Proveedor:</label>
			<div class="col-sm-4">
				<select class="form-control" name="proveedor_user_id">
					@if($actividad->proveedor_user_id != null)
						<option value="{{$actividad->proveedor_user_id}}">
							{{$actividad->proveedorUser->first_name}} {{$actividad->proveedorUser->last_name}}
						</option>
					@endif
					@foreach($provUsers as $user)
						@if($user->id != $actividad->proveedor_user_id)
					    	<option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
					    @endif
					@endforeach
				</select>
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2" >Tipo de Moneda a usar:</label>
		    <div class="col-sm-4">
		      	<select class="form-control" name="tipo_moneda" 
		      		id="tipo_moneda">
					@if($actividad->tipo_moneda_id != 0)
						<option value="{{$actividad->tipo_moneda_id}}">{{$actividad->tipo_moneda->moneda_nombre}}</option>
					@else
		      			<option selected disabled></option>
					@endif
		      		@foreach($tipoMoneda as $moneda)
					  	@if ($moneda->id != $actividad->tipo_moneda_id)
							<option value="{{$moneda->id}}">{{$moneda->moneda_nombre}}</option>
						@endif
		      		@endforeach
		      	</select>
		    </div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-1">Facturas:</label>
				    
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
				      		<th><button id="facturas" class="btn btn_color agregar_factura"><b>+</b></button></th>
				      	</tr>
				    </thead>
				    <tbody class="clone_facturas">
				      	<tr style="display: none;">		      		
				      		<td>
					      		<select class="form-control" name="proyecto[]">
		      						<option></option>
		      						@foreach($proyectos as $proyecto)
		      							<option value="{{$proyecto->id}}">[{{$proyecto->nombre}}] {{$proyecto->descripcion}}</option>
		      						@endforeach
		      					</select>
				      		</td>
				      		
				      		<td>
				      			<select class="form-control" name="cuenta[]">
		      						<option></option>
		      						@foreach($cuentas as $cuenta)
		      							<option value="{{$cuenta->id}}">[{{$cuenta->nombre}}] {{$cuenta->descripcion}}</option>
		      						@endforeach
		      					</select>
				      		</td>

				      		<td>
				      			<input class="form-control" type="text" name="factura[]" placeholder="nro de factura">
				      		</td>
							
							<td>
								<select class="form-control" name="centroCosto[]">
									<option></option>
									@foreach($centrosCosto as $centro)
										<option value="{{$centro->id}}">[{{$centro->codigo}}] {{$centro->descripcion}}</option>
									@endforeach
								</select>
							</td>

							<td>
								<select class="form-control" name="lineaPresup[]">
									<option></option>
									@foreach($lineasPresupuestarias as $linea)
										<option value="{{$linea->id}}">[{{$linea->codigo}}] {{$linea->descripcion}}</option>
									@endforeach
								</select>
							</td>

				      		<td>
				      			<input name="file_factura[]" class="form-control" type="file" >
				      		</td>

				      		<td>
				      			<input type="number" step="0.0001" class="form-control monto" name="monto[]" value="0.00">
				      		</td>			      				
				      				
				      		<td> 
				      			<button class="btn btn_color btn_rojo remover_fact">
				      				<i class="glyphicon glyphicon-trash"></i>
				      			</button> 
				      		</td>
				      				
				      	</tr>
				      			
				      	@foreach($actividad->detalle as $key => $detalle)
				      		<tr>	      		
					      		<td>
						      		<select class="form-control" name="proyecto[]">
						      			<option value="{{$detalle->proyecto_id}}">[{{$detalle->proyecto->nombre}}] {{$detalle->proyecto->descripcion}}</option>
			      						@foreach($proyectos as $proyecto)
			      							<option value="{{$proyecto->id}}">[{{$proyecto->nombre}}] {{$proyecto->descripcion}}</option>
			      						@endforeach
			      					</select>
					      		</td>
				      		
				      		<td>
				      			<select class="form-control" name="cuenta[]">
				      				<option value="{{$detalle->cuenta_id}}">[{{$detalle->cuenta->nombre}}] {{$detalle->cuenta->descripcion}}</option>
		      						@foreach($cuentas as $cuenta)
		      							<option value="{{$cuenta->id}}">[{{$cuenta->nombre}}] {{$cuenta->descripcion}}</option>
		      						@endforeach
		      					</select>
				      		</td>

				      		<td>
				      			<input class="form-control" type="text" name="factura[]" value="{{$detalle->factura}}" placeholder="nro de factura">
				      		</td>
							
							<td>
								<select class="form-control" name="centroCosto[]">
									<option value="{{$detalle->centro_costo_id != 0 ? $detalle->centro_costo_id : ''}}">{{$detalle->centro_costo_id != 0 ? '['.$detalle->centroCosto->codigo.']' : ''}} {{$detalle->centro_costo_id != 0 ? $detalle->centroCosto->descripcion : ''}}</option>
									@foreach($centrosCosto as $centro)
										@if ($centro->id != $detalle->centro_costo_id)
											<option value="{{$centro->id}}">[{{$centro->codigo}}] {{$centro->descripcion}}</option>
										@endif
									@endforeach
								</select>
							</td>

							<td>
								<select class="form-control" name="lineaPresup[]">
									<option value="{{$detalle->linea_presupuestaria_id != 0 ? $detalle->linea_presupuestaria_id : ''}}">{{$detalle->linea_presupuestaria_id != 0 ? '['.$detalle->lineaPresupuestaria->codigo.']' : ''}} {{$detalle->linea_presupuestaria_id != 0 ? $detalle->lineaPresupuestaria->descripcion : ''}}</option>
									@foreach($lineasPresupuestarias as $linea)
										@if ($linea->id != $detalle->linea_presupuestaria_id)
											<option value="{{$linea->id}}">[{{$linea->codigo}}] {{$linea->descripcion}}</option>
										@endif
									@endforeach
								</select>
							</td>

				      		<td>
				      			<label>{{$detalle->file}}</label>
				      		</td>

				      		<td>
				      			<input type="number" step="0.0001" class="form-control monto" name="monto[]" value="{{$detalle->monto}}">
				      		</td>			      				
				      				
				      		<td> 
				      			<a href='{{route('actividad.downloadDocument',$detalle->file)}}'	 
				      				class="btn btn_color btn_download"
				      				style="background: #5cb85c;">
				      					<i class="glyphicon glyphicon-download-alt"></i>
				      			</a> 
				      			{{--<a href='{{url("actividad/$detalle->id/deleteDoc?factura=1")}}'  
				      				class="btn btn_color btn_rojo" 
									title="Eliminar documento  {{$detalle->file}}"
									data-toggle="tooltip"
									data-placement="top"
									data-method="DELETE"
									data-confirm-title="Eliminar Documento "
									data-confirm-text="Esta seguro de querer eliminar este documento"
									data-confirm-delete="Borrar"
								>
									<i class="glyphicon glyphicon-trash"></i>
								</a>--}} 
				      		</td>
				      				
				      	</tr>
				      	@endforeach	
				    </tbody>	      		
				</table>
			</div>		    
		</div>

		<div class="form-group monto_group" {{$actividad->tipo_compra_id >= 3 ? 'hidden' : ''}}>
		    <label class="control-label col-sm-2">Monto:</label>
		    <div class="col-sm-2">
				<p id="moneda_simbolo">{{$actividad->tipo_moneda_id != 0 ? $actividad->tipo_moneda->moneda_simbolo : ''}}</p> <input type="number" step="0.0000001" class="form-control monto_total" name="monto_total" id="monto_total" value="{{$actividad->monto}}"> <p id="moneda_nombre">{{$actividad->tipo_moneda_id != 0 ? $actividad->tipo_moneda->moneda_nombre : ''}}</p>
		    </div>
		    <label class="control-label col-sm-2">Tipo Moneda:</label>		    
		    <div class="col-sm-2">
		      	($) <input type="number" step="0.0000001" class="form-control tipo_cambio_usd" name="tipo_cambio_usd" id="tipo_cambio_usd" value="{{$user->oficina->pais->tasa_conv_usd}}"> (SK) <input type="number" step="0.0000001" class="form-control tipo_cambio_corona" name="tipo_cambio_corona" id="tipo_cambio_corona" value="{{$user->oficina->pais->tasa_conv_corona}}"> 
		    </div>		
		</div>

		<div class="form-group monto_group" {{$actividad->tipo_compra_id >= 3 ? 'hidden' : ''}}>
		    <label class="control-label col-sm-2">Monto ($):</label>
		    <div class="col-sm-2">
		      	<input type="number" step="0.0000001" class="form-control monto_usd" id="monto_usd" name="monto_usd" value="{{$actividad->monto_usd}}">
		    </div>
		    <label class="control-label col-sm-2">Monto (SK):</label>		    
		    <div class="col-sm-2">
		      	<input type="number" step="0.0000001" class="form-control monto_sk" id="monto_sk" name="monto_sk" value="{{$actividad->monto_sk}}">
		    </div>
		    <div class="col-sm-2">
		    	<button  
			        class="calculo_monto btn btn-primary"
					data-toggle="tooltip" 
				    title="Se calcularan los acumulados como tambien los totales">
				        Total <i class="fa fa-question-circle" ></i>
				</button>
		    </div>			
		</div>	

		<div class="form-group">
			<label class="control-label col-sm-2">Documentos:</label>
				    
			<div class="col-sm-8">
				    	
				<table class=" table table-bordered">

				    <thead>
				      	<tr>
				      		<th>#</th>
				      		<th>Fecha</th>				      				
				      		<th>Tipo de documento</th>
				      		<th>Documento ( cada documento no debe pesar mas de 10mb)</th>
				      		<th>Proveedor (no obligatorio)</th>
				      		<th><button id="documentos" class="btn btn_color agregar_documento"><b>+</b></button></th>
				      	</tr>
				    </thead>
				    <tbody class="clone_documentos">
				      	<tr style="display: none;">
				      		<td class="num_docs" align="center"></td>		      							      		
				      		<td>
				      			<div class="form-group">
				      				<div class="col-sm-8">
					      				<div class="input-group datepicker">
					      					<input type="date" name="fecha_documento[]" class="form-control">
					      					<div class="input-group-addon">
						        				<span class="glyphicon glyphicon-calendar"></span>
						   					</div>
					      				</div>
				      				</div>
				      			</div>
				      		</td>
				      		
				      		<td>
				      			<select class="form-control" name="tipo_documento[]">
				      				<option value="0">Seleccione un tipo</option>
				      				@foreach($tipoDoc as $doc)
				      					<option value="{{$doc->id}}">{{$doc->nombre}}</option>
				      				@endforeach
				      			</select>
				      		</td>

				      		<td>
				      			<input name="file_documento[]" class="form-control" type="file" >
				      		</td>

				      		<td>
				      			<select class="form-control" name="proveedor_documento[]">
					      			<option></option>
					      			@foreach($proveedores as $proveedor)
					      				<option value="{{$proveedor->id}}">{{$proveedor->razon_social}}</option>
					      			@endforeach
					      		</select>
				      		</td>			      				
				      				
				      		<td> 
				      			<button class="btn btn_color btn_rojo remover_doc">
				      				<i class="glyphicon glyphicon-trash"></i>
				      			</button> 
				      		</td>
				      				
				      	</tr>
				      	
				      	@foreach($actividad->documento as $key => $documento)
				      		<tr>
				      			<td class="num_docs" align="center">{{$key+1}}</td>

				      			<td>
				      				<div class="form-group">
				      					<div class="col-sm-12">
					      					<div class="input-group datepicker">
					      						<input type="date" name="fecha_documento[]" class="form-control" value="{{date('Y-m-d', strtotime($documento->fecha))}}">
					      						<div class="input-group-addon">
						        					<span class="glyphicon glyphicon-calendar"></span>
						   						</div>
					      					</div>
				      					</div>
				      				</div>
				      			</td>
				      		
					      		<td>
					      			<select class="form-control" name="tipo_documento[]">
					      				<option value="{{$documento->tipo_documento_compras_id}}">
					      					{{$documento->tipo_documento->nombre}}
					      				</option>
					      				@foreach($tipoDoc as $doc)
					      					@if($doc->id != $documento->tipo_documento_compras_id)
					      						<option value="{{$doc->id}}">{{$doc->nombre}}</option>
					      					@endif
					      				@endforeach
				      			</select>
					      		</td>

					      		<td>
					      			<label>{{$documento->file}}</label>
					      		</td>

					      		<td>
					      			<select class="form-control" 
					      				name="proveedor_documento[]">
					      				@if($documento->proveedor_id != null)
					      					<option value="{{$documento->proveedor_id}}">{{$documento->proveedor->razon_social}}</option>
					      				@else
					      					<option></option>
					      				@endif
					      				@foreach($proveedores as $proveedor)
					      					@if($proveedor->id != $documento->proveedor_id)
					      						<option value="{{$proveedor->id}}">{{$proveedor->razon_social}}</option>
					      					@endif
					      				@endforeach
					      			</select>
					      		</td>			      				
					      				
					      		<td>
					      			<a href='{{route('actividad.downloadDocument',$documento->file)}}'
				      					 
				      					 class="btn btn_color btn_download"
				      					 style="background: #5cb85c;">
				      						<i class="glyphicon glyphicon-download-alt"></i>
				      				</a> 
					      			<button class="btn btn_color btn_rojo remover_doc">
					      				<i class="glyphicon glyphicon-trash"></i>
					      			</button> 
					      		</td>
				      				
				      		</tr>		      	
				      	@endforeach

				      	
				    </tbody>	      		
				</table>
			</div>		    
		</div>
		
		<div class="col-md-12 text-right">

			{{--@if($actividad->aprobacion_1 == 1 && Entrust::can('anular-actividad-1') || (Entrust::can('anular-actividad-1-inferior') && auth()->user()->id != $actividad->user_id))
				<a href='{{url("actividad/$actividad->id/aprobacion?aprobacion_1=3")}}' class="btn btn_color btn_rojo aprobar">
					Anular 1ra Aprobación
				</a>
			@endif

			@if($actividad->aprobacion_1 == 3 && $actividad->aprobacion_2 == 1 && Entrust::can('anular-actividad-2') || (Entrust::can('anular-actividad-2-inferior') && auth()->user()->id != $actividad->user_id))
				<a href='{{url("actividad/$actividad->id/aprobacion?aprobacion_2=3")}}' class="btn btn_color btn_rojo aprobar">
					Anular 2da Aprobación
				</a>
			@endif--}}

			@if(Entrust::can(['crear-pago-todos', 'crear-pago-oficina', 'crear-pago-solo']) /*&& $actividad->aprobacion_1 == 1 && $actividad->aprobacion_2 == 1*/ && $actividad->tipo_compra_id == 1 && $actividad->pago == '')
				<a href='{{url("pago/$actividad->id/create?actividad=1")}}' class="btn btn_color orden_compra">
					<i class="glyphicon glyphicon-usd"></i> Crear Orden de Pago
				</a>
			@else
				@if(Entrust::can('ver-pago-info') && $actividad->pago != '')
					<a href='{{route('pago.show', $actividad->pago->id)}}' class="btn btn_color orden_compra">
						<i class="glyphicon glyphicon-usd"></i> Ver Orden de Pago
					</a>
				@endif
			@endif

	        @if(Entrust::can(['crear-ordencompra-todos', 'crear-ordencompra-oficina', 'crear-ordencompra-solo']) /*&& $actividad->aprobacion_1 == 1 && $actividad->aprobacion_2 == 1*/ && $actividad->tipo_compra_id == 2 && $actividad->ordencompra == '')
		        <a href='{{url("ordencompra/$actividad->id/create?actividad=1")}}' class="btn btn_color orden_compra">
					<i class="glyphicon glyphicon-shopping-cart"></i> Orden de Compra
				</a>
			@else
				@if(Entrust::can('ver-ordencompra-info') && $actividad->ordencompra != '')
					<a href='{{route('ordencompra.show', $actividad->ordencompra->id)}}' class="btn btn_color orden_compra">
						<i class="glyphicon glyphicon-usd"></i> Ver Orden de Compra
					</a>
				@endif
	        @endif

	        @if(Entrust::can(['crear-decision-todos', 'crear-decision-oficina']) /*&& $actividad->aprobacion_1 == 1 && $actividad->aprobacion_2 == 1*/ && $actividad->tipo_compra_id != 1 && $actividad->tipo_compra_id != 2 && $actividad->decision == '')
	        		<a href='{{url("decision/$actividad->id/create")}}' class="btn btn_color decision">
						<i class="glyphicon glyphicon-list-alt"></i> Crear Decisión
					</a>
			@else
				@if(Entrust::can('ver-decision-info') && $actividad->decision != '')
					<a href='{{route('decision.show', $actividad->decision->id)}}' class="btn btn_color orden_compra">
						<i class="glyphicon glyphicon-usd"></i> Ver Decisión
					</a>
				@endif 
	        @endif
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
		
		$(this)
			.find(':input[type=submit]')
		    .attr('disabled', true)
		    .html('<i class="glyphicon glyphicon-refresh"></i> Guardando datos')
		    .css('color', '#000');
		
		this.submit();
	});

	$(document).on('click', '.agregar_documento, .agregar_factura', function(event) {
		event.preventDefault();

		id = $(this).attr('id');
		clone = $('.clone_'+id+' tr:first').clone(true);			
		clone.show().find('input').val('');
		$('.clone_'+id).append(clone);

		count_docs();			
	});

	$(document).on('click', '.remover_doc, .remover_fact', function(event) {			
		$(this).closest('tr').remove();				
	});

	function count_docs(arg) {
		num_docs = 0;

		$('.num_docs').each(function (index, el) {

			$(this).text(num_docs);
				num_docs++;
		});
	}

	$(document).on('change', '#tipo_compra', function(event) {

		event.preventDefault();

		tipo_compra = $("#tipo_compra").val();

		if(tipo_compra == 1 || tipo_compra == 2) {

			if(tipo_compra == 1) {

				$(".pago").attr('hidden', false);
			}
			if (tipo_compra == 2) {

				$(".proveedores").attr('hidden', false);
			}
			$(".monto_group").attr('hidden', false);
		}
		else {

			$(".monto_group").attr('hidden', true);
		}
	});

	$(document).on('change', '#tipo_moneda', function(event) {
		
		event.preventDefault();
		
		let moneda = $('#tipo_moneda').val(); 

		$.ajax({
			type:'post',
            url: '{{ url('actividad/buscarMoneda') }}',
            dataType: 'json',
            data: {"moneda": moneda},
            success: function(data) {
            	$("#moneda_nombre").html(data['moneda_nombre']);
            	$("#moneda_simbolo").html(data['moneda_simbolo']); 

                //console.log(data);
                //alert(data.success);
            },
            error : function() {
                alert(data);
                //console.log(data);
            },
		});
	});

	$(document).on('click', '.calculo_monto', function(event) {
		event.preventDefault();
		sumar();
	});

	function sumar() {
		
		var total = 0;

		$(".monto").each(function() {

			if (isNaN(parseFloat($(this).val()))) {
				total += 0;
			}
			else {
				total += parseFloat($(this).val());
			}
		});

		document.getElementById('monto_total').value = total;
		monto = $("#monto_total").val();
		tipo_cambio_usd = $("#tipo_cambio_usd").val();
		tipo_cambio_corona = $("#tipo_cambio_corona").val();

		monto_usd = parseFloat(monto) / parseFloat(tipo_cambio_usd);
		monto_sk = (parseFloat(monto) / parseFloat(tipo_cambio_usd)) * parseFloat(tipo_cambio_corona);

		$("#monto_usd").val(monto_usd.toFixed(2));
		$("#monto_sk").val(monto_sk.toFixed(2));
		$("#monto_total").val(total.toFixed(2));
	}

	$(document).on('change', '.monto_total, .tipo_cambio_usd, .tipo_cambio_corona', function (event) {

		event.preventDefault();
		sumar();
		monto = $("#monto_total").val();
		tipo_cambio_usd = $("#tipo_cambio_usd").val();
		tipo_cambio_corona = $("#tipo_cambio_corona").val();

		monto_usd = parseFloat(monto) / parseFloat(tipo_cambio_usd);
		monto_sk = (parseFloat(monto) / parseFloat(tipo_cambio_usd)) * parseFloat(tipo_cambio_corona);

		$("#monto_usd").val(monto_usd.toFixed(2));
		$("#monto_sk").val(monto_sk.toFixed(2));

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

</script>
@stop
@section('styles')
    {!! HTML::style('assets/css/bootstrap-datepicker.min.css') !!}
    {!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop
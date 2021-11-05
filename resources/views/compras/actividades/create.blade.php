@extends('layouts.app')

@section('page-title', 'Solicitud de Actividad')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Solicitud de Actividad
            <small>Favor llenar todos los campos.</small>            
        </h1>
    </div>
</div>

@include('partials.messages')

	<form class="form-horizontal bonos_form" action="{{route('actividad.store')}}" method="post" enctype="multipart/form-data">
		{!! csrf_field() !!}
		<input type="hidden" name="solicitante" value="{{$user->id}}">
		<input type="hidden" name="bono_salud_id" value="{{$bono_salud != null ? $bono_salud->id : null}}">
		<input type="hidden" name="correlativo" value="{{$correlativo}}">
		<input type="hidden" name="persona_administrativa" value="{{auth()->user()->id}}">
		<input type="hidden" name="oficina_id" value="{{$user->oficina_id}}">
		<input type="hidden" name="tipo_moneda" value="{{$user->oficina->pais->moneda_nombre}}">
		
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
		    <label class="control-label col-sm-2">Solicitante:</label>
		    <div class="col-sm-4">
		      	<input type="text" id="a3" class="form-control" disabled name="nombre" value="{{$user->first_name}} {{$user->last_name}}" >
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
		    <label class="control-label col-sm-2">Actividad:</label>
		    <div class="col-sm-6">
				<textarea class="form-control" required name="actividad"></textarea>
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Comentarios:</label>
		    <div class="col-sm-6">
				<textarea class="form-control" name="comentarios"></textarea>
			</div>
		</div>	

		<div class="form-group">
			<label class="control-label col-sm-2" >Tipo de proceso de compra:</label>
			<div class="col-sm-4">
				@if($bono_salud != null)
					<input type="hidden" name="tipo_compra" value="1">
					<input type="text" class="form-control" value="Pago directo de servicios" disabled>
		    	@else
			      	<select class="form-control" name="tipo_compra" id="tipo_compra" required>
			      		@foreach($tipoCompra as $tipo)
			      			<option value="{{$tipo->id}}">{{$tipo->valor_generico}}</option>
			      		@endforeach
			      	</select>
			    @endif
		    </div>
		</div>

		<div class="form-group proveedores">
		    <label class="control-label col-sm-2" >Proveedor:</label>
		    <div class="col-sm-4">
		      	<select class="form-control" name="proveedor_id" 
		      		id="proveedores">
		      		<option selected disabled></option>
		      		@foreach($proveedores as $proveedor)
		      			<option value="{{$proveedor->id}}">{{$proveedor->razon_social}}</option>
		      		@endforeach
		      		<option value="0">Seleccionar un Colega</option>
		      	</select>
		    </div>
		</div>

		<div class="form-group" id="colegas_proveedores" hidden>
			<label class="control-label col-sm-2">Colega Proveedor:</label>
			<div class="col-sm-4">
				<select class="form-control" name="proveedor_user_id">
				    <option></option>
				    @foreach($provUsers as $user)
				      	<option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
				    @endforeach
				</select>
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2" >Tipo de Moneda a usar:</label>
		    <div class="col-sm-4">
		      	<select class="form-control" name="tipo_moneda" 
		      		id="tipo_moneda">
		      		<option selected disabled></option>
		      		@foreach($tipoMoneda as $moneda)
		      			<option value="{{$moneda->id}}">{{$moneda->moneda_nombre}}</option>
		      		@endforeach
		      	</select>
		    </div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-1">Facturas:</label>
				    
			<div class="col-sm-11">
				    	
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
				      			
				      	@for($i = 1; $i <= 1; $i++)
				      		<tr>
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
				      	@endfor	
				    </tbody>	      		
				</table>
			</div>		    
		</div>	

		@if($bono_salud != null)
			<div class="form-group monto_group">
		    	<label class="control-label col-sm-2">Monto Total:</label>
		    	<div class="col-sm-2">
		      		<p id="moneda_simbolo"></p> <input type="number" step="0.0000001" class="form-control monto_total" name="monto_total" id="monto_total" value="{{$bono_salud->costo_moneda_local}}"> <p id="moneda_nombre"></p>
		    	</div>
		    	<label class="control-label col-sm-2">Tipo Moneda:</label>		    
		    	<div class="col-sm-2">
		      		($) <input type="number" step="0.0000001" class="form-control tipo_cambio_usd" name="tipo_cambio_usd" id="tipo_cambio_usd" value="{{$user->oficina->pais->tasa_conv_usd}}"> (SK) <input type="number"	step="0.0000001" class="form-control tipo_cambio_corona" name="tipo_cambio_corona" id="tipo_cambio_corona" value="{{$user->oficina->pais->tasa_conv_corona}}"> 
		    	</div>		
			</div>

			<div class="form-group monto_group">
		    	<label class="control-label col-sm-2">Monto ($):</label>
		    	<div class="col-sm-2">
		      		<input type="number" step="0.0000001" class="form-control monto_usd" id="monto_usd" name="monto_usd" value="{{number_format($bono_salud->costo_moneda_local / $user->oficina->pais->tasa_conv_usd, 2)}}">
		    	</div>
		    	<label class="control-label col-sm-2">Monto (SK):</label>		    
		    	<div class="col-sm-2">
		      		<input type="number" step="0.0000001" class="form-control monto_sk" id="monto_sk" name="monto_sk" value="{{number_format($bono_salud->costo_moneda_local / $user->oficina->pais->tasa_conv_corona, 2)}}">
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
		@else
			<div class="form-group monto_group">
		    	<label class="control-label col-sm-2">Monto Total:</label>
		    	<div class="col-sm-2">
					<p id="moneda_simbolo"></p> <input type="number" step="0.0000001" class="form-control monto_total" name="monto_total" id="monto_total" value="0.00"> <p id="moneda_nombre"></p>
		    	</div>
		    	<label class="control-label col-sm-2">Tipo Moneda:</label>		    
		    	<div class="col-sm-2">
		      		($) <input type="number" step="0.0000001" class="form-control tipo_cambio_usd" name="tipo_cambio_usd" id="tipo_cambio_usd" value="{{$user->oficina->pais->tasa_conv_usd}}"> (SK) <input type="number" step="0.0000001" class="form-control tipo_cambio_corona" name="tipo_cambio_corona" id="tipo_cambio_corona" value="{{$user->oficina->pais->tasa_conv_corona}}"> 
		    	</div>		
			</div>

			<div class="form-group monto_group">
			    <label class="control-label col-sm-2">Monto ($):</label>
			    <div class="col-sm-2">
			      	<input type="number" step="0.0000001" class="form-control monto_usd" id="monto_usd" name="monto_usd" value="0.00">
			    </div>
			    <label class="control-label col-sm-2">Monto (SK):</label>		    
			    <div class="col-sm-2">
			      	<input type="number" step="0.0000001" class="form-control monto_sk" id="monto_sk" name="monto_sk" value="0.00">
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
		@endif

		<div class="form-group">
			<label class="control-label col-sm-2">Documentos:</label>
				    
			<div class="col-sm-9">
				    	
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
				      				<option></option>
				      				@foreach($tipoDoc as $doc)
				      					<option value="{{$doc->id}}">{{$doc->nombre}}</option>
				      				@endforeach
				      			</select>
				      		</td>

				      		<td>
				      			<input name="file_documento[]" class="form-control" type="file" >
				      		</td>

				      		<td>
				      			<select 
				      				class="form-control" 
				      				name="proveedor_documento[]">
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
				      			
				      	@for($i = 1; $i <= 1; $i++)
				      		<tr>
				      			<td class="num_docs" align="center">{{$i}}</td>

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
					      				<option></option>
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
				      	@endfor	
				    </tbody>	      		
				</table>
			</div>		    
		</div>
		
		<div class="col-md-12 text-right">

			@if(Entrust::can(['crear-actividad-todos','crear-actividad-oficina','crear-actividad-solo']))
				<button type="submit" class="btn btn_color submit" >
		           Crear Solicitud de Actividad
		        </button>
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

			$(".monto_group").attr('hidden', false);
			$(".proveedores").attr('hidden', false);
		}
		else {

			$(".monto_group").attr('hidden', true);
			$(".proveedores").attr('hidden', true);
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
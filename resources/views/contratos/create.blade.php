@extends('layouts.app')

@section('page-title', 'Creacion de contrato')

@section('content')
 
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Contratos | Rola 

				@if($edit && Entrust::can('aprobar-contratos') && $contrato->aprobacion_directora == 0)
					@if($contrato->aprobacion_coordinadora)
					 	
					 	<a href='{{url("/aprobacion/contrato/$contrato->id")}}' class="btn btn_color">
							<i class="fa fa-check-square" aria-hidden="true"></i> Aprobar Contrato
						</a>  
					@else	
						<span title="Este contrato aun sigue en revision debe esperar a ser confirmada" data-toggle="tooltip" data-placement="top">
			                <button disabled class="btn btn_color">
			                	<i class="fa fa-check-square" aria-hidden="true"></i> Aprobar Contrato
			                </button>
			            </span>
												 	 	
					@endif
				@endif

				@if($edit && Entrust::can('confirmar-contratos') && !$contrato->aprobacion_coordinadora )					
					 	
					 	<a href='{{url("/aprobacion/contrato/$contrato->id")}}' class="btn btn_color">
							<i class="fa fa-check-square" aria-hidden="true"></i> Confirmar Contrato
						</a>		
						
				@endif

				@if($edit && $contrato->status == 1 && Entrust::can('anular-contratos'))
					<a href='{{url("/contrato/$contrato->id/anulacion")}}' class="btn btn_color">
						<i class="fa fa-check-square" aria-hidden="true"></i> Anular Contrato
					</a>
				@endif

				@if($edit && Entrust::can(['crear-pago-todos', 'crear-pago-oficina']) && $contrato->aprobacion_coordinadora == 1 && $contrato->ordencompra_id != null && $contrato->pago == '')
					<a href='{{url("pago/$contrato->id/create?actividad=2")}}' class="btn btn_color aprobar">
						<i class="glyphicon glyphicon-usd"></i> Crear Orden de Pago
					</a>
				@else
					@if($edit && Entrust::can('ver-pago-info') && $contrato->pago != '')
						<a href='{{route('pago.show', $contrato->pago->id)}}' class="btn btn_color aprobar">
							<i class="glyphicon glyphicon-usd"></i> Ver Orden de Pago
						</a>
					@endif
				@endif
			    	               
	    </h1>
        
    </div>
</div>

@include('partials.messages')
	
	<form class="form-horizontal form_contrato" action="{{url('/contrato/store')}}" method="post" enctype="multipart/form-data">
		
		@if($edit)
			<input type="hidden" name="contrato_id" value="{{$contrato->id}}">
		@else
			@if($user)
				<input type="hidden" name="user_id" value="{{$user->id}}">
			@else
				@if($proveedor)
					<input type="hidden" name="proveedor_id" value="{{$proveedor->id}}">
				@elseif($proveedor_user)
					<input type="hidden" name="proveedor_id" value="{{$proveedor_user->id}}">
				@endif
			@endif
		<input type="hidden" name="oficina_id" value="@if($user) {{$user->oficina->id}} @elseif($proveedor) {{$proveedor->oficina_id}} @else {{$proveedor_user->oficina_id}} @endif">
		<input type="hidden" name="directora_id" value="{{$directora->id}}">
		@endif
		@if(!$edit && $ordencompra != 0)
			<input type="hidden" name="ordencompra_id" value="{{$ordencompra_id}}">
		@endif
		{!! csrf_field() !!}
		<div class="row ">	
			<div class="col-lg-12">
				<div class="form-group">
				    <label class="control-label col-sm-2">Consultor:</label>
				    <div class="col-sm-4">
				      	<input type="text" id="a1" class="form-control" disabled name="nombre" 
				      	value="@if($user) {{$user->first_name}} {{$user->last_name}} @elseif($proveedor) {{$proveedor->nombre}} @else {{$proveedor_user->first_name}} {{$proveedor_user->last_name}} @endif" >
				    </div>

				</div>			

				<div class="form-group">
				    <label class="control-label col-sm-2">Nombre de la consultoría:</label>
				    <div class="col-sm-4">
				      	<input type="text" required class="form-control" name="consultoria" 
				      	value='{{$edit?"$contrato->consultoria":""}}' >
				    </div>
				</div>

				<div class="form-group">
				    <label class="control-label col-sm-2">Nº de contrato:</label>
				    <div class="col-sm-4">
				      	<input type="text" required class="form-control" readonly="readonly" name="n_contrato"
				      	value='{{$edit?"$contrato->n_contrato":"$correlativo"}}' >
				    </div>
				</div>

				<div class="form-group">
				    <label class="control-label col-sm-2">Objetivo:</label>
				    <div class="col-sm-10">
				      	<textarea class="form-control" required name="objetivo">{{$edit?"$contrato->objetivo":""}}</textarea>
				    </div>
				</div>
				<div class="form-group">
				    <label class="control-label col-sm-2">Alcance:</label>
				    <div class="col-sm-10">				      	
				      	<textarea class="form-control" required name="alcance">{{$edit?"$contrato->alcance":""}}</textarea>
				    </div>
				</div>
				<div class="form-group">
				    <label class="control-label col-sm-2">Actividades:</label>
				    <div class="col-sm-10">
				      	<textarea class="form-control" required name="actividades">{{$edit?"$contrato->actividades":""}}</textarea>
				    </div>
				</div>
				<div class="form-group">
				    <label class="control-label col-sm-2">Metodología:</label>
				    <div class="col-sm-10">
				      	<textarea class="form-control" required name="metodologia">{{$edit?"$contrato->metodologia":""}}</textarea>
				    </div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-2">Fecha de Contrato:</label>
					<div class="col-sm-3">
						<div class="input-group datepicker">
						    <input type="text" class="form-control"  name="fecha_contrato" value='{{$edit?$contrato->fecha_contrato:date("Y-m-d")}}'>
						    <div class="input-group-addon">
						        <span class="glyphicon glyphicon-calendar"></span>
						    </div>
						</div>
					</div>
				</div>	
				
				<div class="form-group">
				    <label class="control-label col-sm-2">Período de contratación:</label>
				    <div class="col-sm-4">
				    	<div class="input-group input-daterange">

				    		<input type="text" id="min-date" class="form-control date-range-filter" required data-date-format="yyyy-mm-dd" name="fecha_inicio"
				    		value='{{$edit?"$contrato->fecha_inicio":""}}' >

				    		<div class="input-group-addon">Hasta</div>

				    		<input type="text" id="max-date" class="form-control date-range-filter" required data-date-format="yyyy-mm-dd" name="fecha_fin"
				    		value='{{$edit?"$contrato->fecha_fin":""}}' >
				    	</div>
				    </div>
				</div>

				<div class="form-group">
				    <label class="control-label col-sm-2">Cantidad de Pagos:</label>
				    
				    <div class="col-sm-10">
				      	<table class=" table table-bordered">
				      		<thead>
				      			<tr>
				      				<th>Pago</th>
				      				<th colspan="2">Monto</th>
				      				<th style="width: 50%;">Producto</th>
				      				<th><button id="pagos" class="btn btn_color agregar_pago"><b>+</b></button></th>
				      			</tr>
				      		</thead>
				      		<tbody class="clone_pagos pagos_contrato">
				      			<tr style="display: none;">
				      				<td class="num_pago" align="center"></td>

				      				<td>
				      					<input name="monto[]" placeholder="Monto en Digitos" class="form-control monto"  type="number" step="0.0000001">
				      				</td>

				      				<td>
				      					<input name="monto_l[]" placeholder="Monto en Letras" class="form-control" type="text" >
				      				</td>

				      				<td>
				      					<input name="monto_producto[]" class="form-control" type="text">
				      				</td>
				      				
				      				<td> 
				      					<button class="btn btn_color btn_rojo remover_pago">
				      						<i class="glyphicon glyphicon-trash"></i>
				      					</button> 
				      				</td>
				      				
				      			</tr>
				      			@if($edit)
					      			@foreach($contrato->pagos as $pagos)
					      				<tr>
						      				<td class="num_pago" align="center"></td>

						      				<td>
						      					<input name="monto[]" placeholder="Monto en Digitos" class="form-control monto"  type="number" step="0.0000001" 
						      					value="{{$pagos->monto}}">
						      				</td>

						      				<td>
						      					<input name="monto_l[]" placeholder="Monto en Letras" class="form-control" type="text" 
						      					value="{{$pagos->monto_l}}">
						      				</td>

						      				<td>
						      					<input name="monto_producto[]" class="form-control" type="text"
						      					value="{{$pagos->monto_producto}}" >
						      				</td>
						      				
						      				<td> 
						      					<button class="btn btn_color btn_rojo remover_pago">
						      						<i class="glyphicon glyphicon-trash"></i>
						      					</button> 
						      				</td>
						      				
						      			</tr>	
					      			@endforeach
				      			@endif
				      			@for($i = 1; $i <= 1; $i++)
				      			<tr>
				      				<td class="num_pago" align="center">{{$i}}</td>

				      				<td>
				      					<input name="monto[]" placeholder="Monto en Digitos" class="form-control monto"  type="number" step="0.0000001" 
				      					>
				      				</td>

				      				<td>
				      					<input name="monto_l[]" placeholder="Monto en Letras" class="form-control" type="text" 
				      					>
				      				</td>

				      				<td>
				      					<input name="monto_producto[]" class="form-control" type="text"
				      					 >
				      				</td>
				      				
				      				<td> 
				      					<button class="btn btn_color btn_rojo remover_pago">
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
					<label class="control-label col-sm-2">Monto Total:</label>
					<div class="col-sm-2">
						<input type="number" step="0.0000001" class="form-control" required name="monto_total" placeholder="Pago en Dígitos ej:2200" 
						value='{{$edit?"$contrato->monto_total":""}}' id="monto_total">
					</div>
					<div class="col-sm-4">
						<input type="text" class="form-control" required name="monto_total_l" placeholder="Pago en Letras ej:Dos mil docientos"

						value='{{$edit?"$contrato->monto_total_l":""}}'>
					</div>
					<div class="col-sm-4">
						<button  
			           			class="calculo_monto btn btn-primary"
					           	data-toggle="tooltip" 
				           		title="Se calcularan los acumulados como tambien los totales"
				           		id="@if($user) {{$user->id}} @elseif($proveedor) {{$proveedor->id}} @else {{$proveedor_user->id}} @endif">
				           		Total <i class="fa fa-question-circle" ></i>
				        </button>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">Productos:</label>
					<div class="col-sm-10">
						<textarea class="form-control" required name="productos">{{$edit?"$contrato->productos":""}}</textarea>
						
					</div>
				</div>

				<div class="form-group">
				    <label class="control-label col-sm-2">Documento:</label>
				    
				    <div class="col-sm-6 ">
				    	
				      	<table class=" table table-bordered">

				      		<thead>
				      			<tr>				      				
				      				<th>Nombre del documento</th>
				      				<th>Documento ( cada documento no debe pesar mas de 10mb)</th>
				      				<th><button id="documentos" class="btn btn_color agregar_documento"><b>+</b></button></th>
				      			</tr>
				      		</thead>
				      		<tbody class="clone_documentos">
				      			<tr style="display: none;">		      				

				      				<td>
				      					<input name="nombre_documento[]"  class="form-control"  type="text" >
				      				</td>

				      				<td>
				      					<input name="file_documento[]" class="form-control" type="file" >
				      				</td>			      				
				      				
				      				<td> 
				      					<button class="btn btn_color btn_rojo remover_pago">
				      						<i class="glyphicon glyphicon-trash"></i>
				      					</button> 
				      				</td>
				      				
				      			</tr>
				      			@if($edit)
				      			@foreach($contrato->documentos as $documento)	
				      			<tr>
				      				<td></td>
				      				<td class="nombre_documento" style="vertical-align: middle;text-align: center;" >
				      					<label>{{$documento->nombre}}</label>
				      				</td>			      				

				      				<td style="width: 100px;"> 

				      					<a href='{{$documento->documento}}'
				      					 
				      					 class="btn btn_color btn_download"
				      					 style="background: #5cb85c;">
				      						<i class="glyphicon glyphicon-download-alt"></i>
				      					</a> 

				      					
										<a href='{{url("documento/delete/$documento->id")}}'  class="btn btn_color btn_rojo" 
											title="Eliminar documento  {{$documento->nombre}}"
											data-toggle="tooltip"
											data-placement="top"
											data-method="DELETE"
											data-confirm-title="Eliminar Documento "
											data-confirm-text="Esta seguro de querer eliminar este documento"
											data-confirm-delete="Borrar"
											>
											<i class="glyphicon glyphicon-trash"></i>
										</a>

				      				</td>
				      			</tr>
						    		@endforeach
						      	@endif
				      			<tr class="documentos_input_file" > 				

				      				<td>
				      					<input name="nombre_documento[]"  class="form-control nombre_documento"  type="text" >
				      				</td>

				      				<td>
				      					<input name="file_documento[]" class="form-control" type="file" >
				      				</td>			      				
				      				
				      				<td> 
				      					<button class="btn btn_color btn_rojo remover_pago">
				      						<i class="glyphicon glyphicon-trash"></i>
				      					</button> 
				      				</td>
				      				
				      			</tr>		
				      		</tbody>	      		
				      	</table>
				    </div>		    
				</div>
				
				<div class="form-group">
					<label class="control-label col-sm-4">Seleccione Director o Representante País para la firma del contrato:</label>
					<div class="col-sm-4">
						<label class="radio-inline">
							<input type="radio" class="radioelection" name="radiooption" value="directora" @if ($edit && $contrato->direct_id != null)
								checked								
							@endif> Directora 
						</label>
						<label class="radio-inline">
							<input type="radio" class="radioelection" name="radiooption" value="representante" @if ($edit && $contrato->representante_id != null)
								checked								
							@endif> Representante País
						</label>
					</div>
					<div class="col-sm-2">
						<label class="control-label" id="firmante">@if ($edit && $contrato->direct_id != null)
							{{$contrato->direct->first_name}} {{$contrato->direct->last_name}}
						@elseif ($edit && $contrato->representante_id != null)
							{{$contrato->representante->first_name}} {{$contrato->representante->last_name}}
						@endif</label>
						<input type="hidden" name="firmante_id" id="firmante_id" @if ($edit && $contrato->direct_id != null)
								value="{{$contrato->direct_id}}"
							@elseif ($edit && $contrato->representante_id != null)
								value="{{$contrato->representante_id}}"
							@else
								value=""
							@endif>
					</div>
				</div>

				@if($edit)
				<div class="form-group">
					<label class="control-label col-sm-2">Fecha de cumplimiento:</label>
					<div class="col-sm-3">
						<div class="input-group datepicker">
						    <input type="text" class="form-control"  name="cumplimiento" value='{{$edit?"$contrato->cumplimiento":""}}'>
						    <div class="input-group-addon">
						        <span class="glyphicon glyphicon-calendar"></span>
						    </div>
						</div>
					</div>
				</div>
				@endif					

				
					<!--<div class="form-group">
						<label class="control-label col-sm-2">Status Contrato:</label>
						<div class="col-sm-3">
							<select  class="form-control" name="status">
								<option value="0" {{($edit && $contrato->status==0)?"selected":""}}>Pendiente</option>		
								<option value="1" {{($edit && $contrato->status==1)?"selected":""}}>Aprobado</option>
								<option value="2" {{($edit && $contrato->status==2)?"selected":""}}>Rechazado</option>
								<option value="3" {{($edit && $contrato->status==3)?"selected":""}}>Terminado</option>
								<option value="4" {{($edit && $contrato->status==4)?"selected":""}}>Anulado</option>
								<option value="5" {{($edit && $contrato->status==5)?"selected":""}}>Vencido</option>
							</select>
						</div>
						<div class="col-sm-5">
							<b>Nota</b>:Luego de ser aprobado el contrato ya no se podra modificar la informacion del mismos
						</div>
					</div>-->
				


				<div class="form-group">				
					
					<div class="col-sm-12 text-right">
						<button class="btn btn_color btn_form btn_verde vista_previa_contrato" disabled> <i class="glyphicon glyphicon-eye-open"></i> Vista Previa</button>
						<button type="submit" class="btn btn_color btn_form" disabled><i class="glyphicon glyphicon-floppy-disk"></i> Guardar Informacion</button>
					</div>			
				</div>					

				
				<!--////////////////////////////////////////////////////////////////////////////////-->
				<div class="form-group">
					<label class="control-label col-sm-2 vista_previa" style="display: none;">Contrato Vista Previa:</label>				
					<div class="col-sm-8 formato_contrato " style="display: none;border: 1px solid #ccc;padding: 10px;">
						<div class="load_vista_contrato"><i class="glyphicon glyphicon-refresh"></i> Cargando...</div>
						<div >
							
							<div class="text-center">
								<img src="{{url('/img/logo-p1.png')}}"><br>
								OFICINA REGIONAL PARA AMERICA LATINA 	<br>
								@if($user) {{$user->oficina->oficina}} @elseif($proveedor) {{$proveedor->oficina->oficina}} @else {{$proveedor_user->oficina->oficina}} @endif							
								Telf: @if($user) {{$user->oficina->telf}} @elseif($proveedor) {{$proveedor->oficina->telf}} @else {{$proveedor_user->oficina->telf}} @endif<br> 				

								@if($user) {{$user->oficina->direccion}} @elseif($proveedor) {{$proveedor->direccion_fiscal}} @else {{$proveedor_user->oficina->direccion}} @endif	
								E-mail:america@weeffect.org		<br><br> 
								<b>CONTRATO DE PRESTACION DE SERVICIOS DE CONSULTORIA</b> 
								<br>
								<b>@if($user) {{$user->n_contrato}} @elseif($proveedor) {{$proveedor->nit}} @else {{$proveedor_user->n_contrato}} @endif</b><br><br>
								
							</div>
							<p>
									Entre nosotros, <b>We Effect </b> - Oficina Regional para América Latina
															
									, bajo el registro jurídico en
									 <b>@if($user) {{$user->oficina->pais->pais}} @elseif($proveedor) {{$proveedor->pais->pais}} @else {{$proveedor_user->oficina->pais->pais}} @endif NIT @if($user) {{$user->oficina->nit}} @elseif($proveedor) {{$proveedor->nit}} @else {{$proveedor_user->oficina->nit}} @endif</b>
									 , representada por  
									 <b>{{$directora->first_name}} {{$directora->last_name}}</b>  
									 quien es Directora Regional y ejerce la Dirección Regional y 
									 <b>@if($user) {{$user->first_name}} {{$user->last_name}} @elseif($proveedor) {{$proveedor->nombre}} @else {{$proveedor_user->first_name}} {{$proveedor_user->last_name}} @endif</b>   
									 con documento 
									 <b>@if($user) {{$user->tipo_documento->tipo_documento}} @elseif($proveedor) NIT @else {{$proveedor_user->tipo_documento->tipo_documento}} @endif @if($user) {{$user->documento}} @elseif($proveedor) {{$proveedor->nit}} @else {{$proveedor_user->documento}} @endif</b>
									 , en su condición de 
									 @if($user)
									 	{{$user->sexo ? 'consultor':'consultora'}}
									 @else
									 	proveedor 
									 @endif, convienen en celebrar el presente contrato de servicios de Consultoría, sujeto a las siguientes cláusulas
								</p>						

							<p><b>1.	OBJETIVO</b></p>
							<p class="input_objetivo"></p>

							<p><b>2.	ALCANCE DEL CONTRATO</b></p>
							<p class="input_alcance"></p>

							<p>
								
								2.1 @if($user)
									{{$user->sexo ? 'La consultora':'El consultor'}}
									@else
									El Proveedor
									@endif conviene en prestar sus servicios a We Effect, cuyas responsabilidades, productos y otras características específicas se detallan en los términos de referencia que se adjuntan y que forman parte integral de este contrato.
							</p>
							<p>

								2.2 We Effect, a través de su Dirección Regional para América Latina, tendrá el derecho de coordinar y dar seguimiento en todo tiempo a las actividades, servicios y productos objeto de este Contrato, y dar a  @if($user)
									{{$user->sexo ? 'La consultora':'El consultor'}}
									@else
									El Proveedor
									@endif por escrito las instrucciones que estime pertinentes relacionadas con su ejecución, a fin de que se ajusten al programa y términos de referencia correspondientes, así como a las modificaciones que en su caso se dispongan. 
							</p>
							<p>
								
								2.3 We Effect dará por recibidos los productos o servicios objeto de este Contrato si los mismos hubieren sido realizados de acuerdo con los términos de referencia, programa de trabajo y demás estipulaciones convenidas.  
							</p>
							<p>

								2.4 @if($user)
									{{$user->sexo ? 'La consultora':'El consultor'}}
									@else
									El Proveedor
									@endif será la única persona responsable por la ejecución de los servicios y actividades contratadas cuando no se ajusten a este Contrato y a las instrucciones dadas por escrito por We Effect. Cuando las actividades no se hubieren ejecutado de acuerdo a este Contrato y sus anexos y con las instrucciones por escrito de We Effect, éste dispondrá su corrección o reposición inmediata por parte de @if($user)
									{{$user->sexo ?'La consultora':'El consultor'}}
									@else
									El Proveedor
									@endif  que no tendrá derecho a ninguna retribución por los trabajos mal ejecutados
							</p> 

							<p><b>3. ACTIVIDADES A REALIZAR</b></p>
							<p class="input_actividades"></p>

							<p><b>4. METODOLOGIA DE TRABAJO</b></p>
							<p class="input_metodologia"></p>

							<p><b>5. PERIODO DE CONTRATACION</b></p>
							<p>
								@if($user)
									{{$user->sexo ?'La consultora':'El consultor'}}
									@else
									El Proveedor
									@endif se obliga a iniciar las actividades objeto de este Contrato a partir del <b class="input_fecha_inicio"></b> hasta <b class="input_fecha_fin"></b>
							</p>

							<p><b>6. HONORARIOS, OTROS COSTOS Y FORMAS DE PAGO</b></p>
							<p>
								
							</p>

							<p>6.1 Los honorarios convenidos corresponden a 
								@if($user) {{$user->oficina->pais->moneda_simbolo}} @elseif($proveedor) {{$proveedor->pais->moneda_simbolo}} @else {{$proveedor_user->oficina->pais->moneda_simbolo}} @endif <label class="input_pago_letras"></label>, pagaderos de la siguiente forma: </p>
							
							<p class="input_cantidad_pagos">
								
							</p>
							

							<p>6.2 Productos de la consultoría</p>

							<p>@if($user)
									<b>{{$user->sexo ? 'La consultora':'El consultor'}}</b>
									@else
									<b>El Proveedor</b>
									@endif   facturará a nombre de: <b>@if($user) We Effect @elseif($proveedor) {{$proveedor->razon_social}} @else We Effect @endif @if($user) {{$user->oficina->nit}} @elseif($proveedor) {{$proveedor->nit}} @else {{$proveedor_user->oficina->nit}} @endif</b>  En ningún caso We Effect asumirá costos adicionales a los establecidos en este Contrato.</p>


							<p><b>7. RELACION CONTRACTUAL TEMPORAL</b></p>
							 <p>
							 	7.1 El presente Contrato no implica ninguna relación obrero patronal entre We Effect y @if($user)
									<b>{{$user->sexo ?'La consultora':'El consultor'}}</b>
									@else
									<b>El Proveedor</b>
									@endif  , limitándose al período de actividades descritas. Las coberturas de cargas sociales y demás serán realizadas por   @if($user)
									<b>{{$user->sexo ?'La consultora':'El consultor'}}</b>
									@else
									<b>El Proveedor</b>
									@endif
							 </p>
							 <p> 
								7.2 En virtud de que las causas que han dado origen a este Contrato de Prestación de Servicios son extraordinarias y transitorias, ambas partes convienen en que al término del plazo estipulado, este Contrato quedará terminado automáticamente, sin necesidad de previo aviso ni ningún otro requisito y que debido a su naturaleza, no implica ningún tipo de relación laboral con @if($user)
									<b>{{$user->sexo ?'La consultora':'El consultor'}}</b>
									@else
									<b>El Proveedor</b>
									@endif  , y por ende, exime a We Effect de cualquier responsabilidad derivada de las disposiciones legales y demás ordenamientos en materia de trabajo y de seguridad social. 
							 </p>
							<p>

								7.3 @if($user)
									<b>{{$user->sexo ?'La consultora':'El consultor'}}</b>
									@else
									<b>El Proveedor</b>
									@endif  se compromete a seguir la política anti-corrupción que destaca la prohibición explícitamente a su personal y a consultores/as financiados bajo este contrato que, para sí mismos o para otros, acepte o le sea prometido, pida o dé, prometa u ofrezca soborno o recompensa, remuneración, compensación, ventajas indebidas o beneficios, de cualquier tipo, que puedan constituir un comportamiento ilegal o inapropiado 
							</p>
							<p>
								<b>8. SUSPENSION, PRORROGA Y TERMINACION DEL CONTRATO</b>
							</p>
							<p>

								8.1 We Effect tiene la facultad de suspender temporal o definitivamente los trabajos objeto de este Contrato por causas de fuerza mayor o circunstancias imprevistas, en cualquier estado en que éstos se encuentren, dando aviso por escrito a @if($user)
									<b>{{$user->sexo ?'La consultora':'El consultor'}}</b>
									@else
									<b>El Proveedor</b>
									@endif  con una anticipación de ocho días. Cuando la suspensión sea temporal, We Effect informará a @if($user)
									<b>{{$user->sexo ?'La consultora':'El consultor'}}</b>
									@else
									<b>El Proveedor</b>
									@endif   sobre su duración aproximada y se modificará el plazo estipulado en la misma proporción. Cuando la suspensión sea total y definitiva, se dará por terminado el Contrato. Cuando We Effect ordene la suspensión definitiva y total por causa no imputable a @if($user)
									<b>{{$user->sexo ?'La consultora':'El consultor'}}</b>
									@else
									<b>El Proveedor</b>
									@endif  , pagará a ésta por los servicios prestados hasta la fecha de suspensión. 
							</p>
							<p>
								8.2 Cuando no fuere posible a las partes cumplir las obligaciones contraídas en virtud de este Contrato debido a causas de fuerza mayor debidamente comprobadas, éste se podrá dar por terminado por cualquiera de ellas previa notificación inmediata y por escrita a la otra. @if($user)
									<b>{{$user->sexo ?'La consultora':'El consultor'}}</b>
									@else
									<b>El Proveedor</b>
									@endif  deberá comprometerse a entregar a We Effect todo el trabajo avanzado y percibirá únicamente la suma que corresponde al resultado, obra, tarea o servicio realizado. 
							</p>
							<p>

								8.3 Confidencialidad: @if($user)
									<b>{{$user->sexo ?'La consultora':'El consultor'}}</b>
									@else
									<b>El Proveedor</b>
									@endif , se compromete a cumplir en todo momento con la confidencialidad de los documentos, archivos y políticas, incluyendo todos los datos electrónicos de We Effect / SCC, los cuales no pueden ser divulgados a ninguna persona o personas ajenas a la organización. En caso de incumplimiento con la presente cláusula, se da por terminado inmediatamente dicho contrato. Todo producto del trabajo del encargado de @if($user)
									<b>{{$user->sexo ?'La consultora':'El consultor'}}</b>
									@else
									<b>El Proveedor</b>
									@endif  en/con We Effect /SCC es propiedad institucional; y We Effect/ SCC se reserva el derecho de uso del mismo. 
							</p>
							<p>

								8.4 La ejecución tardía del contrato acarreará una multa para @if($user)
									<b>{{$user->sexo ?'La consultora':'El consultor'}}</b>
									@else
									<b>El Proveedor</b>
									@endif  . We Effect tendrán el derecho a pronunciarse en forma escrita ante LA CONSULTORA acerca del incumplimiento de la fecha de entrega del servicio/producto, de la tardía o pérdida de tiempo trascurrido. La sanción económica imputable al contratado será de un 1% del monto total del contrato por cada día hábil de acuerdo al plazo contractual. Dicha multa se hará efectiva del importe del saldo del pago pendiente. Esta cláusula sancionatoria no podrá exceder el 25% del monto total del contrato. Así mismo, superado éste monto, We Effect podrá resolver el contrato y exigir además el cumplimiento de la obligación contraída en los términos pactados, según corresponda. Si la demora se produjere por causas no imputables a @if($user)
									<b>{{$user->sexo ?'La consultora':'El consultor'}}</b>
									@else
									<b>El Proveedor</b>
									@endif  deberá hacerlo por escrito a We Effect justificando las causas del atraso. Una vez que We Effect analice la situación, y si ésta corresponda, We Effect autorizará la prórroga del plazo de entrega final. 
							</p>
							<p>

								8.5 We Effect podrá rescindir administrativamente el presente Contrato en los casos siguientes: <br>
								<ol>
									<li type="a">
										Cuando @if($user)
									<b>{{$user->sexo ?'La consultora':'El consultor'}}</b>
									@else
									<b>El Proveedor</b>
									@endif no inicie los trabajos de este Contrato en la fecha que le indique We Effect.
									</li>
									<li type="a">
										 Cuando @if($user)
									<b>{{$user->sexo ?'La consultora':'El consultor'}}</b>
									@else
									<b>El Proveedor</b>
									@endif  no cumpla con cualquiera de las obligaciones derivadas del presente Contrato, el programa de trabajo o los términos de referencia, o sin motivo justificado no acata las instrucciones dadas por escrito por We Effect. 
									</li>							
								</ol>
							</p>

							<p style="text-transform: uppercase;margin-top: 40px;">								
								LEIDO ESTE DOCUMENTO LAS PARTES MANIFIESTAN SU CONFORMIDAD CON TODAS Y CADA UNA DE LAS CLAUSULAS Y PARA CONSTANCIA FIRMAN EN LA PAIS
							</p>              
							<table style="width: 100%;">
								<tr>
									<th>p/ WE EFFECT</th>
									<th>p/ NOMBRE @if($user)
										{{$user->sexo ?'CONSULTORA':'CONSULTOR'}}
										@else
											PROVEEDOR
										@endif
									</th>
								</tr>

								<tr>
									{{--<td>{{$directora->first_name}} {{$directora->last_name}}</td>--}}
									<td id="nombre_firmante">@if ($edit && $contrato->direct_id != null) {{$contrato->direct->first_name}} {{$contrato->direct->last_name}} @elseif ($edit && $contrato->representante_id != null) {{$contrato->representante->first_name}} {{$contrato->representante->last_name}} @endif</td>
									<td>@if($user) {{$user->first_name}} {{$user->last_name}} @elseif($proveedor) {{$proveedor->nombre}} @else {{$proveedor_user->first_name}} {{$proveedor_user->last_name}} @endif</td>
									
								</tr>

								<tr>
									{{--<td>{{$directora->cargo->cargo}} </td>--}}
									<td id="cargo_firmante">@if ($edit && $contrato->direct_id != null) {{$contrato->direct->cargo->cargo}} @elseif ($edit && $contrato->representante_id != null) {{$contrato->representante->cargo->cargo}} @endif</td>
									<td>@if($user) {{$user->documento}} @elseif($proveedor) {{$proveedor->nit}} @else {{$proveedor_user->documento}} @endif </td>
								</tr>
							</table>
           
 



							
							
							
						</div>
					</div>
				</div>
			</div>
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

    {{--@if(isset($view))	
    <div class="row" style="margin-bottom: 50px;">
	    <div class="col-lg-12">
	        <h3 class="page-header">
	            Lista de Adendas 
	         @if(isset($view) && Entrust::hasRole('Coordinadora') )
        	<button id="{{$contrato->id}}" class="btn btn_color crear_adenda" data-toggle="modal" data-target="#myModal">
        		Crear adenda
        	</button>
        	
        	@endif                
	        </h3>
	       
	        <table class="table table-bordered dataTables-adenda">
	        	<thead>                            
	        		<tr>
	        			<th>Fecha de Creación</th>
	        			<th>Fecha de Finalización antes -> ahora </th>
	        			<th>Fecha de Cumplimiento</th>                                
	        			<th>Motivo</th>
	        			<th></th>                              
	        		</tr>
	        	</thead> 
	        	<tbody>
	        	@foreach($contrato->adendas as $adenda)

	        	<tr>
	    			<td  align='center' valign='middle'>$adenda->created_at</td>    			
	    			<td>{{$adenda->fecha_contrato}}  {{$adenda->fecha_contrato_nueva}}</td>
	    			<td>{{$adenda->fecha_cumplimineto}} -> {{$adenda->fecha_cumplimiento_nueva}} </td>    			
	    			<td>{{$adenda->motivo}}</td> 
	    			<td>
	    				<a href='{{url("/adenda/delete/$adenda->id")}}'  class='btn btn_color btn_rojo' 
	                        title='Eliminar Adenda '
	                        data-toggle='tooltip'
	                        data-placement='top'
	                        data-method='DELETE'
	                        data-confirm-title='Eliminar adenda '
	                        data-confirm-text='La fecha de finalizacion se cambiara a la fecha que tenia antes o a la adendas mas reciente'
	                        data-confirm-delete='Borrar'
	                        >
	                        <i class='glyphicon glyphicon-trash'></i>
	                    </a>
	                </td>   			
	    		</tr>

	        	@endforeach
	        		
	        		

	        	</tbody>  
	        </table>

	    </div>
	</div>

	<div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>        
                    <h4 class="modal-title">Crear Adenda</h4>                            
                </div>
                <div class="modal-body">
                    
                    <form class="form-horizontal modal_form_adenda" action="{{url('/adenda/create')}}" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" class="modal_input_contrato_id" name="contrato_id" value="{{$contrato->id}}" >
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Consultor:</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control modal_input_nombre" disabled>
                                    </div>
                                   
                                </div>
                            </div>

                            <div class="col-xs-12">                                
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Actual fecha de finlaizacion </label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control modal_input_fecha" disabled>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Actual fecha de cumplimiento </label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control modal_input_cumplimiento" disabled value="$contrato->cumplimiento">
                                    </div>
                                    <div class="col-sm-4">
                                         El status del contrato y Las aprobaciones de <b>Directora</b> y <b>Coordinadora</b> volveran a el status:
                                        <label class="label label-warning label_status "><i class="fa fa-check"></i> Pendiente</label>
                                        ya que con esta adenda se modificara la fecha de finlaizacion del contrato
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Nueva fecha de finlaizacion</label>
                                    <div class="col-sm-4 ">
                                        <div class="input-group datepicker">
	                                        <input type="text" class="form-control" name="fecha_contrato_nueva">
	                                        <div class="input-group-addon">
	                                            <span class="glyphicon glyphicon-calendar"></span>
	                                        </div>
	                                    </div>
	                                </div>                                   
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Nueva fecha de cumplimiento</label>
                                    <div class="col-sm-4 ">
                                        <div class="input-group datepicker">
                                        <input type="text" class="form-control" name="fecha_cumplimiento_nueva">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Motivo de adenda:</label>
                                    <div class="col-sm-9">
                                        <textarea required type="text" class="form-control" name="motivo"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 text-right">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn_color">Crear Adenda</button>
                            </div> 
                        </div>
                    </form>                   
                </div>
                
            </div>
        </div>
    </div>
	@endif --}} 
     
@stop



@section('scripts')

{!! HTML::script('assets/js/moment.min.js') !!}
{!! HTML::script('assets/js/moment_es.js') !!}

{!! HTML::script('assets/js/bootstrap-datepicker.min.js') !!}
{!! HTML::script('assets/js/bootstrap-datepicker.es.min.js') !!}
{!! HTML::script('assets/js/jquery-ajax-dowload.js') !!}


<script type="text/javascript">

	$('.dataTables-adenda').DataTable({

        "info": false,
        "pageLength": 15,
       
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }                    
        }
    });
	jQuery(document).ready(function($) {
		count_pagos();
		@if($view)
			$('.form_contrato').find('input,select,textarea').attr('disabled', true);
			$('.agregar_pago,.remover_pago,.agregar_documento,.btn_download,.btn_form').remove();
			$('.formato_contrato').closest('.form-group').remove();
		@endif

		@if(!$view && $edit && $contrato->status>0)
			form=$('.form_contrato > .row');
			form.find('input,select,textarea').attr('disabled', true);
			form.find('select').attr('disalbed', true);
			$('input[name="cumplimiento"],input[name="file_documento[]"],input[name="nombre_documento[]"],select[name="aprobacion_coordinadora"],select[name="aprobacion_directora"]').attr('disabled',false);

		@endif
	////////////////////////////////////////////////////////////////////
		$('.input-daterange input,.datepicker input').datepicker({	    
		    language:'es',	    
			format: 'yyyy-mm-dd',			
		});
	/////////////////////////////////////////////////////////////////////
		$(document).on('click', '.agregar_pago,.agregar_documento', function(event) {
			event.preventDefault();

			id=$(this).attr('id');
			clone=$('.clone_'+id+' tr:first').clone(true);			
			clone.show().find('input').val('');
			$('.clone_'+id).append(clone);
			/*$('.pagos_contrato').append(
				'<tr  id="pagos_clone" >'+
					'<td class="num_pago" valign="middle" align="center"></td>'+
					'<td><input class="form-control" type="" name=""></td>'+
					'<td><input class="form-control" type="" name=""></td>'+
					'<td>'+ 
						'<button class="btn btn_color btn_rojo">'+
					'		<i class="glyphicon glyphicon-trash remover_pago"></i>'+
					'	</button> '+
					'</td>'+
				'</tr>');*/
				count_pagos();			
		});
	/////////////////////////////////////////////////////////////////////
		$(document).on('click', '.remover_pago', function(event) {
				
			$(this).closest('tr').remove();				
		});
	/////////////////////////////////////////////////////////////////////
		function count_pagos(argument) {
				num_pagos=0;
				$('.num_pago').each(function(index, el) {

					$(this).text(num_pagos);
					num_pagos++;
				});
		}
	/////////////////////////////////////////////////////////////////////
		$(document).on('click', '.calculo_monto', function(event) {
			event.preventDefault();
			sumar();
		});
	////////////////////////////////////////////////////////////////////
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
		}
	/////////////////////////////////////////////////////////////////////
		$(document).on('change', '.radioelection', function (event) {
			switch($('.radioelection:checked').val()) {
				case 'directora': 
					$('#firmante').html("{{$directora->first_name}} {{$directora->last_name}}").text();
					$('#nombre_firmante').html("{{$directora->first_name}} {{$directora->last_name}}").text();
					$('#cargo_firmante').html("{{$directora->cargo->cargo}}").text();
					$('#firmante_id').val({{$directora->id}});
					break;
				case 'representante': 
					$('#firmante').html("{{$representantePais->first_name}} {{$representantePais->last_name}}").text();
					$('#nombre_firmante').html("{{$representantePais->first_name}} {{$representantePais->last_name}}").text();
					$('#cargo_firmante').html("{{$representantePais->cargo->cargo}}").text();
					$('#firmante_id').val({{$representantePais->id}});
					break;
			}
		});
	/////////////////////////////////////////////////////////////////////
		$(document).on('click', '.vista_previa_contrato', function(event) {
			event.preventDefault();
			/* Act on the event */
			moneda_simbolo="@if($user) {{$user->oficina->pais->moneda_simbolo}} @elseif($proveedor) {{$proveedor->oficina->pais->moneda_simbolo}} @else {{$proveedor_user->oficina->pais->moneda_simbolo}} @endif";

			$('.input_objetivo').text($('textarea[name="objetivo"]').val());
			$('.input_alcance').text($('textarea[name="alcance"]').val());
			$('.input_actividades').text($('textarea[name="actividades"]').val());
			$('.input_metodologia').text($('textarea[name="metodologia"]').val());
			$('.input_fecha_inicio').text($('input[name="fecha_inicio"]').val());
			$('.input_fecha_fin').text($('input[name="fecha_fin"]').val());
			$('.input_actividades').text($('textarea[name="actividades"]').val());
			$('.input_n_contrato').text($('input[name="n_contrato"]').val());

			$('.input_pago_letras').text($('input[name="monto_total"]').val());
			$('.input_pago_num').text($('input[name="monto_total"]').val());

			cantidad_pagos="";
			$('.pagos_contrato tr').each(function(index, el) {
					
					num_pago=$(this).find('.num_pago').text()
					monto=$(this).find('input[name="monto[]"]').val()
					monto_l=$(this).find('input[name="monto_l[]"]').val()
					producto=$(this).find('input[name="monto_producto[]"]').val()
					if (monto!="") {
						cantidad_pagos+="<b>PAGO "+ num_pago+":</b> "+moneda_simbolo+monto+" ("+monto_l+"),  en concepto de "+producto+"<br>";
					}				

			});
			$('.input_cantidad_pagos').html(cantidad_pagos);
			$('.formato_contrato').show();
			$('label.vista_previa').show();
			$('.load_vista_contrato').fadeIn();
			$('.load_vista_contrato').fadeOut();
			$('html,body').animate({
        		scrollTop: $(".formato_contrato").offset().top},
        	'slow');
		});
	/////////////////////////////////////////////////////////////////////
		$(window).load(function() {
	    	$('.btn_form').removeAttr('disabled');
	    	count_pagos();
	    });
	/////////////////////////////////////////////////////////////////////
	    $(document).on('submit', '.form_contrato', function(event) {	
			event.preventDefault();	
			//calculo_totales_inputs();		
			$('.formato_contrato ').fadeOut();
			$(this)
			.find(':input[type=submit]')
		    .attr('disabled', true)
		    .html('<i class="glyphicon glyphicon-refresh"></i> Guardando Informacion')
		    .css('color', '#000')
		    ;
		    this.submit();		    
		});	
	/////////////////////////////////////////////////////////////////////
		$(document).on('click', '.btn_download', function(event) {
			event.preventDefault();
			documento=$(this).attr('href');
			nombre_documento=$(this).closest('tr').find('.nombre_documento label').text();
			nombre=$('input[name="nombre"]').val();
			$(this)
			.attr('disabled', true)
			.html('<i class="glyphicon glyphicon-refresh"></i>');
			
			$.ajax({
			  dataType: 'native',
			  url: "{{url('/documentos')}}/"+documento,
			  xhrFields: {
			    responseType: 'blob'
			  },
			  success: function(blob){		    
			      var link=document.createElement('a');
			      link.href=window.URL.createObjectURL(blob);
			      link.download =nombre_documento+'-'+documento;	      
			      link.click();		      
			  }
			});
			$(this)
			.attr('disabled', false)
			.html('<i class="glyphicon glyphicon-download-alt"></i>');	 	
		});
	/////////////////////////////////////////////////////////////////////
		@if($view || $edit)
			
		        event.preventDefault();         
		        
		        $('.modal_input_nombre').val($('input[name="nombre"]').val());

		        $('.modal_input_cumplimiento').val('{{$contrato->cumplimiento}}'); 
		        
		        $('.modal_input_fecha').val('{{$contrato->fecha_fin}}'); 
		        $('.modal .datepicker input').datepicker('remove');
		        $('.modal .datepicker input').datepicker({ 
		            language:'es',      
		            format: 'yyyy-mm-dd',             
		            startDate: '{{$contrato->fecha_fin}}',
		        });           
	    	
    	@endif
    	$(document).on('change', ' input[type="file"]', function(event) {
			
			imagen=	(this.files[0].size/1024)/1024;	
			if ((imagen)>2) {
				$('#modal_size_file').modal('show');	
				$('.informacion').text('Las Archivos deben pesar menos de 10 Mb , esta archivo pesa '+imagen.toFixed(2)+' Mb' );		
				$(this).val('');				
			}
		});
		$(document).on('change','input[name="fecha_inicio"] ,input[name="fecha_fin"]', function(event) {
			$('.input_fecha_inicio').text($('input[name="fecha_inicio"]').val());
			$('.input_fecha_fin').text($('input[name="fecha_fin"]').val());
		});
	});//ready
</script>
@stop
@section('styles')
    {!! HTML::style('assets/css/bootstrap-datepicker.min.css') !!}
    {!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop

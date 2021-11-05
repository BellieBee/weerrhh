@extends('layouts.app')

@section('page-title', 'Reportes')

@section('content')

	<h1 class="page-header">Generar Reporte</h1>

	@include('partials.messages')
	
	<ul class="nav nav-tabs">		
		<li class="active"><a data-toggle="tab" href="#planilla">Reportes</a></li>
		<li><a data-toggle="tab" href="#boleta_empleados">Boleta de Empleado</a></li>
		<li ><a data-toggle="tab" href="#liquidacion">Boleta de Liquidacion</a></li>	
		<li ><a data-toggle="tab" href="#contratos">Contratos</a></li>
		<li><a data-toggle="tab" href="#seguro_social">Seguro Social</a></li>
		<li><a data-toggle="tab" href="#dias_vacaciones">Días Vacaciones</a></li>
		<li><a data-toggle="tab" href="#colegas">Colegas</a></li>
		<li><a data-toggle="tab" href="#compras">Compras</a></li>
	</ul>
	<div class="tab-content">
		<div id="planilla" class="tab-pane fade in active  ">
			<form class="form_reportes form-horizontal text-left" target="_blank" action="{{url('/reporte/planillas')}}" method="post">
				{!! csrf_field() !!}
				
				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;">Tipo de reporte:</label>	
					<div class="col-sm-8" style="margin-left: 15px;">

						<label class="radio-inline">
							<input type="radio" class="radio_tipo radio_empleados" checked name="tipo" value="empleados"> Empleados
						</label>
						<label class="radio-inline">
							<input type="radio" class="radio_tipo radio_consolidados" name="tipo" value="consolidados"> Consolidados
						</label>
						<label class="radio-inline">
							<input type="radio" class="radio_tipo radio_vacaciones" name="tipo" value="vacaciones"> Vacaciones
						</label>
						<label class="radio-inline">
							<input type="radio" class="radio_tipo radio_permisos" name="tipo" value="permisos"> Permisos
						</label>
						<label class="radio-inline">
							<input type="radio" class="radio_tipo radio_viajes" name="tipo" value="viajes"> Viajes de trabajo
						</label>
						<label class="radio-inline">
							<input type="radio" class="radio_tipo radio_bono_salud" name="tipo" value="bono_salud"> Actividad de Salud
						</label>															
						<label class="radio-inline">
							<input type="radio" class="radio_tipo radio_integrador" name="tipo" value="integrador"> Integrador
						</label>															
					</div>
				</div>			
				
				<div class="form-group checbox_aprobados">
					<label class="col-sm-2 control-label" style="font-size: 15px;">Solo Aprobadas</label>	
					<div class="col-sm-8" style="margin-left: 15px;">						
						<label>
							<input class="confirmada" type="checkbox" name="confirmada">								
						</label>										
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;">Formato del documento:</label>	
					<div class="col-sm-8" style="margin-left: 15px;">

						<label class="radio-inline">
							<input type="radio" class="radio_doc" checked name="tipo_doc" value="pdf"> PDF
						</label>
						<label class="radio-inline">
							<input type="radio" class="radio_doc" name="tipo_doc" value="excel"> Excel
						</label>															
					</div>
				</div>	

				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;">Rango de fechas:</label>					
					<div class="col-sm-8">
						<div class="col-sm-2">
							<input class="fechas_todas" id="#fechas_todas_planilla" type="checkbox"  name="fecha_todas">Todos los meses
						</div>
						<div class="col-sm-5">
							<div class="input-group input-daterange" id="fechas_todas_planilla">

								<input type="text" id="min-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="De:" name="fecha_inicio">

								<div class="input-group-addon">to</div>

								<input type="text" id="max-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="Hasta:" name="fecha_fin">
							</div>
						</div>
					</div>				
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;">Oficinas:</label>
					<div class="col-sm-8">


						@foreach($oficinas as $oficina)
						<div class="col-sm-4">
							<label>
								<input class="oficina" id="#oficina_{{$oficina->id}}" type="checkbox" name="oficinas[]" value="{{$oficina->id}}">
								{{$oficina->oficina}}
							</label>
						</div>
						@endforeach       	

					</div>

				</div>

				<div class="form-group div_empleados">					

					<div class="col-sm-2 text-right">
						<label class="control-label" style="font-size: 15px;">Empleados por oficina:</label>
						<button class="todos">Marcar Todos</button>					
					</div>

					<div class="col-sm-8 ">			

						@foreach($oficinas as $oficina)					

						<div class="col-sm-12" id="oficina_{{$oficina->id}}" 
							style="display: none;"
							>
							<h4 class="page-header" style="margin-top: 30px;">{{$oficina->oficina}}</h4>
							@foreach($oficina->user as $user)
							<div class="col-sm-4">
								<label>
									<input type="checkbox"
									class="empleados_inputs"
									name="empleados_oficinas[]"
									{{$oficina==auth()->user()->oficina?'checked':''}}
									value="{{$user->id}}">
									{{$user->first_name}} {{--({{$user->roles->first()->name}})--}}
								</label>
							</div>
							@endforeach
						</div>       					          	 
						@endforeach
					</div>			
				</div>
				<div class="form-group">
					<div class="col-sm-12 text-right">
						<label class="mensaje" style="color: red;"></label>
						<button type="submit"  disabled class="btn btn_color btn_rojo">GENERAR REPORTE</button>
					</div>
				</div>					
			</form>
		</div>
		
		
		<div id="boleta_empleados" class="tab-pane fade ">
			<form class="form-horizontal text-left" target="_blank" action="{{url('/reporte/empleado')}}" method="post">
				{!! csrf_field() !!}
				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;"> Empleados </label>	
					<div class="col-sm-5" >
						<select class="form-control" required name="user">
							<option disabled selected></option>
							@foreach($oficinas as $oficina)								
								<optgroup label="{{$oficina->oficina}}">
									<option value="todos,{{$oficina->id}}">Todos los empleados</option>
									@foreach($oficina->user as $user)
									<option value="{{$user->id}},{{$oficina->id}}">
										{{$user->first_name}} {{$user->last_name}}
										{{--({{$user->roles->first()->name}})--}}
									</option>
									@endforeach
								</optgroup>
							
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;"> Año Y Mes </label>	
					<div class="col-sm-2" >
						<select class="form-control" required name="mes" >
					       	<option >Enero</option>
					       	<option >Febrero</option>
					       	<option >Marzo</option>
					       	<option >Abril</option>
					       	<option >Mayo</option>
					       	<option >Junio</option>
					       	<option >Julio</option>
					       	<option >Agosto</option>
					       	<option >Septiembre</option>
					       	<option >Octubre</option>
					       	<option >Noviembre</option>
					       	<option >Diciembre</option>					       	
					    </select>
			    	</div>
			    	<div class="col-sm-2" >
						<input class="form-control" required type="number" name="año" value="{{date('Y')}}">
			    	</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;">Formato del documento:</label>	
					<div class="col-sm-8" style="margin-left: 15px;">

						<label class="radio-inline">
							<input type="radio" class="radio_doc" checked name="tipo_doc" value="pdf"> PDF
						</label>
						<label class="radio-inline">
							<input type="radio" class="radio_doc" name="tipo_doc" value="excel"> Excel
						</label>															
					</div>
				</div>				
				<div class="form-group">
					<div class="col-sm-12 text-right">
						
						<button type="submit"  disabled class="btn btn_color btn_rojo">GENERAR BOLETA DE EMPLEADO</button>
					</div>
				</div>					
			</form>
		</div>

		<div id="seguro_social" class="tab-pane fade ">
			<form class="form-horizontal text-left" target="_blank" action="{{url('/seguro_social/planilla')}}" method="post">
				{!! csrf_field() !!}
				
				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;"> Año Y Mes </label>	
					<div class="col-sm-2" >
						<select class="form-control" required name="mes" >
					       	<option >Enero</option>
					       	<option >Febrero</option>
					       	<option >Marzo</option>
					       	<option >Abril</option>
					       	<option >Mayo</option>
					       	<option >Junio</option>
					       	<option >Julio</option>
					       	<option >Agosto</option>
					       	<option >Septiembre</option>
					       	<option >Octubre</option>
					       	<option >Noviembre</option>
					       	<option >Diciembre</option>					       	
					    </select>
			    	</div>
			    	<div class="col-sm-2" >
						<input class="form-control" required type="number" name="year" value="{{date('Y')}}">
			    	</div>
				</div>	
				@if(Entrust::can('ver-reporte-todos') || Entrust::can('ver-reportes-oficina'))
					<div class="form-group">
						<label class="col-sm-2 control-label" style="font-size: 15px;">Pais</label>	
						<div class="col-sm-4">
							<select name="pais" class="form-control" require>
								@foreach($paises as $pais)
									<option value="{{$pais->id}}">{{ $pais->pais }}</option>
								@endforeach
							</select>
						</div>
					</div>
				@endif			
				<div class="form-group">
					<div class="col-sm-12 text-right">
						
						<button type="submit"  disabled class="btn btn_color btn_rojo">GENERAR REPORTE PARA SEGURO SOCIAL</button>
					</div>
				</div>					
			</form>
		</div>
		
		<div id="liquidacion" class="tab-pane fade">
			<form class="form-horizontal text-left" target="_blank" action="{{url('/reporte/liquidacion')}}" method="post">
				

				{!! csrf_field() !!}
				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;"> Empleados </label>	
					<div class="col-sm-5" >
						<select class="form-control liquidacion" required name="user">
							<option disabled selected></option>
							@foreach($oficinas as $oficina)								
								<optgroup label="{{$oficina->oficina}}">									
									@foreach($oficina->user->where('status',1) as $user)
									<option value="{{$user->id}},{{$oficina->id}}">
										{{$user->first_name}} {{$user->last_name}}
										 {{--( {{$user->roles->first()->name}} )--}}
									</option>
									@endforeach
								</optgroup>
							
							@endforeach
						</select>
					</div>
				</div>	
				<div class="form-group liquidacion_formato" style="display: none;">
					<label class="col-sm-2  control-label" style="font-size: 15px;"> Formato : </label>	
					<div class="col-sm-10 col-sm-offset-1 " style="border: 1px solid black;" >
						<div class="load_ajax_liquidacion"><i class="glyphicon glyphicon-refresh"> </i> Carngando...</div>
						<div align="right">{{date("Y-m-d H:i:s")}}</div>
						<div style="text-align: center;margin: 30px; text-transform: uppercase;">
							<b>FINIQUITO LABORAL</b>
						</div>
						
						<div><b>1.	Hago constar expresamente que:</b></div><br>
						<table style="margin-left: 30px;">
							<tr>
								<td style="vertical-align: top;">1.1</td>
								<td style="padding-bottom: 20px;">
									Sostuve relación de servicios con la entidad <b>WE EFFECT</b> a partir del 
									<label class="ajax_fecha_ingreso"></label>, 
									por virtud de la cual presté a aquella los servicios: (i) del ,
									<label class="ajax_fecha_ingreso"></label>
									al
									<label class="ajax_fecha_finalizacion"></label>								
									
									como <b class="ajax_cargo" style="text-transform: uppercase;">REPRESENTANTE PAÍS</b>, el cual me era pagado mensualmente mediante un recibo emitido a su nombre.
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top;">1.2</td>
								<td style="padding-bottom: 20px;">
									Dicha relación de servicios terminará  el 
									<label class="ajax_fecha_finalizacion"></label>
									tal y como fue pactado por ambas partes en la cláusula cuarta del Contrato Individual de Trabajo por Tiempo Definido No. CD 009-2017 en adelante el “Contrato”.
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top;">1.3</td>
								<td style="padding-bottom: 20px;">
									Que <b>WE EFFECT</b> me hará efectivo mediante cheque el día 
									<label class="ajax_fecha_finalizacion"></label> , el pago de 

									<input style="display: inline-block;width:auto;" 
									type="text"  
									class="form-control pago_cheque " 
									name="pago_cheque" required 
									>  correspondiente a los siguientes rubros:
								</td>
							</tr>
						</table>

						<table class="pago " style="border :1px solid black;">
							<tr>
								<td style="width: 80%">Pago de salario correspondiente al mes de 
									<label class="ajax_planilla_mes"></label>
								</td>
								<td class="ajax_planilla_salario"></td>
							</tr>
							<tr>
								<td >Pago correspondiente al bono 13avo 
									(<label class="ajax_fecha_ingreso"></label> al <label class="ajax_fecha_finalizacion"></label>)</td>
								<td class="ajax_catorceavo"></td>
							</tr>

							<tr class="ajax_pension_tr">
								<td >Pago de pensión beneficio que aplica en su política de personal de We Effect por un porcentaje de <label class="ajax_porcentaje_pension"></label> mensual sobre los salarios del (
									<label class="ajax_fecha_ingreso"></label> al <label class="ajax_fecha_finalizacion"></label>)</td>
								<td class="ajax_pension"></td>
							</tr>
							<tr>
								<td><input type="text" name="extra_label[]" class="input_table form-control" style="width: 80%"></td>
								<td><label class="cambio_moneda"></label><input type="text" name="extra_valor[]" class="input_table form-control"></td>
							</tr>
							<tr>
								<td><input type="text" name="extra_label[]" class="input_table form-control" style="width: 80%"></td>
								<td><label class="cambio_moneda"></label><input type="text" name="extra_valor[]" class="input_table form-control"></td>
							</tr>
							<tr>
								<td><input type="text" name="extra_label[]" class="input_table form-control" style="width: 80%"></td>
								<td><label class="cambio_moneda"></label><input type="text" name="extra_valor[]" class="input_table form-control"></td>
							</tr>
							
							<tr>
								<td> <br></td>
								<td> <br></td>
							</tr>
							<tr>
								<td align="right"><b>SUB-TOTAL</b></td>
								<td><input type="text sub_total" name="sub_total" required class="input_table form-control"></td></td>
							</tr>
							<tr>
								<td style="border:none"> <br></td>
								<td style="border:none"> <br></td>
							</tr>
							<tr>
								<td style="width: 80%"><b>(-) DEDUCCIONES</b></td>
								<td></td>
							</tr>
							<tr>
								<td>Cargas impositivas: Deduccion total </td>
								<td ><b class="ajax_total_deducciones"></b></td>
							</tr>
							<tr>
								<td> <br></td>
								<td> <br></td>
							</tr>
							<tr>
								<td align="right"><b>SUB-TOTAL</b></td>
								<td><b class="ajax_sub_total_deducciones"></b></td>
							</tr>
							<tr>
								<td style="border:none"> <br></td>
								<td style="border:none"> <br></td>
							</tr>
							<tr>
								<td><b>TOTAL A ENTREGAR EN ESTA LIQUIDACION</b></td>
								<td><input type="text pago_liquidacion" name="pago_liquidacion" required class="input_table form-control"></td>
							</tr>
							<tr>
								<td style="border:none"> <br></td>
								<td style="border:none"> <br></td>
							</tr>
							
						</table>	
						<p>Una vez enterado el monto antes relacionado, quedará por bien saldado y por recibido a mi entera satisfacción el pago que me corresponde por la terminación del Contrato.</p>
						<div ><b>2.	Hago constar expresamente que:</b></div><br>
						<table style="margin-left: 30px;page-break-after:always;">
							<tr>
								<td style="vertical-align: top;">2.1</td>
								<td style="padding-bottom: 20px;">
									<b>WE EFFECT</b> no me adeuda suma alguna en concepto de honorarios, gastos, indemnización por daños y perjuicios, reembolsos por gastos incurridos y autorizados por la entidad, pago correspondiente al reembolso por montos de jubilación según el Contrato o cualquier otra obligación ni prestación que se derive directa o indirectamente del contrato antes indicado, pues todas las obligaciones a que fui acreedor me fueron pagadas.
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top;">2.2</td>
								<td style="padding-bottom: 20px;">
									<b>WE EFFECT</b> no me tiene ninguna obligación pendiente ni de carácter civil, ni mercantil, ni de ninguna otra índole, ni en concepto de indemnización por tiempo servido, compensación o cualquier otra prestación o definición que por su fondo sea equivalente, similar o análoga, ni por salarios ordinarios y/o extraordinarios, ni por aguinaldo, bonificaciones anuales, vacaciones, ventajas, ni por séptimos días, ni por incentivos o bonificaciones incentivos, ni por cualquier otro concepto, ni por reajustes por ningún concepto, ya que no se derivaron del contrato de servicios relacionado en el numeral anterior, obligaciones laborales de ningún tipo.
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top;">2.3</td>
								<td style="padding-bottom: 20px;">
									El precio pagado mensualmente por <b>WE EFFECT</b> en concepto de salario, retribuyeron los servicios prestados a dicha entidad.
								</td>
							</tr>
						</table>

						<div><b>3.	Por el presente acto me obligo a:</b></div><br>
						<table style="margin-left: 30px;">
							<tr>
								<td style="vertical-align: top;">3.1</td>
								<td style="padding-bottom: 20px;">
									<p>Mantener en estricta reserva y confidencialidad toda la información que me fue proporcionada o que tuve conocimiento por cualquier medio a través de la prestación de mis servicios profesionales, comprometiéndome a no darla a conocer, ni total ni parcialmente a ninguna persona, ni a utilizarla para beneficio personal o de intereses de terceros.</p>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top;">3.2</td>
								<td style="padding-bottom: 20px;">
									<p>No divulgar a terceros, directa o indirectamente, el contenido del presente documento, ni ninguno de los términos y condiciones establecidos en el mismo, así como lo que fue conversado con los funcionarios de <b>WE EFFECT</b> al momento de suscribir dicho documento.</p>

									<p>Por el presente acto acepto expresamente que el incumplimiento de lo establecido en el numeral 3 del presente documento, podré ser responsable civil y penalmente de los daños y perjuicios que pudiera ocasionar.</p>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top;">3.3</td>
								<td style="padding-bottom: 20px;">
									<p>Hacer entrega del equipo, información y cualquier otro bien que We Effectle haya proporcionado durante el desarrollo del trabajo.</p>
								</td>
							</tr>
						</table>
						
						<p>Y, en vista de la presente manifestación, apruebo y remato la totalidad de mis cuentas y otorgo la más completa y eficaz carta de pago y el finiquito más absoluto, obligándome por pacto expreso de no pedir, toda vez que no tengo reclamo de ningún género que hacer a <b>WE EFFECT</b>.</p>

						<label class="ajax_fecha_finalizacion"></label>.<br><br><br><br><br><br>

						<center>
							_____________________________________<br>
							{{$user->first_name}} {{$user->last_name}} <br>
							Documento de Identidad {{$user->docmento}}
						</center>
					</div>
				</div>								
				<div class="form-group">
					<div class="col-sm-12 text-right">
						
						<button type="submit"  disabled class="btn btn_color btn_rojo">GENERAR BOLETA DE LIQUIDACION</button>
					</div>
				</div>					
			</form>
		</div>
		<div id="contratos" class="tab-pane fade">
			
			<form class="form_contratos form-horizontal text-left" target="_blank" action="{{url('/reporte/contratos')}}" method="post">
				{!! csrf_field() !!}			

				<div class="form-group">
					<label class="control-label col-sm-2">Status de Contratos:</label>
					<div class="col-sm-3">
						<select  class="form-control" name="status">
							<option value="todos">Todos</option>						
							<option value="0">Pendientes</option>						
							<option value="1">Aprobados</option>
							<option value="2">Rechazados</option>
							<option value="3">Terminados</option>
							<option value="4">Anulados</option>
							<option value="5">Vencidos</option>
						</select>
					</div>
				</div>			
				
				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;">	Fecha de Contratacion </label>					
					<div class="col-sm-8">
						<div class="col-sm-2">
							<input class="fechas_todas" id="#fechas_todas_contrato" type="checkbox"  name="fecha_todas">Todos los meses
						</div>
						<div class="col-sm-5">
							<div class="input-group input-daterange" id="fechas_todas_contrato">

								<input type="text" id="min-date" class="form-control date-range-filter" placeholder="De:" name="fecha_inicio">

								<div class="input-group-addon">to</div>

								<input type="text" id="max-date" class="form-control date-range-filter" placeholder="Hasta:" name="fecha_fin">
							</div>
						</div>
					</div>				
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;">Oficinas:</label>
					<div class="col-sm-8">


						@foreach($oficinas as $oficina)
						<div class="col-sm-4">
							<label>
								<input class="oficina" id="#contratos_oficina_{{$oficina->id}}" type="checkbox" name="oficinas[]" value="{{$oficina->id}}">
								{{$oficina->oficina}}
							</label>
						</div>
						@endforeach       	

					</div>

				</div>

				<div class="form-group div_empleados">					

					<div class="col-sm-2 text-right">
						<label class="control-label" style="font-size: 15px;">Empleados por oficina:</label>
						<button class="todos">Marcar Todos</button>					
					</div>

					<div class="col-sm-8 ">			

						@foreach($oficinas as $oficina)					

						<div class="col-sm-12" id="contratos_oficina_{{$oficina->id}}" 
							style="display: none;"
							>
							<h4 class="page-header" style="margin-top: 30px;">{{$oficina->oficina}}</h4>
							@foreach($oficina->user as $user)
							<div class="col-sm-4">
								<label>
									<input type="checkbox"
									class="empleados_inputs"
									name="empleados_oficinas[]"
									{{$oficina==auth()->user()->oficina?'checked':''}}
									value="{{$user->id}}">
									{{$user->first_name}} {{--({{$user->roles->first()->name}})--}}
								</label>
							</div>
							@endforeach
						</div>       					          	 
						@endforeach
					</div>			
				</div>
				<div class="form-group">
					<div class="col-sm-12 text-right">
						<label class="mensaje" style="color: red;"></label>
						<button type="submit"  disabled class="btn btn_color btn_rojo">GENERAR REPORTE</button>
					</div>
				</div>					
			</form>
		</div>

		<!-- Días Vacaciones reporte -->

		<div id="dias_vacaciones" class="tab-pane fade">
			
			<form class="form-horizontal" target="_blank" action="{{url('/reporte/dias_vacaciones')}}" method="post">
			{!! csrf_field() !!}
			
				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;">Oficinas:</label>
					<div class="col-sm-4">
						<select name="oficina" class="form-control">
							@foreach($oficinas as $oficina)
								<option value="{{$oficina->id}}">{{$oficina->oficina}}</option>
							@endforeach
						</select>       	

					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12 text-right">
						<label class="mensaje" style="color: red;"></label>
						<button type="submit"  disabled class="btn btn_color btn_rojo">GENERAR REPORTE</button>
					</div>
				</div>
			</form>
		</div>

		<!-- Colegas reporte -->

		<div id="colegas" class="tab-pane fade">
			
			<form class="form-horizontal" target="_blank" action="{{url('/reporte/colegas')}}" method="post">
			{!! csrf_field() !!}
			
				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;">Oficinas:</label>
					<div class="col-sm-4">
						<select name="oficina" class="form-control">
							@foreach($oficinas as $oficina)
								<option value="{{$oficina->id}}">{{$oficina->oficina}}</option>
							@endforeach
						</select>       	

					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12 text-right">
						<label class="mensaje" style="color: red;"></label>
						<button type="submit"  disabled class="btn btn_color btn_rojo">GENERAR REPORTE</button>
					</div>
				</div>
			</form>
		</div>

		<!-- Compras Reporte -->
		
		<div id="compras" class="tab-pane fade">
			<form class="form_compras form-horizontal text-left" target="_blank" action="{{url('/reporte/compras')}}" method="post">
				{!! csrf_field() !!}
				
				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;">Tipo de reporte de compras:</label>	
					<div class="col-sm-8" style="margin-left: 15px;">

						<label class="radio-inline">
							<input type="radio" class="radio_tipo radio_actividades" checked name="tipo" value="actividades"> Actividades
						</label>
						<label class="radio-inline">
							<input type="radio" class="radio_tipo radio_decisiones" name="tipo" value="decisiones"> Decisiones
						</label>
						<label class="radio-inline">
							<input type="radio" class="radio_tipo radio_ordenescompra" name="tipo" value="ordenescompra"> Órdenes de Compra
						</label>
						<label class="radio-inline">
							<input type="radio" class="radio_tipo radio_pagos" name="tipo" value="pagos"> Pagos
						</label>														
					</div>
				</div>			
				
				{{--<div class="form-group checbox_aprobados">
					<label class="col-sm-2 control-label" style="font-size: 15px;">Solo Aprobadas</label>	
					<div class="col-sm-8" style="margin-left: 15px;">						
						<label>
							<input class="confirmada" type="checkbox" name="confirmada">								
						</label>										
					</div>
				</div>--}}

				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;">Rango de fechas:</label>					
					<div class="col-sm-8">
						<div class="col-sm-2">
							<input class="fechas_todas" id="#fechas_todas_compra" type="checkbox"  name="fecha_todas">Todos los meses
						</div>
						<div class="col-sm-5">
							<div class="input-group input-daterange" id="fechas_todas_compra">

								<input type="text" id="min-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="De:" name="fecha_inicio">

								<div class="input-group-addon">to</div>

								<input type="text" id="max-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="Hasta:" name="fecha_fin">
							</div>
						</div>
					</div>				
				</div>

				<div class="form-group">
					<label class="col-sm-2 control-label" style="font-size: 15px;">Oficinas:</label>
					<div class="col-sm-8">
						@foreach($oficinas as $oficina)
						<div class="col-sm-4">
							<label>
								<input class="oficinaCompra" id="#oficinaCompra_{{$oficina->id}}" type="checkbox" name="oficinas[]" value="{{$oficina->id}}">
								{{$oficina->oficina}}
							</label>
						</div>
						@endforeach       	
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12 text-right">
						<label class="mensaje" style="color: red;"></label>
						<button type="submit"  disabled class="btn btn_color btn_rojo">GENERAR REPORTE</button>
					</div>
				</div>					
			</form>
		</div>

	</div>

@endsection

@section('scripts')
{!! HTML::script('assets/js/moment.min.js') !!}
{!! HTML::script('assets/js/moment_es.js') !!}

{!! HTML::script('assets/js/bootstrap-datepicker.min.js') !!}
{!! HTML::script('assets/js/bootstrap-datepicker.es.min.js') !!}	
<script>

	$('#planilla .input-daterange input').datepicker({	    
	    language:'es',	    
		format: 'yyyy-mm',
		startView: "months", 
    	minViewMode: "months"
	});
	$('#contratos .input-daterange input').datepicker({	    
	    language:'es',	    
		format: 'yyyy-mm-dd',		
	});
	$('#compras .input-daterange input').datepicker({	    
	    language:'es',	    
		format: 'yyyy-mm-dd',		
	});

	
	$(document).on('change', '.radio_tipo', function(event) {

			
		switch($('.radio_tipo:checked').val()) {		    
		    
		    case 'consolidados':
		    	$('.div_empleados').hide();	        
		        break;
		    default:		    
				
				$('.div_empleados').show();
		    
		}

		
	});
	
	$(document).on('change', 'input.oficina', function(event) {
		event.preventDefault();
		input=$(this).is(':checked');
		empleado_oficina=$(this).attr('id');
		
		if (!$('.radio_consolidados').is(':checked')) {

			$('.div_empleados').show();
			
		}else{
			$('.div_empleados').hide();
		}
			
		if (input) {
			$(empleado_oficina).show();
			$(empleado_oficina+" input").attr('disabled', false);

		}else{
			$(empleado_oficina).hide();
			$(empleado_oficina+" input").attr('disabled', true);
		}
		
		console.log(input);
	});
	
	$(document).on('click', 'button.todos', function(event) {
		event.preventDefault();	
		empleados=$(this).closest('.div_empleados').find('.empleados_inputs');
		
		empleados.prop('checked', true);
		/*$('.empleados_inputs:enabled').each(function(){
     		 $(this).prop('checked', true);
  		});*/		
	});
	
	$(document).on('change', 'input.fechas_todas', function(event) {
		event.preventDefault();		
		id=$(this).attr('id');
		console.log(id);
		input=$(this).is(':checked');
		if (!input) {
			$(id ).show();
			$(id +" input").attr('disabled', false);

		}else{
			$(id ).hide();
			$(id +" input").attr('disabled', true);


		}		
	});
	
	$(document).on('submit', '.form_reportes,.form_contratos,.form_compras', function(event) {

		event.preventDefault();
		
		if ($(this).hasClass('form_reportes')) {
			
			form=$('.form_reportes')
			
			switch($('.radio_tipo:checked').val()) {
				
			    case 'empleados':
					$(this).attr('action', "{{url('/reporte/planillas')}}");   
				break;

			    case 'consolidados':
			    	$(this).attr('action', "{{url('/reporte/planillas')}}");    
			    break;
			
			    case 'vacaciones':
					$(this).attr('action', "{{url('/reporte/vacaciones_permisos')}}");
				break;

			    case 'permisos':
			    	$(this).attr('action', "{{url('/reporte/vacaciones_permisos')}}");
			    break;

				case 'viajes':
					$(this).attr('action', "{{url('/reporte/vacaciones_permisos')}}");
				break;

				case 'bono_salud':
					$(this).attr('action', "{{url('/reporte/vacaciones_permisos')}}");
				break;				

				case 'integrador':
					$(this).attr('action', "{{url('/reporte/calendar/descargar')}}");
				break
			    
			}
		}
		else if ($(this).hasClass('form_compras')) {
			
			form=$('.form_compras')

			switch($('.radio_tipo:checked').val()) {

				case 'actividades':
					$(this).attr('action', "{{url('/reporte/compras')}}");
				break;

				case 'decisiones':
					$(this).attr('action', "{{url('/reporte/compras')}}");
				break;

				case 'ordenescompra':
					$(this).attr('action', "{{url('/reporte/compras')}}");
				break;

				case 'pagos':
					$(this).attr('action', "{{url('/reporte/compras')}}");
				break
			}
		} 
		else{
			form=$('.form_contratos')
		}

		oficina=form.find('input.oficina:checked').size();
		oficinaCompra=form.find('input.oficinaCompra:checked').size();
		fecha_inicio=form.find('input[name="fecha_inicio"]').val();
		fecha_fin=form.find('input[name="fecha_fin"]').val();
		check_fecha_todos=form.find('.fechas_todas').is(':checked');
		console.log(fecha_inicio+fecha_fin);
		
		if (oficina == 0 && form == $('.form_reportes') || oficinaCompra == 0 && form == $('.form_compras')) {
			form.find('.mensaje').text('Debe elegir una oficina ');
			
		}else if(!check_fecha_todos && fecha_inicio=="" && fecha_fin==""){

			form.find('.mensaje').text('Debe elegir las fechas ');									

		}else{

			this.submit();

			
		}
		setTimeout(input_color, 2000);	
			
			function input_color(argument) {					
			
				form.find('.mensaje').text('');
			
			};
		
		//
	});
	
	

	$('.ajax_fecha').datepicker({	    
	    language:'es',
	    format: 'yyyy-mm-dd',		
	});

	$(document).on('change', 'select.liquidacion', function(event) {
		event.preventDefault();
		$('.liquidacion_formato').show();
		$('.load_ajax_liquidacion').show();

		$('.pago_cheque, .pago_liquidacion, .sub_total').val('');

		id=$(this).val();
		console.log($(this).val())
		$.ajax({
			url: "{{url('/reporte/ajax/liquidacion')}}",			
			dataType: 'json',
			data: {id: id},
		})
		.done(function(data) {
			moneda=data.pais.moneda_simbolo
			$('.ajax_fecha_inicio').text(data.user.fecha_inicio);
			$('.ajax_cargo').text(data.user.cargo.cargo);

			$('.ajax_planilla_mes').text(data.planilla.m_a);			

			$('.ajax_planilla_salario').text(moneda+" "+data.empleado.liquido_recibir);

			$('.ajax_catorceavo').text(moneda+" "+data.catorceavo);
			$('.ajax_porcentaje_pension').text(moneda+" "+data.pais.porcentaje_pension+"%");
			$('.ajax_total_deducciones,.ajax_sub_total_deducciones').text(moneda+" "+data.total_deducciones);
			$('.ajax_fecha_ingreso').text(data.user.fecha_ingreso);
			$('.ajax_fecha_finalizacion').text(data.user.fecha_finalizacion);
			$('.cambio_moneda').text(moneda)

			$('input[name="planilla_mes"]').val(data.planilla.m_a);
			$('input[name="moneda_simbolo"]').val(moneda);
			$('input[name="porcentaje_pension"]').val(data.pais.porcentaje_pension);
			$('input[name="liquido"]').val(data.empleado.liquido_recibir);
			$('input[name="pago_pension"]').val(data.pais.pago_pension);
			$('input[name="total_deducciones"]').val(data.total_deducciones);

			if (data.pais.pago_pension=="anual") {
				$('.ajax_pension_tr').hide();
			}else{
				$('.ajax_pension_tr').show();
				$('.ajax_pension').text(moneda+" "+data.pension)
			}

			//console.log(data);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function(data) {
			console.log(data);
			$('.load_ajax_liquidacion').hide();
		});


		
	});
	
	$(window).load(function() {
    		$('button[type="submit"]').removeAttr('disabled'); 
    });

</script>
			@stop
@section('styles')
<style type="text/css">
	.form-group{
		margin-bottom: 35px;
	}
</style>
    {!! HTML::style('assets/css/bootstrap-datepicker.min.css') !!}
    {!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop
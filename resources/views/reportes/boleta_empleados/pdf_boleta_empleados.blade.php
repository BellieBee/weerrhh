
<body  >
	@foreach($empleados as $empleado)
		<div style="page-break-after:always;" >
			<img src="img/logo-p1.png" >
			<div style="text-align: center;margin-top: 30px;margin-bottom: 30px;"><b>RECIBO SALARIO </b></div>
			
			<div style="display: inline-block; width: 47%;font-size: 12px;" >
				<b>Organizacion: </b>WE EFFECT<br>
				<b>Domicilio: </b> {{$planilla->oficina->direccion}} <br>
				<b>Identif. Tributaria: </b> {{$planilla->oficina->nit}} <br>	
				<b>Codigo Patronal: </b> {{$planilla->oficina->num_patronal}} 	
			</div>

			<div style="display: inline-block; width: 47%;font-size: 12px;" >
				<b>Nombre: </b>{{$empleado->user->first_name}} {{$empleado->user->last_name}}<br>
				<b>Identif. Tributaria: </b> {{$empleado->user->n_identificacion_tributaria}} <br>
				<b>Afiliacion al Seguro Social: </b>{{$empleado->user->n_afiliacion}}<br>
				<b>Cargo: </b>{{$empleado->user->cargo->cargo}}	
			</div>	

			<div style="text-align: center;margin: 30px; text-transform: uppercase;">
				<b>Pago Salarial correspondiente al mes de {{$m_a}} </b>
			</div>
			<table class="tabla_1">
				<tr>
					<td class="titulo" colspan="2"><b>I. SALARIO DEVENGADO:</b></td>			
					
				</tr>					

				<tr>
					<td>Salario mensual Devengado:</td>				
					<td align="right">{{$empleado->salario_base}}</td>
				</tr>	
				
				
				<tr>
					<td>Ajuste Salarial </td>				
					<td align="right">{{$empleado->ajuste}}</td>
				</tr>

				<tr>					
					<td align="right"><b>A. TOTAL DEVENGADO:</b></td>					
					<td class="total">{{$empleado->total_salario}}</td>
				</tr>


				<!--__________________Deducciones_____________ -->
				
				<tr>
					<td class="titulo" colspan="2"><b>II. DEDUCCIONES:</b></td>			
					
				</tr>
				@if($empleado->user->oficina->pais->id==2)<!--//BOLIVIA-->									
					<tr>
						<td>CTA. IND. 10%</td>
						<td align="right">{{$empleado->deduccion->cta_ind}}</td>	
					</tr>
					<tr>
						<td>RIESGO 1,71%</td>
						<td align="right">{{$empleado->deduccion->riesgo}}</td>	
					</tr>
					<tr>
						<td>COM. AFP 0,5%</td>
						<td align="right">{{$empleado->deduccion->com_afp}}</td>
					</tr>
					<tr>
						<td>AFP APORTE SOLIDARIO 0,5%</td>
						<td align="right">{{$empleado->deduccion->afp_aporte_solidario}}</td>	
					</tr>
					<tr>
						<td>AFP APORTE NACIONAL SOLIDARIO 1%</td>
						<td align="right">{{$empleado->deduccion->afp_aporte_nacional_solidario}}</td>
					</tr>
					<tr>
						<td>RC - IVA 13%: </td>
						<td align="right">{{$empleado->deduccion->rc_iva}}</td>
					</tr>

				@elseif($empleado->user->oficina->pais->id==3)<!--//NICARAGUA-->	
					<tr>
						<td>Deduccion 1</td>
						<td align="right">{{number_format($empleado->deduccion->deduccion_1,2)}}</td>
					</tr>
					<tr>
						<td>Deduccion 2</td>
						<td align="right">{{number_format($empleado->deduccion->deduccion_2,2)}}</td>
					</tr>

				@elseif($empleado->user->oficina->pais->id==4)<!--//HONDURAS-->
					<tr>
						<td>Seguro_medico</td>
						<td align="right">{{number_format($empleado->deduccion->seguro_medico,2)}}</td>
					</tr>
					<tr>
						<td>RAP (1.5%)</td>
						<td align="right">{{number_format($empleado->deduccion->rap,2)}}</td>
					</tr>

				@elseif($empleado->user->oficina->pais->id==5)<!--//PARAGUAY-->					


				@elseif($empleado->user->oficina->pais->id==6)<!--//SALVADOR-->					
					<tr>
						<td>AFP ({{7.25}}%)</td>
						<td align="right">{{number_format($empleado->deduccion->afp,2)}}</td>	

					</tr>

				@elseif($empleado->user->oficina->pais->id==7)<!--//COLOMBIA-->
					<tr>
						<td>Salud 4%</td>
						<td align="right">{{number_format($empleado->deduccion->salud,2)}}</td>	

					</tr>
					<tr>
						<td>FSP 1%</td>
						<td align="right">{{number_format($empleado->deduccion->fsp,2)}}</td>	

					</tr>
					<tr>
						<td>Pensión 4%</td>
						<td align="right">{{number_format($empleado->deduccion->pen,2)}}</td>	

					</tr>
					<tr>
						<td>RTE FTW UVT</td>
						<td align="right">{{number_format($empleado->deduccion->rte,2)}}</td>	

					</tr>
					<tr>
						<td>Otros descuentos celulares</td>
						<td align="right">{{number_format($empleado->deduccion->otros_descuentos_celulares,2)}}</td>	

					</tr>
				@endif

				@if( str_contains($pais->campo_deducciones, 'seguridad_social'))
					<tr>
						<td align="right">{{$pais->campo->seguridad_social}}</td>
						<td align="right">{{$empleado->deduccion->seguridad_social}}</td>
					</tr>	
				@endif

				@if( str_contains($pais->campo_deducciones, 'impuesto_renta'))
					<tr>
						<td align="right">{{$pais->campo->impuestos}}</td>
						<td align="right">{{$empleado->deduccion->impuesto_renta}}</td>
					</tr>
				@endif

				@if( str_contains($pais->campo_deducciones, 'prestamo'))
					<tr>
						<td align="right">{{$pais->campo->prestamo}}</td>
						<td align="right">{{$empleado->deduccion->prestamo}}</td>
					</tr>
				@endif

				@if( str_contains($pais->campo_deducciones, 'interes'))
					<tr>
						<td align="right">{{$pais->campo->interes}}</td>
						<td align="right">{{$empleado->deduccion->interes}}</td>
					</tr>
				@endif

				@if( str_contains($pais->campo_deducciones, 'otras_deducciones'))
					<tr>
						<td align="right">{{$pais->campo->otras_deducciones}}</td>
						<td align="right">{{$empleado->deduccion->otras_deducciones}}</td>
					</tr>	
				@endif				

				<tr>					
					<td align="right"><b>B. TOTAL DEDUCCIONES</b></td>
					<td class="total">{{$empleado->deduccion->total_deducciones}}</td>
				</tr>
				<!--APORTES-->

				@if ($empleado->aporte->total_aportes != 0.00)
					<tr>
						<td class="titulo" colspan="2"><b>III. APORTES:</b></td>			
					</tr>
					@if($empleado->user->oficina->pais->id==1) <!--GUATEMALA -->
						<tr>
							<td>Bonificacion incentivo</td>
							<td align="right">{{$empleado->aporte->bonificacion_incentivo}}</td>	
						</tr>
						<tr>
							<td>Bonificación Docto 37 2001</td>
							<td align="right">{{$empleado->aporte->bonificacion_docto_37_2001}}</td>
						</tr>
						<tr>
							<td>Reintegros</td>			
							<td align="right">{{$empleado->aporte->reintegros}}</td>
						</tr>
						{{--<tr>
							<td>Bonificación 14 (Dto 42-92)({{$planilla->catorceavo_mes}})</td>						
							<td align="right">{{$empleado->aporte->bonificacion_14}}</td>
						</tr>--}}
						{{--@if(str_contains($planilla->m_a, 'Diciembre'))
							<tr>
								<td>Fondo de Pensión</td>
								<td align="right">{{$empleado->total_pension}}</td>
							</tr>
							<tr>
								<td>Aguinaldo</td>
								<td align="right">{{$empleado->total_aguinaldo}}</td>
							</tr>
						@endif--}}
					@else
						@if($empleado->user->oficina->pais->id==7) <!--COLOMBIA -->
							<tr>
								<td>Auxilio de Transporte</td>
								<td align="right">{{$empleado->aporte->auxilio_transporte}}</td>	
							</tr>
							<tr>
								<td>Bono Salud</td>
								<td align="right">{{$empleado->aporte->bono_salud}}</td>
							</tr>
							<tr>
								<td>Capacitación</td>			
								<td align="right">{{$empleado->aporte->capacitacion}}</td>
							</tr>
							<tr>
								<td>Seguro Gastos Médicos</td>						
								<td align="right">{{$empleado->aporte->seguro_medico}}</td>
							</tr>
							<tr>
								<td>INCAP/Licencia</td>						
								<td align="right">{{$empleado->aporte->incap_licencia}}</td>
							</tr>
							<tr>
								<td>Total Incapacidad</td>						
								<td align="right">{{$empleado->aporte->total_incapacidad}}</td>
							</tr>
							<tr>
								<td>Incapacidades</td>						
								<td align="right">{{$empleado->aporte->incapacidades}}</td>
							</tr>
							<tr>
								<td>Aux. Trans</td>						
								<td align="right">{{$empleado->aporte->aux_trans}}</td>
							</tr>
							<tr>
								<td>Aux. Celular</td>						
								<td align="right">{{$empleado->aporte->aux_celular}}</td>
							</tr>
							<tr>
								<td>Medios Transp</td>						
								<td align="right">{{$empleado->aporte->medios_transp}}</td>
							</tr>
							<tr>
								<td>Otros Devengos</td>						
								<td align="right">{{$empleado->aporte->otros_devengos}}</td>
							</tr>
						@endif
						{{--@if(str_contains($planilla->m_a, 'Junio'))
							<tr>
								<td>Bonificación 14 (Dto 42-92)(Junio)</td>				
								<td align="right">{{$empleado->aporte->bonificacion_14}}</td>
							</tr>
						@endif--}}
						{{--@if(str_contains($planilla->m_a, 'Diciembre'))
							<tr>
								<td>Fondo de Pensión</td>
								<td align="right">{{$empleado->total_pension}}</td>
							</tr>
							<tr>
								<td>Aguinaldo</td>
								<td align="right">{{$empleado->total_aguinaldo}}</td>
							</tr>
						@endif--}}
					@endif
					<tr>					
						<td align="right"><b>C. TOTAL APORTES:</b></td>					
						<td class="total">
							@if(str_contains($planilla->m_a, $bono_14))
								{{number_format((float)$empleado->aporte->total_aportes - (float)$empleado->aporte->bonificacion_14, 2, '.', '')}}
							@elseif(str_contains($planilla->m_a, 'Diciembre'))
								{{number_format((float)$empleado->aporte->total_aportes - ((float)$empleado->total_pension + (float)$empleado->total_aguinaldo), 2, '.', '')}}
							@else
								{{number_format((float)$empleado->aporte->total_aportes, 2, '.', '')}}
							@endif
						</td>
					</tr>
				@endif

				<tr>
					<td colspan="2" style="padding-bottom: 40px;"></td>
				</tr>
				<tr class="liquido">					
					<td align="right"><b>LIQUIDO TOTAL A PERCIBIR:</b></td>
					<td class="total" style="border-bottom: double;">
						@if(str_contains($planilla->m_a, $bono_14))
							{{number_format((float)$empleado->liquido_recibir - (float)$empleado->aporte->bonificacion_14, 2, '.', '')}}
						@elseif(str_contains($planilla->m_a, 'Diciembre'))
							{{number_format((float)$empleado->liquido_recibir - ((float)$empleado->total_pension + (float)$empleado->total_aguinaldo), 2, '.', '')}}
						@else
							{{number_format((float)$empleado->liquido_recibir, 2, '.', '')}}
						@endif
					</td>
				</tr>
				<tr style="border:none;">
					<td colspan="2" align="center" style="border:none;padding:22px;font-size:14px;">{{$fecha_hoy}}</td>
				</tr>				
			</table>

			<table  style="width: 100% ;margin-top:30px;">
				<tr >
					<td  width="60%" align="right"><b>RECIBIDO</b></td>
					<td style="border: 1px solid black;padding: 20px 0px"></td>
				</tr>
				<tr >
					<td ></td>
					<td align="center" style="font-size: 12px;">Firma</td>
				</tr>
			</table>
		</div>

		{{--RECIBO DE BONO CATORCEAVO--}}

		@if(str_contains($planilla->m_a, $bono_14))
			<div style="page-break-after:always;" >
				<img src="img/logo-p1.png" >
				<div style="text-align: center;margin-top: 30px;margin-bottom: 30px;"><b>RECIBO BONO CATORCEAVO </b></div>
				
				<div style="display: inline-block; width: 47%;font-size: 12px;" >
					<b>Organizacion: </b>WE EFFECT<br>
					<b>Domicilio: </b> {{$planilla->oficina->direccion}} <br>
					<b>Identif. Tributaria: </b> {{$planilla->oficina->nit}} <br>	
					<b>Codigo Patronal: </b> {{$planilla->oficina->num_patronal}} 	
				</div>

				<div style="display: inline-block; width: 47%;font-size: 12px;" >
					<b>Nombre: </b>{{$empleado->user->first_name}} {{$empleado->user->last_name}}<br>
					<b>Identif. Tributaria: </b> {{$empleado->user->n_identificacion_tributaria}} <br>
					<b>Afiliacion al Seguro Social: </b>{{$empleado->user->n_afiliacion}}<br>
					<b>Cargo: </b>{{$empleado->user->cargo->cargo}}	
				</div>	

				<div style="text-align: center;margin: 30px; text-transform: uppercase;">
					<b>
						@if($pais->id == 1)
							LEY DE BONIFICACION ANUAL PARA LOS TRABAJADORES (DECRETO 42-92)
						@else
							Pago del Bono Catorceavo en el mes de {{$m_a}}
						@endif
					</b>
				</div>
				<table class="tabla_1">
					<tr>
						<td class="titulo" colspan="2"><b>I. SALARIO DEVENGADO:</b></td>			
						
					</tr>					

					<tr>
						<td>Salario mensual Devengado:</td>				
						<td align="right">{{$empleado->salario_base}}</td>
					</tr>	
					
					
					<tr>
						<td>Ajuste Salarial </td>				
						<td align="right">{{$empleado->ajuste}}</td>
					</tr>

					<tr>					
						<td align="right"><b>A. TOTAL DEVENGADO:</b></td>					
						<td class="total">{{$empleado->total_salario}}</td>
					</tr>

					<tr>
						<td class="titulo" colspan="2"><b>II. MESES ACUMULADO:</b></td>
					</tr>
					@foreach($meses_catorceavo as $mes)
						<tr>
							<td>{{$mes}}:</td>
							{{--@php dd($empleado->user->acumulado) @endphp--}}
							<td align="right">{{$empleado->user->acumulado->where('m_a', $mes)->first() != null ? $empleado->user->acumulado->where('m_a', $mes)->first()->catorceavo : '0.00'}}</td>
						</tr>
					@endforeach
					
					<tr>					
						<td align="right"><b>C. TOTAL BONO CATORCEAVO:</b></td>				
						<td class="total">{{number_format($empleado->aporte->bonificacion_14, 2, '.', '')}}</td>
					</tr>

					{{--<tr>
						<td colspan="2" style="padding-bottom: 40px;"></td>
					</tr>
					<tr class="liquido">					
						<td align="right"><b>LIQUIDO TOTAL A PERCIBIR:</b></td>
						<td  class="total" style="border-bottom: double;">{{number_format((float)$empleado->total_salario + (float)$empleado->aporte->bonificacion_14, 2, '.', '')}}</td>
					</tr>--}}
					<tr style="border:none;">
						<td colspan="2" align="center" style="border:none;padding:22px;font-size:14px;">{{$fecha_hoy}}</td>
					</tr>				
				</table>

				<table  style="width: 100% ;margin-top:30px;">
					<tr >
						<td  width="60%" align="right"><b>RECIBIDO</b></td>
						<td style="border: 1px solid black;padding: 20px 0px"></td>
					</tr>
					<tr >
						<td ></td>
						<td align="center" style="font-size: 12px;">Firma</td>
					</tr>
				</table>
			</div>
		@endif

		{{--RECIBO DE AGUINALDO--}}

		@if(str_contains($planilla->m_a, 'Diciembre'))
			<div style="page-break-after:always;" >
				<img src="img/logo-p1.png" >
				<div style="text-align: center;margin-top: 30px;margin-bottom: 30px;"><b>RECIBO AGUINALDO </b></div>
				
				<div style="display: inline-block; width: 47%;font-size: 12px;" >
					<b>Organizacion: </b>WE EFFECT<br>
					<b>Domicilio: </b> {{$planilla->oficina->direccion}} <br>
					<b>Identif. Tributaria: </b> {{$planilla->oficina->nit}} <br>	
					<b>Codigo Patronal: </b> {{$planilla->oficina->num_patronal}} 	
				</div>

				<div style="display: inline-block; width: 47%;font-size: 12px;" >
					<b>Nombre: </b>{{$empleado->user->first_name}} {{$empleado->user->last_name}}<br>
					<b>Identif. Tributaria: </b> {{$empleado->user->n_identificacion_tributaria}} <br>
					<b>Afiliacion al Seguro Social: </b>{{$empleado->user->n_afiliacion}}<br>
					<b>Cargo: </b>{{$empleado->user->cargo->cargo}}	
				</div>	

				<div style="text-align: center;margin: 30px; text-transform: uppercase;">
					<b>Pago de Aguinaldo correspondiente al mes de {{$m_a}} </b>
				</div>
				<table class="tabla_1">
					<tr>
						<td class="titulo" colspan="2"><b>I. SALARIO DEVENGADO:</b></td>			
						
					</tr>					

					<tr>
						<td>Salario mensual Devengado:</td>				
						<td align="right">{{$empleado->salario_base}}</td>
					</tr>	
					
					
					<tr>
						<td>Ajuste Salarial </td>				
						<td align="right">{{$empleado->ajuste}}</td>
					</tr>

					<tr>					
						<td align="right"><b>A. TOTAL DEVENGADO:</b></td>					
						<td class="total">{{$empleado->total_salario}}</td>
					</tr>

					<tr>
						<td class="titulo" colspan="2"><b>II. MESES ACUMULADO:</b></td>
					</tr>
					@foreach($meses as $key => $mes)
					
						<tr>
							<td>{{$mes}}:</td>				
							<td align="right">{{$aguinaldo_meses[$empleado->user->id][$key]}}</td>
						</tr>
					@endforeach
					<tr>
						<td colspan="2" style="padding-bottom: 40px;"></td>
					</tr>
					<tr class="liquido">					
						<td align="right"><b>C. TOTAL AGUINALDO:</b></td>
				
						<td class="total" style="border-bottom: double;">{{$empleado->total_aguinaldo}}</td>
					</tr>

					{{--<tr>
						<td colspan="2" style="padding-bottom: 40px;"></td>
					</tr>
					<tr class="liquido">					
						<td align="right"><b>LIQUIDO TOTAL A PERCIBIR:</b></td>
						<td  class="total" style="border-bottom: double;">{{number_format((float)$empleado->total_salario + (float)$empleado->total_aguinaldo, 2, '.', '')}}</td>
					</tr>--}}
					<tr style="border:none;">
						<td colspan="2" align="center" style="border:none;padding:22px;font-size:14px;">{{$fecha_hoy}}</td>
					</tr>				
				</table>

				<table  style="width: 100% ;margin-top:30px;">
					<tr >
						<td  width="60%" align="right"><b>RECIBIDO</b></td>
						<td style="border: 1px solid black;padding: 20px 0px"></td>
					</tr>
					<tr >
						<td ></td>
						<td align="center" style="font-size: 12px;">Firma</td>
					</tr>
				</table>
			</div>
		@endif

		{{--RECIBO DE PENSIÓN--}}

		@if(str_contains($planilla->m_a, 'Diciembre'))
			<div style="page-break-after:always;" >
				<img src="img/logo-p1.png" >
				<div style="text-align: center;margin-top: 30px;margin-bottom: 30px;"><b>RECIBO PENSIÓN </b></div>
				
				<div style="display: inline-block; width: 47%;font-size: 12px;" >
					<b>Organizacion: </b>WE EFFECT<br>
					<b>Domicilio: </b> {{$planilla->oficina->direccion}} <br>
					<b>Identif. Tributaria: </b> {{$planilla->oficina->nit}} <br>	
					<b>Codigo Patronal: </b> {{$planilla->oficina->num_patronal}} 	
				</div>

				<div style="display: inline-block; width: 47%;font-size: 12px;" >
					<b>Nombre: </b>{{$empleado->user->first_name}} {{$empleado->user->last_name}}<br>
					<b>Identif. Tributaria: </b> {{$empleado->user->n_identificacion_tributaria}} <br>
					<b>Afiliacion al Seguro Social: </b>{{$empleado->user->n_afiliacion}}<br>
					<b>Cargo: </b>{{$empleado->user->cargo->cargo}}	
				</div>	

				<div style="text-align: center;margin: 30px; text-transform: uppercase;">
					<b>Pago de Pensión correspondiente al mes de {{$m_a}} </b>
				</div>
				<table class="tabla_1">
					<tr>
						<td class="titulo" colspan="2"><b>I. SALARIO DEVENGADO:</b></td>			
						
					</tr>					

					<tr>
						<td>Salario mensual Devengado:</td>				
						<td align="right">{{$empleado->salario_base}}</td>
					</tr>	
					
					
					<tr>
						<td>Ajuste Salarial </td>				
						<td align="right">{{$empleado->ajuste}}</td>
					</tr>

					<tr>					
						<td align="right"><b>A. TOTAL DEVENGADO:</b></td>					
						<td class="total">{{$empleado->total_salario}}</td>
					</tr>

					<tr>
						<td class="titulo" colspan="2"><b>II. MESES ACUMULADO:</b></td>
					</tr>
					@foreach($meses as $key => $mes)
						<tr>
							<td>{{$mes}}:</td>				
							<td align="right">{{$pension_meses[$empleado->user->id][$key]}}</td>
						</tr>
					@endforeach
					<tr>
						<td colspan="2" style="padding-bottom: 40px;"></td>
					</tr>
					<tr class="liquido">					
						<td align="right"><b>C. TOTAL PENSIÓN:</b></td>
				
						<td class="total" style="border-bottom: double;">{{$empleado->total_pension}}</td>
					</tr>

					{{--<tr>
						<td colspan="2" style="padding-bottom: 40px;"></td>
					</tr>
					<tr class="liquido">					
						<td align="right"><b>LIQUIDO TOTAL A PERCIBIR:</b></td>
						<td  class="total" style="border-bottom: double;">{{number_format((float)$empleado->total_salario + (float)$empleado->total_aguinaldo, 2, '.', '')}}</td>
					</tr>--}}
					<tr style="border:none;">
						<td colspan="2" align="center" style="border:none;padding:22px;font-size:14px;">{{$fecha_hoy}}</td>
					</tr>				
				</table>

				<table  style="width: 100% ;margin-top:30px;">
					<tr >
						<td  width="60%" align="right"><b>RECIBIDO</b></td>
						<td style="border: 1px solid black;padding: 20px 0px"></td>
					</tr>
					<tr >
						<td ></td>
						<td align="center" style="font-size: 12px;">Firma</td>
					</tr>
				</table>
			</div>
		@endif
	@endforeach
</body>
<style type="text/css">
	body{
		font-family: 'Helvetica';
	}
	.total{
		border-top: 1px solid black;
    	text-align: right;
    	font-weight: bold;
	}
	.tabla_1,.tabla_2 {
		border-collapse: collapse;
	}
	.tabla_1 {
		width: 100%
	}
	.tabla_1 td, .tabla_1 tr{
		border: 1px solid black;
	}
	.tabla_1 .titulo{		
		padding-top: 30px;
	}
	
</style>

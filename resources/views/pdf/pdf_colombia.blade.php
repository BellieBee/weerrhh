
	@include('pdf.pdf_encabezado')

	<!-- TABLA APORTES -->
	<table class="datos_em">
		<tr>
			<td  colspan="7"></td>
			@if (str_contains($planilla->m_a, 'Junio'))
				<td class="titulo_td" height="25" colspan="14" >APORTES</td>
			@else
				<td class="titulo_td"  colspan="12" >APORTES</td>
			@endif		
			<td style="border-left: 1px solid #000"></td>
		</tr>
		<tr class="titulo_tr" >
			<td style="width: 20px;" >Nº</td>
			<td style="width: 130px;">Nombres y Apellidos</td>
			<td >Dias Trabajados</td>
			<td >{{$campo->salario_base}}</td>
			<td >{{$campo->ajustes}}</td>
			<td>Intereses Cesantias</td>
			<td >{{$campo->total_salario}}</td>

			<!--campo aportes-->
			<td>Aux. de transp.</td>
			<td>INCAP/Lic.</td>
			<td>Bono salud</td>
			<td>Capacitación</td>
			<td>Seguro gastos medicos</td>
			<td>Total Incap.</td>
			<td>Incap.</td>
			<td>AUX. Transp.</td>
			<td>AUX. Celular</td>
			<td>Medios Transp.</td>
			<td>Otros Devengos</td>
			<td align="center">TOTAL APORTES</td>
			@if(str_contains($planilla->m_a, 'Junio'))
				<td >Bonificación 14 (Dto 42-92)(Junio)</td>				
				<td align="center" >TOTAL APORTES</td>
			@endif
									
			<!--/campo aportes-->

			<td >{{$campo->liquido}}</td>
		</tr>
		@foreach($planilla->empleados as $empleado)
			<tr class="empleados" >
				<td>{{$empleado->user->id}}</td>
				<td>{{$empleado->nombre}}</td>
				<td>{{$empleado->dias_trabajados}}</td>
				<td>{{number_format($empleado->salario_base,2)}}</td>
				<td>{{number_format($empleado->ajuste,2)}}</td>
				<td>{{number_format($empleado->aporte->intereses_cesantias)}}</td>
				<td>{{number_format($empleado->total_salario,2)}}</td>				
				
				<!--aportes-->
					
				<td>{{number_format($empleado->aporte->auxilio_transporte,2)}}</td>
				<td>{{number_format($empleado->aporte->incap_licencia,2)}}</td>
				<td>{{number_format($empleado->aporte->bono_salud,2)}}</td>
				<td>{{number_format($empleado->aporte->capacitacion,2)}}</td>
				<td>{{number_format($empleado->aporte->seguro_medico,2)}}</td>
				<td>{{number_format($empleado->aporte->total_incapacidad,2)}}</td>
				<td>{{number_format($empleado->aporte->incapacidades,2)}}</td>
				<td>{{number_format($empleado->aporte->aux_trans,2)}}</td>
				<td>{{number_format($empleado->aporte->aux_celular,2)}}</td>
				<td>{{number_format($empleado->aporte->medios_transp,2)}}</td>
				<td>{{number_format($empleado->aporte->otros_devengos,2)}}</td>
				<td>{{number_format($empleado->aporte->total_aportes,2)}}</td>
				@if(str_contains($planilla->m_a, 'Junio'))
					<td>{{number_format($empleado->aporte->bonificacion_14,2)}}</td>	
					<td>{{number_format($empleado->aporte->total_aportes,2)}}</td>
				@endif	
				<!--/aportes-->

				<td>{{number_format($empleado->liquido_recibir,2)}}</td>						
			</tr>
		@endforeach
		<tr class="empleados">
			<td  colspan="3" style="text-align: right;"><b>TOTAL</b></td>
			<!--total salrio base-->
			<td> {{number_format($planilla->empleados->sum('salario_base'),2)}}</td>
			<!--total Ajuste-->
			<td> {{number_format($planilla->empleados->sum('ajuste'),2)}}</td>
			<!--total Intereses Cesantias-->
			<td> {{number_format($planilla->aportes->sum('intereses_cesantias'),2)}}</td>

			<!--total de  salarios-->
			<td> {{number_format($planilla->empleados->sum('total_salario'),2)}}</td>			
			<!--aportes-->	
					
			<td>{{number_format($planilla->aportes->sum('auxilio_transporte'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('incap_licencia'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('bono_salud'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('capacitacion'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('seguro_medico'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('total_incapacidad'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('incapacidades'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('aux_trans'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('aux_celular'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('medios_transp'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('otros_devengos'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('total_aportes'),2)}}</td>
			@if(str_contains($planilla->m_a, 'Junio'))
				<td>{{number_format($planilla->aportes->sum('bonificacion_14'),2)}}</td>			
				<td>{{number_format($planilla->aportes->sum('total_aportes'),2)}}</td>
			@endif
					
			<!--/aportes-->	
				
			<td width="11" >{{number_format($planilla->empleados->sum('liquido_recibir'),2)}}</td>		
		</tr>
	</table>
<br>
<br>
<br>
	<!-- TABLA DEDUCCIONES -->
	<table class="datos_em" style="page-break-after:always;">
		<tr>
			<td  colspan="7"></td>		
			<td class="titulo_td"  colspan="{{$planilla->cell_deducciones}}" >DEDUCCIONES</td>	
			<td style="border-left: 1px solid #000"></td>
		</tr>
		<tr class="titulo_tr" >
			<td style="width: 20px;" >Nº</td>
			<td style="width: 130px;">Nombres y Apellidos</td>
			<td >Dias Trabajados</td>
			<td >{{$campo->salario_base}}</td>
			<td >{{$campo->ajustes}}</td>
			<td>Intereses Cesantias</td>
			<td >{{$campo->total_salario}}</td>
			
			<!--campo deducciones-->
			<td>Salud 4%</td>
			<td>FSP 1%</td>
			<td>Pension 4%</td>
			<td>RTE FTW UVT</td>
			<td>Otros descuentos celulares</td>
			@if( in_array('seguridad_social', explode(',', $planilla->campo_deducciones)) )
				<td>{{$campo->seguridad_social}}</td>	
			@endif

			@if( in_array('impuesto_renta', explode(',', $planilla->campo_deducciones)) )
				<td>{{$campo->impuestos}}</td>
			@endif

			@if( in_array('prestamo', explode(',', $planilla->campo_deducciones)) )
				<td>{{$campo->prestamo}}</td>
			@endif

			@if( in_array('interes', explode(',', $planilla->campo_deducciones)) )
				<td>{{$campo->interes}}</td>
			@endif

			@if( in_array('otras_deducciones', explode(',', $planilla->campo_deducciones)) )
				<td >{{$campo->otras_deducciones}}</td>	
			@endif

			<td >{{$campo->total_deducciones}}</td>
			<!--/campo deducciones-->

			<td >{{$campo->liquido}}</td>
		</tr>
		@foreach($planilla->empleados as $empleado)
			<tr class="empleados" >
				<td>{{$empleado->user->id}}</td>
				<td>{{$empleado->nombre}}</td>
				<td>{{$empleado->dias_trabajados}}</td>
				<td>{{number_format($empleado->salario_base,2)}}</td>
				<td>{{number_format($empleado->ajuste,2)}}</td>
				<td>{{number_format($empleado->aporte->intereses_cesantias)}}</td>
				<td>{{number_format($empleado->total_salario,2)}}</td>				
				
				<!--deducciones-->
				<td>{{number_format($empleado->deduccion->salud,2)}}</td>	
				<td>{{number_format($empleado->deduccion->fsp,2)}}</td>	
				<td>{{number_format($empleado->deduccion->pen,2)}}</td>	
				<td>{{number_format($empleado->deduccion->rte,2)}}</td>
				<td>{{number_format($empleado->deduccion->otros_descuentos_celulares,2)}}</td>

				@if( in_array('seguridad_social', explode(',', $planilla->campo_deducciones)) )
					<td>{{number_format($empleado->deduccion->seguridad_social,2)}}</td>		
				@endif

				@if( in_array('impuesto_renta', explode(',', $planilla->campo_deducciones)) )
					<td>{{number_format($empleado->deduccion->impuesto_renta,2)}}</td>
				@endif

				@if( in_array('prestamo', explode(',', $planilla->campo_deducciones)) )
					<td>{{number_format($empleado->deduccion->prestamo,2)}}</td>
				@endif

				@if( in_array('interes', explode(',', $planilla->campo_deducciones)) )
					<td>{{number_format($empleado->deduccion->interes,2)}}</td>
				@endif

				@if( in_array('otras_deducciones', explode(',', $planilla->campo_deducciones)) )
					<td>{{number_format($empleado->deduccion->otras_deducciones,2)}}</td>		
				@endif
				<td>{{number_format($empleado->deduccion->total_deducciones,2)}}</td>
				<!--/deducciones-->

				<td>{{number_format($empleado->liquido_recibir,2)}}</td>						
			</tr>
		@endforeach
		<tr class="empleados">
			<td  colspan="3" style="text-align: right;"><b>TOTAL</b></td>
			<!--total salrio base-->
			<td> {{number_format($planilla->empleados->sum('salario_base'),2)}}</td>
			<!--total Ajuste-->
			<td> {{number_format($planilla->empleados->sum('ajuste'),2)}}</td>
			<!--total Intereses Cesantias-->
			<td> {{number_format($empleado->aporte->sum('intereses_cesantias'),2)}}</td>

			<!--total de  salarios-->
			<td> {{number_format($planilla->empleados->sum('total_salario'),2)}}</td>

			<!-- total deducciones-->			
			<td>{{number_format($planilla->deducciones->sum('salud'),2)}}</td>	
			<td>{{number_format($planilla->deducciones->sum('fsp'),2)}}</td>	
			<td>{{number_format($planilla->deducciones->sum('pen'),2)}}</td>	
			<td>{{number_format($planilla->deducciones->sum('rte'),2)}}</td>
			<td>{{number_format($planilla->deducciones->sum('otros_descuentos_celulares'),2)}}</td>

			@if( in_array('seguridad_social', explode(',', $planilla->campo_deducciones)) )			
				<td> {{number_format($planilla->deducciones->sum('seguridad_social'),2)}}</td>
			@endif

			<!--total de deduccion impuesto_renta -->
			@if( in_array('impuesto_renta', explode(',', $planilla->campo_deducciones)) )			
				<td> {{number_format($planilla->deducciones->sum('impuesto_renta'),2)}}</td>
			@endif

			<!--total de deduccion prestamo -->
			@if( in_array('prestamo', explode(',', $planilla->campo_deducciones)) )			
				<td> {{number_format($planilla->deducciones->sum('prestamo'),2)}}</td>
			@endif

			<!--total de deduccion -->
			@if( in_array('interes', explode(',', $planilla->campo_deducciones)) )
				<td> {{number_format($planilla->deducciones->sum('interes'),2)}}</td>
			@endif

			<!--total de deduccion otras_deducciones -->
			@if( in_array('otras_deducciones', explode(',', $planilla->campo_deducciones)) )			
				<td> {{number_format($planilla->deducciones->sum('otras_deducciones'),2)}}</td>
			@endif
					
			<td width="11">{{number_format($planilla->deducciones->sum('total_deducciones'),2)}}</td>		
			<!--/ total deducciones-->	
					
			<td width="11" >{{number_format($planilla->empleados->sum('liquido_recibir'),2)}}</td>		
		</tr>
	</table>



	
	
		
	
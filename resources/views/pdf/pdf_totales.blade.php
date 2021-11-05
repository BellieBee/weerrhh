
	@include('pdf.pdf_encabezado')
	<table class="datos_em" style="page-break-after:always;">
		<tr >
				<td  colspan="6"></td>			
				<td class="titulo_td"  colspan="{{$planilla->cell_deducciones}}" >DEDUCCIONES</td>		
				@if($pais->id==1)<!--//GUATEMALA-->					
					<td class="titulo_td"  colspan="4" >APORTES</td>			
				@endif	
				<td style="border-left: 1px solid #000"></td>
			</tr>
		<tr class="titulo_tr" >
			<td style="width: 20px;"  >Nº</td>
			<td style="width: 130px;" >Nombres y Apellidos</td>
				<td >Dias Trabajado</td>
				<td >{{$campo->salario_base}}</td>
				<td >{{$campo->ajustes}}</td>
				<td >{{$campo->total_salario}}</td>
				
				<!--campo deducciones-->				
				
				@if($pais->id==2)<!--//BOLIVIA-->									
				<td >CTA. IND. 10%</td>
				<td >RIESGO 1,71%</td>
				<td >COM. AFP 0,5%</td>
				<td >AFP APORTE SOLIDARIO 0,5%</td>
				<td >AFP APORTE NACIONAL SOLIDARIO 1%</td>
				<td >RC - IVA 13%: </td>

				@elseif($pais->id==3)<!--//NICARAGUA-->	
				<td >Deduccion 1</td>
				<td >Deduccion 2</td>

				@elseif($pais->id==4)<!--//HONDURAS-->
				<td >Seguro Medico</td>
				<td >RAP (1.5%)</td>

				@elseif($pais->id==5)<!--//PARAGUAY-->					


				@elseif($pais->id==6)<!--//SALVADOR-->					
				<td>AFP ({{7.25}}%)</td>
				@endif

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

				<!--campo aportes-->
				@if($pais->id==1)<!--//GUATEMALA-->	
					<td >Bonificacion incentivo</td>
					<td >Bonificación Docto 37 2001</td>
					<td >Reintegros</td>				
					<td align="center" >TOTAL APORTES</td>			
				@endif									
				<!--/campo aportes-->

				{{--@if(str_contains($planilla->m_a, $mes_14))
					<td>Mes Catorceavo</td>
				@endif--}}

				<td >{{$campo->liquido}}</td>



		</tr>
		@foreach($planilla->empleados as $empleado)
			<tr class="empleados" >
				<td>{{$empleado->user->id}}</td>
				<td>{{$empleado->nombre}}</td>
				<!--<td>{{$empleado->cargo}}</td>
				<td>{{$empleado->documento}}</td>
				<td>{{$empleado->fecha_inicio}}</td>-->
				<td>{{$empleado->dias_trabajados}}</td>
				<td>{{number_format($empleado->salario_base,2)}}</td>
				<td>{{number_format($empleado->ajuste,2)}}</td>
				<td>{{number_format($empleado->total_salario,2)}}</td>				

				<!--deducciones-->					

					@if($pais->id==2)<!--//BOLIVIA-->						
						<td>{{number_format($empleado->deduccion->cta_ind,2)}}</td>	
						<td>{{number_format($empleado->deduccion->riesgo,2)}}</td>	
						<td>{{number_format($empleado->deduccion->com_afp,2)}}</td>	
						<td>{{number_format($empleado->deduccion->afp_aporte_solidario,2)}}</td>	
						<td>{{number_format($empleado->deduccion->afp_aporte_nacional_solidario,2)}}</td>	
						<td>{{number_format($empleado->deduccion->rc_iva,2)}}</td>	
					
					@elseif($pais->id==3)<!--//NICARAGUA-->	
						<td>{{number_format($empleado->deduccion->deduccion_1,2)}}</td>
						<td>{{number_format($empleado->deduccion->deduccion_2,2)}}</td>
					
					@elseif($pais->id==4)<!--//HONDURAS-->	
						<td>{{number_format($empleado->deduccion->seguro_medico,2)}}</td>
						<td>{{number_format($empleado->deduccion->rap,2)}}</td>
					
					@elseif($pais->id==5)<!--//PARAGUAY-->				
					
					@elseif($pais->id==6)<!--//SALVADOR-->	
						<td>{{number_format($empleado->deduccion->afp,2)}}</td>		

					@endif

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
				
				<!--aportes-->
					@if($pais->id==1)<!--//GUATEMALA-->
						<td>{{number_format($empleado->aporte->bonificacion_incentivo,2)}}</td>	
						<td>{{number_format($empleado->aporte->bonificacion_docto_37_2001,2)}}</td>	
						<td>{{number_format($empleado->aporte->reintegros,2)}}</td>
						<td>{{number_format($empleado->aporte->total_aportes,2)}}</td>
					@endif			
				<!--/aportes-->

				<!--mes 14 -->
					{{--@if(str_contains($planilla->m_a, $mes_14))
						<td>{{number_format($empleado->aporte->bonificacion_14,2)}}</td>
					@endif--}}

				<td>
					@if(str_contains($planilla->m_a, $mes_14))
						{{number_format((float)$empleado->liquido_recibir - (float)$empleado->aporte->bonificacion_14,2)}}
					@else
						{{number_format($empleado->liquido_recibir,2)}}
					@endif
				</td>

						
			</tr>
		@endforeach
		<tr class="empleados">
				<td  colspan="3" style="text-align: right;"><b>TOTAL</b></td>
				<!--total salrio base-->
				<td> {{number_format($planilla->empleados->sum('salario_base'),2)}}</td>
				<!--total Ajuste-->
				<td> {{number_format($planilla->empleados->sum('ajuste'),2)}}</td>

				<!--total de  salarios-->
				<td> {{number_format($planilla->empleados->sum('total_salario'),2)}}</td>			
				
				<!-- total deducciones-->			
					
					@if($pais->id==2)<!--//BOLIVIA-->				
						<td>{{number_format($planilla->deducciones->sum('cta_ind'),2)}}</td>
						<td>{{number_format($planilla->deducciones->sum('riesgo'),2)}}</td>
						<td>{{number_format($planilla->deducciones->sum('com_afp'),2)}}</td>
						<td>{{number_format($planilla->deducciones->sum('afp_aporte_solidario'),2)}}</td>
						<td>{{number_format($planilla->deducciones->sum('afp_aporte_nacional_solidario'),2)}}</td>
						<td>{{number_format($planilla->deducciones->sum('rc_iva'),2)}}</td>
					@elseif($pais->id==3)<!--//NICARAGUA-->					
						<td>{{number_format($planilla->deducciones->sum('deduccion_1'),2)}}</td>
						<td>{{number_format($planilla->deducciones->sum('deduccion_2'),2)}}</td>
					
					@elseif($pais->id==4)<!--//HONDURAS-->				
						<td>{{number_format($planilla->deducciones->sum('seguro_medico'),2)}}</td>
						<td>{{number_format($planilla->deducciones->sum('rap'),2)}}</td>
					
					@elseif($pais->id==5)<!--//PARAGUAY-->				
					
					@elseif($pais->id==6)<!--//SALVADOR-->				
						<td>{{number_format($planilla->deducciones->sum('afp'),2)}}</td>

					@endif

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

					<!--total de deduccion ', -->
					@if( in_array('interes', explode(',', $planilla->campo_deducciones)) )
					<td> {{number_format($planilla->deducciones->sum('interes'),2)}}</td>
					@endif

					<!--total de deduccion otras_deducciones -->
					@if( in_array('otras_deducciones', explode(',', $planilla->campo_deducciones)) )			
					<td> {{number_format($planilla->deducciones->sum('otras_deducciones'),2)}}</td>
					@endif
					
					<td width="11">{{number_format($planilla->deducciones->sum('total_deducciones'),2)}}</td>		
				<!--/ total deducciones-->	
				<!--aportes-->	
					@if($pais->id==1)<!--//GUATEMALA-->					
						<td>{{number_format($planilla->aportes->sum('bonificacion_incentivo'),2)}}</td>		
						<td>{{number_format($planilla->aportes->sum('bonificacion_docto_37_2001'),2)}}</td>	
						<td>{{number_format($planilla->aportes->sum('reintegros'),2)}}</td>			
						{{--<td>{{number_format($planilla->aportes->sum('bonificacion_14'),2)}}</td>--}}			
						<td>{{number_format($planilla->aportes->sum('total_aportes'),2)}}</td>		
					{{--@else
						@if(str_contains($planilla->m_a, 'Junio'))
							<td>{{number_format($planilla->aportes->sum('bonificacion_14'),2)}}</td>			
							<td>{{number_format($planilla->aportes->sum('total_aportes'),2)}}</td>
						@endif--}}
					@endif
					
				<!--/aportes-->

				<!-- mes 14 -->

				{{--@if(str_contains($planilla->m_a, $mes_14))
					<td>{{number_format($planilla->aportes->sum('bonificacion_14'))}}</td>
				@endif--}}	
				
				<td width="11">
					@if(str_contains($planilla->m_a, $mes_14))
						{{number_format((float)$planilla->empleados->sum('liquido_recibir') - (float)$planilla->aportes->sum('bonificacion_14'),2)}}
					@else
						{{number_format((float)$planilla->empleados->sum('liquido_recibir'),2)}}
					@endif
				</td>
				
				{{--<td width="11">{{number_format($planilla->deducciones->sum('total_deducciones'),2)}}</td>
				<td width="11">{{number_format($planilla->aportes->sum('total_aportes'),2)}}</td>
				
				
				<td width="11" >{{number_format($planilla->empleados->sum('liquido_recibir'),2)}}</td>
				<td width="11">{{number_format($planilla->aportes->sum('total_carga_patronal'),2)}}</td>--}}		
			</tr>
	</table>




	
	
		
	
	@include('pdf.pdf_encabezado')
	
	<h2>Aportes Patronales</h2>
	<table class="datos_em" style="page-break-after:always;">
		<tr>
			<td  colspan="2"></td>			
			<td class="titulo_td" align="center" colspan="{{$planilla->cell_aportes_patronales}}">
				APORTES PATRONALES
			</td>		

		</tr>
		<tr class="titulo_tr" >
			<td style="width: 20px;"  >NÂº</td>
			<td style="width: 130px;" >Nombres y Apellidos</td>			

			@if($pais->id==2)<!--//BOLIVIA-->
			<td > Seguro universitario (10%)</td>
			<td > AFP Prevision (1.7%)</td>
			<td > AFP Prevision PNVS (2%)</td>			
			<td >AFP Aporte Solidario (3%) </td>				

			@elseif($pais->id==3)<!--//NICARAGUA-->
			<td align="center" >INATEC</td>

			@elseif($pais->id==4)<!--//HONDURAS-->
			<td align="center" >RAP 1.5%</td>

			@elseif($pais->id==5)<!--//PARAGUAY-->
			<td align="center" >Total_aporte (25.5%)</td>

			@elseif($pais->id==6)<!--//SALVADOR-->			
			<td align="center"  >AFP (7.75%): </td>

			@elseif($pais->id == 7)<!--//COLOMBIA-->
			<td align="center">Caja de Compensaci¨®n {{$pais->porcentaje_parafiscales}}%</td>
			<td align="center">Arl 2.43%</td>
			<td align="center">EPS {{$pais->porcentaje_eps}}%</td>
			<td align="center">Caja OpciÃ³n {{$pais->porcentaje_caja_opcion}}%</td>
			<td align="center">ICBF {{$pais->porcentaje_icbf}}%</td>
			<td align="center">SENA {{$pais->porcentaje_sena}}%</td>
			<td align="center">Salud 8.5%</td>
			<td align="center">Pension patronal 12%</td>
			@endif	

			@if($pais->id!=2 && $pais->id != 7)		
			<td >{{$campo->seguridad_social_patronal}}</td>
			@endif
			<td align="center">TOTAL APORTES  PATRONALES</td>					
		</tr>		

		@foreach($planilla->empleados as $empleado)
		<tr class="empleados" >
			<td>{{$empleado->id}}</td>
			<td>{{$empleado->nombre}}</td>			

			@if($pais->id==2)<!--//BOLIVIA-->
			<td>{{number_format($empleado->aporte->seguro_universitario,2)}}</td>	
			<td>{{number_format($empleado->aporte->afp_prevision,2)}}</td>	
			<td>{{number_format($empleado->aporte->afp_prevision_pnvs,2)}}</td>	
			<td>{{number_format($empleado->aporte->afp_aporte_solidario,2)}}</td>				

			@elseif($pais->id==3)<!--//NICARAGUA-->
			<td>{{number_format($empleado->aporte->INATEC,2)}}</td>

			@elseif($pais->id==4)<!--//HONDURAS-->
			<td>{{number_format($empleado->aporte->rap,2)}}</td>

			@elseif($pais->id==5)<!--//PARAGUAY-->
			<td>{{number_format($empleado->aporte->total_aporte_25_5,2)}}</td>

			@elseif($pais->id==6)<!--//SALVADOR-->	
			<td>{{number_format($empleado->aporte->afp_6_75,2)}}</td>

			@elseif($pais->id == 7)<!--//COLOMBIA-->
			<td>{{number_format($empleado->aporte->parafiscales,2)}}</td>
			<td>{{number_format($empleado->aporte->arl,2)}}</td>
			<td>{{number_format($empleado->aporte->eps,2)}}</td>
			<td>{{number_format($empleado->aporte->caja_opcion,2)}}</td>
			<td>{{number_format($empleado->aporte->icbf,2)}}</td>
			<td>{{number_format($empleado->aporte->sena,2)}}</td>
			<td>{{number_format($empleado->aporte->salud_patronal,2)}}</td>
			<td>{{number_format($empleado->aporte->pension_patronal,2)}}</td>
			@endif

			@if($pais->id!=2 && $pais->id != 7)<!--//SALVADOR-->	
			<td>{{number_format($empleado->aporte->seguridad_social_patronal,2)}}</td>
			@endif	


			<td align="right">{{number_format($empleado->aporte->total_carga_patronal,2)}}</td>	
		</tr>
		@endforeach

		<tr class="empleados totales" >
			<td  colspan="2" style="text-align: right;"><b>TOTAL</b></td>						

			@if($pais->id==2)<!--//BOLIVIA-->	
			<td>{{number_format($planilla->aportes->sum('seguro_universitario'),2)}}</td>		
			<td>{{number_format($planilla->aportes->sum('afp_prevision'),2)}}</td>	
			<td>{{number_format($planilla->aportes->sum('afp_prevision_pnvs'),2)}}</td>			
			<td>{{number_format($planilla->aportes->sum('afp_aporte_solidario'),2)}}</td>				

			@elseif($pais->id==3)<!--//NICARAGUA-->	
			<td>{{number_format($planilla->aportes->sum('INATEC'),2)}}</td>	

			@elseif($pais->id==4)<!--//HONDURAS-->	
			<td>{{number_format($planilla->aportes->sum('rap'),2)}}</td>

			@elseif($pais->id==5)<!--//PARAGUAY-->	
			<td>{{number_format($planilla->aportes->sum('total_aporte_25_5'),2)}}</td>

			@elseif($pais->id==6)<!--//SALVADOR-->	
			<td>{{number_format($planilla->aportes->sum('afp_6_75'),2)}}</td>

			@elseif($pais->id == 7)<!--//COLOMBIA-->
			<td>{{number_format($planilla->aportes->sum('parafiscales'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('arl'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('eps'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('caja_opcion'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('icbf'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('sena'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('salud_patronal'),2)}}</td>
			<td>{{number_format($planilla->aportes->sum('pension_patronal'),2)}}</td>
			@endif

			@if($pais->id!=2 && $pais->id != 7)	
			<td>{{number_format($planilla->aportes->sum('seguridad_social_patronal'),2)}}</td>	
			@endif

			<td align="right">{{number_format($planilla->aportes->sum('total_carga_patronal'),2)}}</td>

		</tr>
	</table>

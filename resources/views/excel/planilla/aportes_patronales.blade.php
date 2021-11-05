	@extends('excel.planilla_normal')
	
	@section('tabla')

		<tr>
			<td  colspan="2"></td>			
			<td class="titulo_td" height="25" align="center" colspan="{{$planilla->cell_aportes_patronales}}">
				APORTES PATRONALES
			</td>		
			
		</tr>
		<tr class="titulo_tr" >
			<td width="4" >Nº</td>
			<td width="18" >Nomrbe y Apellidos</td>		
			<!--<td width="14">Cargo</td>
			<td width="14">No. Documento</td>
			<td width="14">Fecha Ingreso</td>-->				

			@if($pais->id==2)<!--//BOLIVIA-->
				<td width="15"> Seguro universitario (10%)</td>
				<td width="15"> AFP Previsión (1.7%)</td>
				<td width="15"> AFP Previsión PNVS (2%)</td>			
				<td width="15">AFP Aporte Solidario (3%) </td>				
			
			@elseif($pais->id==3)<!--//NICARAGUA-->
				<td align="center" width="11">INATEC</td>
			
			@elseif($pais->id==5)<!--//PARAGUAY-->
				<td align="center" width="15">Total_aporte (25.5%)</td>
			
			@elseif($pais->id==6)<!--//SALVADOR-->			
				<td align="center" width="11" >AFP (7.75%): </td>
				
			@endif	
			
			@if($pais->id!=2)		
			<td width="12">{{$campo->seguridad_social_patronal}}</td>
			@endif
			<td align="center" width="20" >TOTAL APORTES  PATRONALES</td>					
		</tr>		
		
		@foreach($planilla->empleados as $empleado)
		<tr class="din empleado" >
			<td>{{$empleado->id}}</td>
			<td>{{$empleado->nombre}}</td>			

			@if($pais->id==2)<!--//BOLIVIA-->
				<td>{{number_format($empleado->aporte->seguro_universitario,2)}}</td>	
				<td>{{number_format($empleado->aporte->afp_prevision,2)}}</td>	
				<td>{{number_format($empleado->aporte->afp_prevision_pnvs,2)}}</td>	
				<td>{{number_format($empleado->aporte->afp_aporte_solidario,2)}}</td>				
			
			@elseif($pais->id==3)<!--//NICARAGUA-->
				<td>{{number_format($empleado->aporte->INATEC,2)}}</td>
			
			@elseif($pais->id==5)<!--//PARAGUAY-->
				<td>{{number_format($empleado->aporte->total_aporte_25_5,2)}}</td>
			
			@elseif($pais->id==6)<!--//SALVADOR-->	
				<td>{{number_format($empleado->aporte->afp_6_75,2)}}</td>
			@endif

			@if($pais->id!=2)<!--//SALVADOR-->	
				<td>{{number_format($empleado->aporte->seguridad_social_patronal,2)}}</td>
			@endif	

			
			<td>{{number_format($empleado->aporte->total_carga_patronal,2)}}</td>	
		</tr>
		@endforeach
		
		<tr class="din" style="font-weight: bold;">
			<td  colspan="2" style="text-align: right;"><b>TOTAL</b></td>						

			@if($pais->id==2)<!--//BOLIVIA-->	
				<td>{{number_format($planilla->aportes->sum('seguro_universitario'),2)}}</td>		
				<td>{{number_format($planilla->aportes->sum('afp_prevision'),2)}}</td>	
				<td>{{number_format($planilla->aportes->sum('afp_prevision_pnvs'),2)}}</td>			
				<td>{{number_format($planilla->aportes->sum('afp_aporte_solidario'),2)}}</td>				
			
			@elseif($pais->id==3)<!--//NICARAGUA-->	
				<td>{{number_format($planilla->aportes->sum('INATEC'),2)}}</td>	
			
			@elseif($pais->id==5)<!--//PARAGUAY-->	
				<td>{{number_format($planilla->aportes->sum('total_aporte_25_5'),2)}}</td>

			@elseif($pais->id==6)<!--//SALVADOR-->	
				<td>{{number_format($planilla->aportes->sum('afp_6_75'),2)}}</td>
			@endif

			@if($pais->id!=2)	
				<td>{{number_format($planilla->aportes->sum('seguridad_social_patronal'),2)}}</td>	
			@endif
			
			<td>{{number_format($planilla->aportes->sum('total_carga_patronal'),2)}}</td>
				
		</tr>	
		
		
	@endsection
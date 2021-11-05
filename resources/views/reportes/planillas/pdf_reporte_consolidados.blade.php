<body>
	@include('reportes.planillas.pdf_encabezado')

	<br>
	@foreach($oficinas as $oficina)
		@if(count($planillas->where('oficina_id', $oficina->id)))
			<h2>{{$oficina->oficina}}</h2>
			<table border="1"  class="datos_em" style="page-break-after:always;">
				<tr class="text-center titulo_grupo">
					<td colspan="3"></td>
					<td colspan="4" class="color_1">TOTALES</td>
					<td class="color_2">ARPOTES PATRONALES</td>
					<td colspan="4" class="color_3">ACUMULADOS</td>
				</tr>
				<tr class="titulo_td">
					<th >Oficina</th>
					<th style="width: 100px;">Fecha</th>
					<th style="width: 60px;">Tipo de cambio</th>
					<th class="color_1">Salario total</th>
					<th class="color_1" >Total aportes</th>
					<th class="color_1" style="width: 80px;" >total deducciones</th>
					<th class="color_1" style="width: 100px;">liquido a recibir</th>

					<th class="color_2" style="width: 100px;">total aporte patronales</th>

					<th class="color_3" >aguinaldo (Junio)</th>
					<th class="color_3" >aguinaldo (Diciembre)</th>
					<th class="color_3" >Fondo pension</th>
					<th class="color_3" >Indemnizacion</th>
				</tr>
				@foreach($planillas->where('oficina_id', $oficina->id)->sortBy('created_at') as $planilla)			
					<tr class="empleados">
						<td>{{$planilla->oficina->oficina}}</td>
						<td>{{$planilla->m_a}}</td>
						<td>{{$planilla->cambio}}</td>
						
						<td class="color_1">{{  number_format($planilla->empleados->sum('total_salario')*$planilla->cambio_mensual,2)  }}</td>
						<td class="color_1">{{  number_format($planilla->aportes->sum('total_aportes')*$planilla->cambio_mensual,2)  }}</td>
						<td class="color_1">{{  number_format($planilla->deducciones->sum('total_deducciones')*$planilla->cambio_mensual,2)  }}</td>
						<td class="color_1">{{  number_format($planilla->empleados->sum('liquido_recibir')*$planilla->cambio_mensual,2)  }}</td>

						<td class="color_2">{{  number_format($planilla->aportes->sum('total_carga_patronal')*$planilla->cambio_mensual,2)  }}</td>

						<td class="color_3">{{  number_format($planilla->acumulados->sum('catorceavo')*$planilla->cambio_mensual,2)  }}</td>
						<td class="color_3">{{  number_format($planilla->acumulados->sum('aguinaldo')*$planilla->cambio_mensual,2)  }}</td>
						<td class="color_3">{{  number_format($planilla->acumulados->sum('pension')*$planilla->cambio_mensual,2)  }}</td>
						<td class="color_3">{{  number_format($planilla->acumulados->sum('indemnizacion')*$planilla->cambio_mensual,2)  }}</td>
					</tr>
					
				@endforeach
				
			</table>
		@endif	
	@endforeach

</body>
<style type="text/css">	
	body{
		font-family: 'Helvetica';
	}
	table{
		border-collapse: collapse;
		font-size: 10px;
	}
	
	.titulo_td th{
		padding: 3px 3px;
	}
	th{		
		background: #e0e0e0 ;
		text-align: center;
		font-weight: bold;
		text-transform: uppercase;			
	}
	
	.empleados td{
		min-width: 50px;
		padding-bottom: 5px;
		padding-top: 5px;
		padding-left: 3px;
		padding-right: 3px;
		font-size: 11px;
	}
	.titulo_grupo{
		text-align: center;
		font-weight: bold;
	}
	.titulo_grupo td{
		padding: 8px;
	}
	.color_1{
		background: #8bc34a7d;
	}
	.color_2{
		background: #ffeb3b7d;
	}
	.color_3{
		background: #03a9f457;
	}

</style>
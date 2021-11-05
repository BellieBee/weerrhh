<body>
	@include('reportes.permiso_vacaciones.pdf_encabezado')
		<div style="text-align: center;margin-top: 30px;"><b>REPORTE DE VACACIONES</b></div>
	<br>
	@foreach($oficinas as $oficina)
		
		@if(count($reporte->where('oficina_id', $oficina->id)))
			<h2>{{$oficina->oficina}}</h2>
			<table border="1"  class="datos_em" style="page-break-after:always;">
				<tr>
					<th >Oficina</th>
					<th >Fechade solicitud</th>
					<th >Tiempo de vacaciones</th>					
					<th >Dias solicitados</th>					
					<th >Colega</th>
					<th >Tiempo acumulado</th>
								
				</tr>				
				@foreach($reporte->where('oficina_id', $oficina->id)->sortBy('created_at') as $vacaciones)			
					
					<tr class="empleados">
						<td>{{$oficina->oficina}}</td>
						<td>{{$vacaciones->created_at->format('d-m-Y')}}</td>
						<td>{{$vacaciones->num_dh}} {{$vacaciones->dh}}</td>
						
						<td>@foreach(explode(',', $vacaciones->fechas) as $fecha)
							{{$fecha}}<br>
							@endforeach
						</td>
						
						<td>{{$vacaciones->user->first_name}} {{$vacaciones->user->last_name}}</td>
						<td>{{($vacaciones->user->acumulado_vacaciones+count($vacaciones->user->planilla) )*$oficina->pais->vacaciones}}</td>
											
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
	
	th{		
		background: #e0e0e0 ;
		text-align: center;
		font-weight: bold;
		text-transform: uppercase;
		padding: 10px;
		
	}
	
	.empleados td{
		min-width: 50px;
		padding-bottom: 5px;
		padding-top: 5px;
		padding-left: 3px;
		padding-right: 3px;
		font-size: 12px;
	}
	

</style>
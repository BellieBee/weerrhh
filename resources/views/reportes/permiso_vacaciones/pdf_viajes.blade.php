<body>
	@include('reportes.permiso_vacaciones.pdf_encabezado')
		<div style="text-align: center;margin-top: 30px;"><b>REPORTE DE VIAJES DE TRABAJO</b></div>
	<br>
	@foreach($oficinas as $oficina)
		
		@if(count($reporte->where('oficina_id', $oficina->id)))
			<h2>{{$oficina->oficina}}</h2>
			<table border="1"  class="datos_em" style="page-break-after:always;">
				<tr>
					<th >Oficina</th>
					<th >Fecha de solicitud</th>
					<th >Tiempo del viaje</th>					
					<th >Dias solicitados</th>					
					<th >Colega</th>
					<th>Destino</th>
					<th>Comentarios</th>
								
				</tr>				
				@foreach($reporte->where('oficina_id', $oficina->id)->sortBy('created_at') as $viajes)			
					
					<tr class="empleados">
						<td>{{$oficina->oficina}}</td>
						<td>{{$viajes->created_at->format('d-m-Y')}}</td>
						<td>{{$viajes->num_dh}} {{$viajes->dh}}</td>
						
						<td>@foreach(explode(',', $viajes->fechas) as $fecha)
							{{$fecha}}<br>
							@endforeach
						</td>
						
						<td>{{$viajes->user->first_name}} {{$viajes->user->last_name}}</td>
                        <td>{{$viajes->destino}}</td>
                        <td>{{$viajes->comentarios}}</td>
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
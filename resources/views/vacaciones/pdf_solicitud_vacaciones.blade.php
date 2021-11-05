<body>
	@include('vacaciones.pdf_encabezado')
		<div style="text-align: center;margin-top: 30px;"><b>SOLICITUD DE VACACIONES</b></div>
	<br>
	
	<table border="1"  class="datos_em">
		<tr>
			<th>Oficina</th>
			<th>Fecha de solicitud</th>
			<th>Tiempo de vacaciones</th>					
			<th>Dias solicitados</th>					
			<th>Colega</th>
			<th>Tiempo acumulado</th>								
		</tr>												
		<tr class="empleados">
			<td>{{$user->oficina->oficina}}</td>
			<td>{{$solicitud->created_at->format('d-m-Y')}}</td>
			<td>{{$solicitud->num_dh}} {{$solicitud->dh}}</td>
						
			<td>@foreach(explode(',', $solicitud->fechas) as $fecha)
					{{$fecha}}<br>
				@endforeach
			</td>
						
			<td>{{$user->first_name}} {{$user->last_name}}</td>
			<td>{{round($user->acumulate, 2)}}</td>									
		</tr>								
	</table>
	<br>
	<br>
	<br>

	@include('vacaciones.pdf_firma_colega')

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
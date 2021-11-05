@php $dias_restantes = 0; @endphp

<body>
	@include('reportes.dias_vacaciones.pdf_encabezado')
		<div style="text-align: center;margin-top: 30px;"><b>REPORTE DE DÍAS VACACIONES</b></div>
	<br>

	<table border="1"  class="datos_em">
		<tr class="empleados">
			<td><b>Colega</b></td>
			<td><b>Días Acumulados</b></td>
			<td><b>Días por Planilla</b></td>
			<td><b>Días Gastados</b></td>
			<td><b>Días Restantes</b></td>
		</tr>
		@foreach($users as $key => $user)
			<tr class="empleados">
				@php 
					$dias_restantes = ($user->acumulado_vacaciones + $dias_planilla[$key]) - $solicitudes[$key]; 
				@endphp
				<td>{{$user->first_name}} {{$user->last_name}}</td>
				<td>{{$user->acumulado_vacaciones}}</td>
				<td>{{$dias_planilla[$key]}}</td>
				<td>{{$solicitudes[$key]}}</td>
				<td>{{$dias_restantes}}</td>
			</tr>
		@endforeach
	</table>
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
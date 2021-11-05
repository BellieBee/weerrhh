<body>
	@include('reportes.dias_vacaciones.pdf_encabezado')
		<div style="text-align: center;margin-top: 30px;"><b>REPORTE DE COLEGAS</b></div>
	<br>

	<table border="1"  class="datos_em">
		<tr class="empleados">
			<td><b>Nro Contrato</b></td>
			<td><b>Nombres</b></td>
			<td><b>Apellidos</b></td>
			<td><b>Correo Electrónico</b></td>
			<td><b>Cargo</b></td>
            <td><b>Categoría</b></td>
            <td><b>Fecha de Inicio</b></td>
            <td><b>Fecha de Finalización</b></td>
            <td><b>Rol</b></td>
            <td><b>Estado</b></td>
		</tr>
		@foreach($colegas as $key => $colega)
			<tr class="empleados">
                <td>{{$colega->n_contrato}}</td>
				<td>{{$colega->first_name}}</td>
                <td>{{$colega->last_name}}</td>
				<td>{{$colega->email}}</td>
				<td>{{$colega->cargo->cargo}}</td>
				<td>{{$colega->categoria->categoria}}</td>
				<td>{{date('d-m-Y', strtotime($colega->fecha_inicio))}}</td>
                <td>{{$colega->fecha_finalizacion != null ? date('d-m-Y', strtotime($colega->fecha_finalizacion)) : ''}}</td>
                <td>{{$colega->roles->first()->display_name}}</td>
                <td>{{$colega->status == 1 ? 'Activo' : 'Inactivo'}}</td>
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
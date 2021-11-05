<body>
	@include('reportes.permiso_vacaciones.pdf_encabezado')
		<div style="text-align: center;margin-top: 30px;"><b>REPORTE DE {{strtoupper($reporte->tipo)}}</b></div>
	<br>
	@foreach($oficinas as $oficina)
		
		@if(count($reporte->where('oficina_id', $oficina->id)))
			<h2>{{$oficina->oficina}}</h2>
			<table border="1"  class="datos_em" style="page-break-after:always;">
				<tr>
					<th>Correlativo</th>
					<th>Fecha de solicitud</th>
					<th>Solicitante</th>					
					<th>Tramitante</th>
                    <th>Proveedor</th>					
					<th>Tipo de Compra</th>
					<th>Actividad</th>
                    <th>Monto en {{$oficina->pais->moneda_nombre}}</th>
                    <th>Monto en USD</th>
                    <th>Monto en SK</th>	
				</tr>				
				@foreach($reporte->where('oficina_id', $oficina->id)->sortBy('fecha') as $actividad)			
					
					<tr class="empleados">
						<td>{{$actividad->correlativo}}</td>
						<td>{{date('d-m-Y', strtotime($actividad->fecha))}}</td>
						<td>{{$actividad->user->first_name}} {{$actividad->user->last_name}}</td>
						<td>{{$actividad->admin->first_name}} {{$actividad->admin->last_name}}</td>
						<td>@if ($actividad->proveedor_id != null) {{$actividad->proveedor->razon_social}} @elseif ($actividad->proveedor_user_id != null) {{$actividad->proveedorUser->first_name}} {{$actividad->proveedorUser->last_name}} @else "NO PROVEEDOR" @endif</td>
						<td>{{$actividad->tipo_compra->valor_generico}}</td>
                        <td>{{$actividad->actividad}}</td>
                        <td>{{$actividad->monto}}</td>
                        <td>{{$actividad->monto_usd}}</td>
                        <td>{{$actividad->monto_sk}}</td>
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
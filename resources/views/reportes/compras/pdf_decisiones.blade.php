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
					<th>Tramitante</th>
                    <th>Proveedor</th>					
					<th>Antecedentes</th>
					<th>Decisión</th>
					<th>Justificación</th>
					<th>Notas</th>
                    <th>Monto en {{$oficina->pais->moneda_nombre}}</th>
                    <th>Monto en USD</th>
                    <th>Monto en SK</th>	
				</tr>				
				@foreach($reporte->where('oficina_id', $oficina->id)->sortBy('fecha') as $decision)			
					
					<tr class="empleados">
						<td>{{$decision->correlativo}}</td>
						<td>{{date('d-m-Y', strtotime($decision->fecha))}}</td>
						<td>{{$decision->actividad->admin->first_name}} {{$decision->actividad->admin->last_name}}</td>
						<td>@if ($decision->proveedor_id != 0) {{$decision->proveedor->razon_social}} @elseif ($decision->proveedor_user_id != null) {{$decision->proveedorUser->first_name}} {{$decision->proveedorUser->last_name}} @else "NO PROVEEDOR" @endif</td>
						<td>{{$decision->antecedentes}}</td>
                        <td>{{$decision->decision}}</td>
						<td>{{$decision->justificacion}}</td>
						<td>{{$decision->notas}}</td>
                        <td>{{$decision->actividad->monto}}</td>
                        <td>{{$decision->actividad->monto_usd}}</td>
                        <td>{{$decision->actividad->monto_sk}}</td>
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
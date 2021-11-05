<body>
	@include('reportes.permiso_vacaciones.pdf_encabezado')
		<div style="text-align: center;margin-top: 30px;"><b>REPORTE DE ORDENES DE COMPRA</b></div>
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
					<th>Descripción</th>
                    <th>Monto en {{$oficina->pais->moneda_nombre}}</th>
                    <th>Monto en USD</th>
                    <th>Monto en SK</th>	
				</tr>				
				@foreach($reporte->where('oficina_id', $oficina->id)->sortBy('fecha') as $orden)			
					
					<tr class="empleados">
						<td>{{$orden->correlativo}}</td>
						<td>{{date('d-m-Y', strtotime($orden->fecha))}}</td>
						<td>{{$orden->solicitante->first_name}} {{$orden->solicitante->last_name}}</td>
						<td>@if ($orden->proveedor_id != 0) {{$orden->proveedor->razon_social}} @elseif ($orden->proveedor_user_id != null) {{$orden->proveedorUser->first_name}} {{$orden->proveedorUser->last_name}} @else "NO PROVEEDOR" @endif</td>
						<td>{{$orden->descripcion}}</td>
                        <td>{{$orden->monto}}</td>
                        <td>{{$orden->monto_usd}}</td>
                        <td>{{$orden->monto_sk}}</td>
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
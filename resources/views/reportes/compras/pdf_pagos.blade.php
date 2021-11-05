<body>
	@include('reportes.permiso_vacaciones.pdf_encabezado')
		<div style="text-align: center;margin-top: 30px;"><b>REPORTE DE {{strtoupper($reporte->tipo)}}</b></div>
	<br>
	@foreach($oficinas as $oficina)
		
		@if(count($reporte->where('oficina_id', $oficina->id)))
			<h2>{{$oficina->oficina}}</h2>
			<table border="1"  class="datos_em" style="page-break-after:always;">
				<tr>
					<th>Nro Actividad</th>
					<th>Fecha de solicitud</th>				
					<th>Tramitante</th>
                    <th>Usuario a Pagar</th>
                    <th>Proveedor</th>	
                    <th>Tipo de Pago</th>				
					<th>Concepto</th>
                    <th>Monto neto en {{$oficina->pais->moneda_nombre}}</th>
                    <th>Impuesto (%)</th>
                    <th>Descuento ISR (%)</th>
                    <th>Monto en USD</th>
                    <th>Monto en SK</th>
                    <th>Monto Total</th>	
				</tr>				
				@foreach($reporte->where('oficina_id', $oficina->id)->sortBy('fecha') as $pago)			
					
					<tr class="empleados">
						@if ($pago->actividad_id != null)
                            <td>{{$pago->actividad->correlativo}}</td>
                        @elseif ($pago->orden_compra_id != null)
                            <td>{{$pago->ordencompra->actividad->correlativo}}</td>
                        @elseif($pago->contrato_id != null)
                            <td>{{$pago->contrato->ordencompra->decision->actividad->correlativo}}</td>
                        @endif
						<td>{{date('d-m-Y', strtotime($pago->fecha))}}</td>
						<td>{{$pago->tramitante->first_name}} {{$pago->tramitante->last_name}}</td>
                        <td>{{$pago->user->first_name}} {{$pago->user->last_name}}</td>
						<td>@if ($pago->proveedor_id != 0) {{$pago->proveedor->razon_social}} @elseif ($pago->proveedor_user_id != null) {{$pago->proveedorUser->first_name}} {{$pago->proveedorUser->last_name}} @else "NO PROVEEDOR" @endif</td>
						<td>{{$pago->tipopago->nombre}}</td>
                        <td>{{$pago->concepto}}</td>
                        <td>{{$pago->monto}}</td>
                        <td>{{$pago->impuesto}}</td>
                        <td>{{$pago->isr}}</td>
                        <td>{{$pago->monto_usd}}</td>
                        <td>{{$pago->monto_sk}}</td>
                        <td>{{$pago->monto_total}}</td>
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
<body>
	@include('reportes.permiso_vacaciones.pdf_encabezado')
		<div style="text-align: center;margin-top: 30px;"><b>REPORTE DE BONO SALUD</b></div>
	<br>
	@foreach($oficinas as $oficina)
		
		@if(count($reporte->where('oficina_id', $oficina->id)))
			<h2>{{$oficina->oficina}}</h2>
			<table border="1"  class="datos_em" style="page-break-after:always;">
				<tr>
					<th >Oficina</th>
					<th >Fecha de solicitud</th>
					<th >Colega</th>
					<th >Destino</th>
					<th >Saldo Inicial</th>					
					<th >Costo Moneda Local</th>					
					<th >Tipo Cambio SK</th>
					<th >Tipo Cambio USD</th>
					<th >Costo SK</th>
					<th >Saldo Final</th>
								
				</tr>				
				@foreach($reporte->where('oficina_id', $oficina->id)->sortBy('fecha_solicitud') as $bono)			
					
					<tr class="empleados">
						<td>{{$oficina->oficina}}</td>
						<td>{{date('d-m-Y', strtotime($bono->fecha_solicitud))}}</td>
						<td>{{$bono->user->first_name}} {{$bono->user->last_name}}</td>
						<td>{{$bono->destino}}</td>
						<td>{{$bono->saldo_inicial}}</td>
						<td>{{$bono->costo_moneda_local}}</td>
						<td>{{$bono->tipo_cambio_sk}}</td>
						<td>{{$bono->tipo_cambio_usd}}</td>
						<td>{{$bono->costo_sk}}</td>
						<td>{{$bono->saldo_final}}</td>
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
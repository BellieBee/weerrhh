<body style="">
	

	@foreach($oficinas as $oficina)	
		@if(count($contratos->where('oficina_id', $oficina->id)))
		<div>
			@include('reportes.contratos.pdf_encabezado')
			<div style="text-align: center;margin-top: 30px;"><b>REPORTE DE CONTRATOS</b></div>
		</div>		
		
			<h2>{{$oficina->oficina}}</h2>
			<table border="1"  class="datos_em" style="page-break-after:always;">
				<tr>
					<th >Oficina</th>
					<th >Nº de contrato</th>
					<th >Fecha de Contrato</th>
					<th>Consultoria</th>
					<th>Consultor</th>
					<th>Periodo</th>
					<th>Tiempo de contrato</th>
					<th>Pago Total</th>								
				</tr>
				
				@foreach($contratos->where('oficina_id', $oficina->id)->sortByDesc('created_at') as $contrato)				
					<tr class="empleados">
						<td>{{$oficina->oficina}}</td>
						<td>{{$contrato->n_contrato}}</td>
						<td>{{$contrato->created_at}}</td>
						<td>{{$contrato->consultoria}}</td>
						<td>{{$contrato->user->first_name}} {{$contrato->user->last_name}}</td>
						<td>{{$contrato->fecha_inicio}} hasta {{$contrato->fecha_fin}}</td>
						<td>{{$contrato->tiempo_contrato}} dias</td>										
						<td> {{$oficina->pais->moneda_simbolo}}{{$contrato->monto_total}}</td>										
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
<body>
	@include('bono_salud.pdf_encabezado')
		<div style="text-align: center;margin-top: 30px;"><b>SOLICITUD DE BONO SALUD</b></div>
	<br>
	
	<table border="1"  class="datos_em">
		<tr>
			<th>Oficina</th>
			<th>Fecha de solicitud</th>
			<th>Colega</th>
			<th>Saldo Inicial</th>					
			<th>Costo Moneda Local</th>
			<th>Tipo Cambio SK</th>
			<th>Tipo Cambio USD</th>					
			<th>Costo SK</th>
			<th>Saldo Final</th>
			<th>Destino</th>								
		</tr>												
		<tr class="empleados">
			<td>{{$user->oficina->oficina}}</td>
			<td>{{$bono_salud->fecha_solicitud}}</td>
			<td>{{$user->first_name}} {{$user->last_name}}</td>
			<td>{{$bono_salud->saldo_inicial}}</td>
			<td>{{$bono_salud->costo_moneda_local}}</td>
			<td>{{$bono_salud->tipo_cambio_sk}}</td>
			<td>{{$bono_salud->tipo_cambio_usd}}</td>
			<td>{{$bono_salud->costo_sk}}</td>
			<td>{{$bono_salud->saldo_final}}</td>
			<td>{{$bono_salud->destino}}</td>								
		</tr>								
	</table>
	<br>
	<br>
	<br>

	@include('bono_salud.pdf_firma_colega')

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
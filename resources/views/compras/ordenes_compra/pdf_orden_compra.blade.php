<style type="text/css">
	body{
		font-family: 'Helvetica';
	}

	table{
		border-collapse: collapse;
	}

	.titulo_td{
		border: 2px solid #000;
		padding: 5px;
		font-size: 12px;
	}
</style>

<body>
	@include('compras.ordenes_compra.pdf_encabezado')

	<div style="padding:2px;">
		<h6>
			OFICINA: {{$ordencompra->oficina->oficina}}<br>
			PAÍS: {{$ordencompra->oficina->pais->pais}}<br>
			DIRECCIÓN: {{$ordencompra->oficina->direccion}}<br>
			TELÉFONO: {{$ordencompra->oficina->telf}}
		<br>
		<br>
		FECHA: {{date('d-m-Y', strtotime($ordencompra->fecha))}}<br>
		ORDEN DE COMPRA: {{$ordencompra->correlativo}}</h6> 
	</div>
	<div style="text-align: center;margin-top: 30px;">
		<b>SOLICITUD DE ORDEN DE COMPRA</b>
	</div>
	<br>
	<br>
	<div style="text-align: justify;">
		<b>SOLICITANTE:</b> {{$ordencompra->solicitante->first_name}} {{$ordencompra->solicitante->last_name}}<br>
		<b>PROVEEDOR:</b> {{$ordencompra->proveedor_id != 0 ? $ordencompra->proveedor->razon_social : $ordencompra->proveedorUser->first_name. ' ' .$ordencompra->proveedorUser->last_name}}<br>
		<b>DESCRIPCION:</b> {{$ordencompra->descripcion}}<br>
		<br>
		<b>FACTURAS:</b> 
		@if($ordencompra->actividad_id != null) 
			@php $facturas = $ordencompra->actividad->detalle @endphp 
		@else 
			@php $facturas = $ordencompra->decision->actividad->detalle @endphp 
		@endif
		<table style="width: 100%;">
			<thead>
				<tr>
					<th class="titulo_td" style="width:50%; text-align: center;">Proyecto</th>
					<th class="titulo_td" style="width:50%; text-align: center;">Cuenta</th>
					<th class="titulo_td" style="width:50%; text-align: center;">Factura</th>
					<th class="titulo_td" style="width:50%; text-align: center;">Monto</th>
				</tr>
			</thead>
			<tbody>
				@foreach($facturas as $factura)
					<tr>
						<td class="titulo_td" align="center">
							[{{$factura->proyecto->nombre}}] {{$factura->proyecto->descripcion}}
						</td>
						<td class="titulo_td" align="center">
							[{{$factura->cuenta->nombre}}] {{$factura->cuenta->descripcion}}
						</td>
						<td class="titulo_td" align="center">
							{{$factura->factura}}
						</td>
						<td class="titulo_td" align="center">
							{{$factura->monto}}
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		<br>
		<b>TOTALES:</b><br>
		<table style="width: 100%;">
			<thead>
				<tr>
					<th class="titulo_td" style="width:50%; text-align: center;">MONTO TOTAL</th>
					<th class="titulo_td" style="width:50%; text-align: center;">MONTO USD</th>
					<th class="titulo_td" style="width:50%; text-align: center;">MONTO SK</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="titulo_td" align="center">{{$ordencompra->monto}}</td>
					<td class="titulo_td" align="center">{{$ordencompra->monto_usd}}</td>
					<td class="titulo_td" align="center">{{$ordencompra->monto_sk}}</td>
				</tr>
			</tbody>
		</table>
		<br>
		<br>
		@if($ordencompra->pago != '' && $ordencompra->pago->revision == 1)
			<table style="width: 100%;">
				<thead>
					<tr>
						<th>{{$ordencompra->solicitante->first_name}} {{$ordencompra->solicitante->last_name}}</th>
						<th>{{$ordencompra->pago->aprobador->first_name}} {{$ordencompra->pago->aprobador->last_name}}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<img src="{{url('/upload/users/'.$ordencompra->solicitante->firma)}}" style="width:100px"><br>

							________________________<br></td>
						<td>
							<img src="{{url('/upload/users/'.$ordencompra->pago->aprobador->firma)}}" style="width:100px"><br>

							________________________<br>
						</td>
					</tr>
				</tbody>
			</table>
		@else
			@if($ordencompra->contrato != '')
				@if($ordencompra->contrato->pago != '' && $ordencompra->contrato->pago->revision == 1)
					<table style="width: 100%;">
						<thead>
							<tr>
								<th>{{$ordencompra->solicitante->first_name}} {{$ordencompra->solicitante->last_name}}</th>
								<th>{{$ordencompra->contrato->pago->aprobador->first_name}} {{$ordencompra->contrato->pago->aprobador->last_name}}</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<img src="{{url('/upload/users/'.$ordencompra->solicitante->firma)}}" style="width:100px"><br>

									________________________<br>
								</td>
								<td>
									<img src="{{url('/upload/users/'.$ordencompra->contrato->pago->aprobador->firma)}}" style="width:100px"><br>

									________________________<br>
								</td>
							</tr>
						</tbody>
					</table>
				@endif
			@endif
		@endif
		{{--<table>
			<thead>
				<tr>
					<th>{{$ordencompra->solicitante->first_name}} {{$ordencompra->solicitante->last_name}}</th>
					<th>{{$ordencompra->pago != '' ? $ordencompra->pago->aprobador->first_name : $ordencompra->contrato->pago->aprobador->first_name}} {{$ordencompra->pago != '' ? $ordencompra->pago->aprobador->last_name : $ordencompra->contrato->pago->aprobador->last_name}}</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<img src="{{url('/upload/users/'.$ordencompra->solicitante->firma)}}" style="width:200px"><br>

						________________________<br></td>
					<td>
						<img src="{{url('/upload/users/'.$ordencompra->pago != '' ? $ordencompra->pago->aprobador->firma : $ordencompra->contrato->pago->aprobador->firma)}}" style="width:200px"><br>

						________________________<br>
					</td>
				</tr>
			</tbody>
		</table>--}}
		{{--<b>PRIMERA APROBACION DADA POR:</b> {{$ordencompra->aprobador_1->first_name}} {{$ordencompra->aprobador_1->last_name}}<br>
		<b>SEGUNDA APROBACION DADA POR:</b> {{$ordencompra->aprobador_2->first_name}} {{$ordencompra->aprobador_2->last_name}}<br>--}}
	</div>
</body>
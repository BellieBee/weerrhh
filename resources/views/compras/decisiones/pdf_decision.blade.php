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
	@include('compras.decisiones.pdf_encabezado')

	<div style="padding:2px;">
		<h6>
			OFICINA: {{$oficina->oficina}}<br>
			PAÍS: {{$oficina->pais->pais}}<br>DIRECCIÓN: {{$oficina->direccion}}<br>
			TELÉFONO: {{$oficina->telf}}
		<br>
		<br>
		FECHA: {{date('d-m-Y', strtotime($decision->fecha))}}<br>
		DECISIÓN: {{$decision->correlativo}}</h6> 
	</div>
	<div style="text-align: center;margin-top: 30px;">
		<b>SOLICITUD DE DECISIÓN</b>
	</div>
	<br>
	<table style="width: 100%;">
		<thead>
			<tr>
				<th class="titulo_td" style="width:50%; text-align: left;">
					<b>Realizado por</b><br>
					{{$decision->actividad->admin->first_name}} {{$decision->actividad->admin->last_name}}
				</th>
				<th class="titulo_td" style="text-align: left;">
					<b>Responsable para la Implementación y Seguimiento</b><br>
					{{$coord->first_name}} {{$coord->last_name}}
				</th>
			</tr>
			<tr>
				{{--<th class="titulo_td" style="text-align: left;">
					<b>Aprobado por</b><br>
					{{$decision->aprobador_1->first_name}} {{$decision->aprobador_1->last_name}}<br>
					{{$decision->aprobador_2->first_name}} {{$decision->aprobador_2->last_name}}
				</th>--}}
				<th class="titulo_td" style="text-align: left;" colspan="2">
					<b>Informar a</b><br>
					{{$decision->proveedor_id != 0 ? $decision->proveedor->razon_social : $decision->proveedorUser->first_name. ' ' .$decision->proveedorUser->last_name}}
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="2" class="titulo_td">
					<b>ANTECEDENTES:</b><br>
					{{$decision->antecedentes}}
					<br>
					<br>
					<b>Ofertas recibidas:</b>
					<center>
						<table>
							<thead>
								<tr>
									<th class="titulo_td"><b>Proveedor</b></th>
									<th class="titulo_td"><b>Valor Local</b></th>
									<th class="titulo_td"><b>Valor USD</b></th>
									<th class="titulo_td"><b>Valor SK</b></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="titulo_td">
										{{$decision->proveedor_id != 0 ? $decision->proveedor->razon_social : $decision->proveedorUser->first_name. ' ' .$decision->proveedorUser->last_name}}
									</td>
									<td class="titulo_td" align="center">{{$decision->actividad->monto}}</td>
									<td class="titulo_td" align="center">
										{{$decision->actividad->monto_usd}}
									</td>
									<td class="titulo_td" align="center">
										{{$decision->actividad->monto_sk}}
									</td>
								</tr>
							</tbody>
						</table>
					</center>
					<br>
					<br>
					<b>Decisión:</b><br>
					{{$decision->decision}}
					<br>
					<br>
					<b>Justificación:</b><br>
					{{$decision->justificacion}}
					<br>
					<br>
					<br>
				</td>
			</tr>
			<tr>
				<td class="titulo_td">
					{{$dir->first_name}} {{$dir->last_name}}<br>
					<b>Directora Regional</b>
				</td>
				<td class="titulo_td">
					{{$dir_fin->first_name}} {{$dir_fin->last_name}}<br>
					<b>Director Financiero</b>
				</td>
			</tr>
		</tbody>
	</table>
	<br>
	<br>
	@if ($decision->aprobacion_1 == 1 && $decision->aprobacion_2 == 1)
		<table style="width: 100%;">
			<thead>
				<tr>
					<th>{{$decision->aprobador_1->first_name}} {{$decision->aprobador_1->last_name}}</th>
					<th>{{$decision->aprobador_2->first_name}} {{$decision->aprobador_2->last_name}}</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<img src="{{url('/upload/users/'.$decision->aprobador_1->firma)}}" style="width:100px"><br>

						________________________<br></td>
					<td>
						<img src="{{url('/upload/users/'.$decision->aprobador_2->firma)}}" style="width:100px"><br>

						________________________<br>
					</td>
				</tr>
			</tbody>
		</table>
	@endif
</body>
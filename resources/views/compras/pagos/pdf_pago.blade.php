<style type="text/css">
	body {
		font-family: 'Helvetica';
        font-size: 15px;
	}

	table {
		border-collapse: collapse;
	}

    .correlativo {
        margin-left: 75%
    }

	.titulo_td {
		border: 2px solid #000;
		padding: 5px;
		font-size: 12px;
	}
</style>

<body>
    @if($pago->actividad_id != null) 
		@php $facturas = $pago->actividad->detalle @endphp 
	@elseif($pago->orden_compra_id != null) 
		@php $facturas = $pago->ordencompra->actividad->detalle @endphp 
	@else 
		@php $facturas = $pago->contrato->ordencompra->decision->actividad->detalle @endphp 
	@endif

    <div class="correlativo">
        @if ($pago->actividad_id != null)
            {{$pago->actividad->correlativo}}
        @elseif ($pago->orden_compra_id != null)
            {{$pago->ordencompra->actividad->correlativo}}
        @else
            {{$pago->contrato->ordencompra->decision->actividad->correlativo}}
        @endif
    </div>

    <div style="padding:2px;">
        <h5>
            <b>
                WE EFFECT<br>
                PAIS: {{$pago->oficina->pais->pais}}<br>
            </b>
        </h5>
    </div>
    <br>
    <center><b>SOLICITUD DE EMISION DE CHEQUE</b></center>
    <br>
    <br>
    <div>
        Favor emitir cheque a nombre:
        @if($pago->proveedor_id == 0)
            <b>{{$pago->proveedorUser->first_name}} {{$pago->proveedorUser->last_name}}</b><br>
        @else
            <b>{{$pago->proveedor->razon_social}}</b><br>
        @endif
        Por un monto de: <b>{{$pago->monto_total}} 
            @if ($pago->actividad_id != null) 
                {{$pago->actividad->tipo_moneda_id != 0 ? $pago->actividad->tipo_moneda->moneda_nombre : ''}}
            @elseif ($pago->orden_compra_id != null)
                @if ($pago->ordencompra->actividad_id != null)
                        {{$pago->ordencompra->actividad->tipo_moneda_id != 0 ? $pago->ordencompra->actividad->tipo_moneda->moneda_nombre : ''}}
                @else
                    {{$pago->ordencompra->decision->actividad->tipo_moneda_id != 0 ? $pago->ordencompra->decision->actividad->tipo_moneda->moneda_nombre : ''}}
                @endif
             @else
                {{$pago->contrato->ordencompra->decision->actividad->tipo_moneda_id != 0 ? $pago->contrato->ordencompra->decision->actividad->tipo_moneda->moneda_nombre : ''}}
            @endif</b><br>
    </div>
    <br>
    <table>
        <thead>
            <tr>
                <th class="titulo_td">CONCEPTO:</th>
                <th class="titulo_td" colspan="5">{{$pago->concepto}}</th>
            </tr>
            <tr>
                <th class="titulo_td" colspan="3" align="left">Pago a través de: {{$pago->tipopago->nombre}}</th>
                <td class="titulo_td">
                    @if($pago->proveedor_id == 0)
                        {{$pago->proveedorUser->cuenta}}
                    @else
                        {{$pago->proveedor->n_cuenta_bancaria}}
                    @endif
                </td>
                <td class="titulo_td">
                    @if($pago->proveedor_id == 0)
				        {{$pago->proveedorUser->tipocuenta->nombre}}
			        @else
				        {{$pago->proveedor->tipo_cuenta}}
			        @endif
                </td>
                <td class="titulo_td">
                    @if($pago->proveedor_id == 0)
				        {{$pago->proveedorUser->banco}}
			        @else
				        {{$pago->proveedor->banco}}
			        @endif
                </td>
            </tr>
            <tr>
                <th class="titulo_td" colspan="6" align="left">REQUERIMIENTO DE PAGO</th>
            </tr>
            <tr>
                <th class="titulo_td">PROYECTOS:</th>
                <td class="titulo_td" align="center" colspan="5">
                    @foreach ($facturas as $key => $factura)
                        @if ($key == 0 || ($key != 0 && $factura->proyecto_id != $facturas[$key-1]['proyecto_id']))
                            [{{$factura->proyecto->nombre}}] {{$factura->proyecto->descripcion}} <br>    
                        @endif
                    @endforeach
                </td>
            </tr>
            <tr>
                <td class="titulo_td">Código Contable</td>
                <td class="titulo_td">CENTRO DE COSTO</td>
                <td class="titulo_td">LÍNEA PRESUPUESTARIA</td>
                <td class="titulo_td">Detalle</td>
                <td class="titulo_td">Débito</td>
                <td class="titulo_td">Crédito</td>
            </tr>
            @if (count($facturas) != 0)
                @foreach ($facturas as $key => $factura)
                    <tr>
                        @if ($key == 0)
                            <td class="titulo_td" rowspan="{{count($facturas) + 1}}"></td>
                        @endif
                        <td class="titulo_td">[{{$factura->centroCosto->codigo}}] {{$factura->centroCosto->descripcion}}</td>
                        <td class="titulo_td">[{{$factura->lineaPresupuestaria->codigo}}] {{$factura->lineaPresupuestaria->descripcion}}</td>
                        @if ($key == 0)
                            <td class="titulo_td" rowspan="{{count($facturas)}}">
                                {{$pago->concepto}}
                            </td>
                        @endif
                        <td class="titulo_td"></td>
                        <td class="titulo_td" align="right">{{$factura->monto}}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="titulo_td" colspan="6"></td>
                </tr>
            @endif
            <tr>
                <td class="titulo_td" colspan="{{count($facturas) != 0 ? 4 : 5}}"></td>
                <td class="titulo_td" align="right"><b>{{$pago->oficina->pais->moneda_simbolo}}</b> {{$pago->monto_total}}</td>
            </tr>
            <tr>
                <td class="titulo_td">
                    Elaborada por: <img src="url('/upload/users/'.$pago->tramitante->firma)}}" style="width:100px"><br>
                    {{$pago->tramitante->first_name}} {{$pago->tramitante->last_name}}
                </td>
                <td class="titulo_td"></td>
                <td class="titulo_td" colspan="4"></td>
            </tr>
            <tr>
                <td class="titulo_td">
                    Solicitado por: <img src="url('/upload/users/'.$pago->user->firma)}}" style="width:100px"><br>
                    {{$pago->user->first_name}} {{$pago->user->last_name}}
                </td>
                <td class="titulo_td">
                    FECHA DE LA SOLICITUD: <br>
                    {{date('d-m-Y', strtotime($pago->fecha))}}
                </td>
                <td class="titulo_td">
                    FIRMA DE RESPONSABLE DEL PROYECTO:
                </td>
                <td class="titulo_td" colspan="3"></td>
            </tr>
            <tr>
                <td class="titulo_td" colspan="6"></td>
            </tr>
            <tr>
                <td class="titulo_td"><b>Observación:</b></td>
                <td class="titulo_td" colspan="5"></td>
            </tr>
        </thead>
    </table>
</body>
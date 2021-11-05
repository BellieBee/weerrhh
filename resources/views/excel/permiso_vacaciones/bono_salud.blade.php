<table border="1" >
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr>
        <td colspan="6">NOMBRE DE QUIEN LO EXPIDE: {{auth()->user()->first_name}} {{auth()->user()->last_name}}</td>
    </tr>
    <tr>
        <td colspan="6">FECHA: {{date("Y-m-d H:i:s")}}</td>
    </tr>
    <tr>
        <td colspan="6">Rango de fechas: {{$reporte->fecha_inicio}} - {{$reporte->fecha_fin}}</td>
    </tr>
    <tr>
        <td colspan="6">Oficinas: {{implode(' - ', $oficinas->pluck('oficina')->toArray())}}</td>
    </tr>
</table>
@foreach($oficinas as $oficina)
	@if(count($reporte->where('oficina_id', $oficina->id)))
		<h2>{{$oficina->oficina}}</h2>
		<table border="1" style="font-family: 'Helvetica'">
            <tr><td></td></tr>
            <tr>
                <th colspan="4" height="25" align="center" style="text-transform: uppercase;">
                    REPORTE DE ACTIVIDAD DE SALUD
                </th>
            </tr>
            <tr><td></td></tr>
			<tr>
				<th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Oficina</th>
				<th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Fecha de solicitud</th>
				<th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Colega</th>					
				<th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Destino</th>					
				<th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Saldo Inicial</th>
                <th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Costo Moneda Local</th>
                <th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Tipo Cambio SK</th>	
                <th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Tipo Cambio USD</th>
                <th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Costo SK</th>
                <th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Saldo Final</th>				
			</tr>				
			@foreach($reporte->where('oficina_id', $oficina->id)->sortBy('created_at') as $bono)				
				<tr>
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
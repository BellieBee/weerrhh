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
                    REPORTE DE VIAJES
                </th>
            </tr>
            <tr><td></td></tr>
			<tr>
				<th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Oficina</th>
				<th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Fecha de solicitud</th>
				<th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Tiempo del Viaje</th>					
				<th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Dias solicitados</th>					
				<th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Colega</th>
                <th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Destino</th>
                <th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Comentarios</th>				
			</tr>				
			@foreach($reporte->where('oficina_id', $oficina->id)->sortBy('created_at') as $viajes)				
				<tr>
				    <td>{{$oficina->oficina}}</td>
					<td>{{$viajes->created_at->format('d-m-Y')}}</td>
					<td>{{$viajes->num_dh}} {{$viajes->dh}}</td>	
					<td>{{$viajes->fechas}}</td>
					<td>{{$viajes->user->first_name}} {{$viajes->user->last_name}}</td>
                    <td>{{$viajes->destino}}</td>	
                    <td>{{$viajes->comentarios}}</td>					
				</tr>		
			@endforeach
		</table>
	@endif	
@endforeach
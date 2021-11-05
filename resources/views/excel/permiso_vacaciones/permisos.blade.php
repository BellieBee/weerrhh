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
                    REPORTE DE PERMISOS
                </th>
            </tr>
            <tr><td></td></tr>
			<tr>
				<th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Oficina</th>
				<th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Fecha de creación</th>
				<th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Tiempo de Permiso</th>					
				<th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Colega</th>					
				<th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Tipo de Permiso</th>
                <th align="center" style="text-transform: uppercase; background: #e0e0e0 ;">Motivo</th>				
			</tr>				
			@foreach($reporte->where('oficina_id', $oficina->id)->sortBy('created_at') as $permiso)				
				<tr>
				    <td>{{$oficina->oficina}}</td>
					<td>{{$permiso->created_at->format('d-m-Y')}}</td>
					<td>{{$permiso->num_dh}} {{$permiso->dh}}</td>	
					<td>{{$permiso->user->first_name}} {{$permiso->user->last_name}}</td>
					<td>{{$permiso->tipo}}</td>
                    <td>{{$permiso->motivo}}</td>						
				</tr>		
			@endforeach
		</table>
	@endif	
@endforeach
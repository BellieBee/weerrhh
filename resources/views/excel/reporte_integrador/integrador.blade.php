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
<table>
    @foreach($oficinas as $oficina)
        @if(count($feriados))
            <tr>
                <td colspan="6" height="30" align="center" style="text-transform: uppercase;">
                    <b>{{ $oficina->oficina }}</b>
                </td>
            </tr>
            <tr><td></td></tr>
            <tr>
                <td colspan="4" height="25" align="center" style="text-transform: uppercase;">
                    FERIADOS
                </td>
            </tr>
            <tr>
                <th>Dia</th>
                <th>Mes</th>
                <th>Pais</th>
                <th>Feriado</th>			
			</tr>
            @foreach($feriados->where('pais_id', $oficina->pais_id) as $feriado)			
                <tr>
                    <td>{{$feriado->dia}}</td>
                    <td>{{$feriado->month_id}}</td>
                    <td>{{$oficina->pais->pais}}</td>
                    <td>{{$feriado->descripcion_feriado}}</td>				
                </tr>
			@endforeach
        @endif
        <tr><td></td></tr>
	    <tr><td></td></tr>
        @if(count($reporte))
            <tr>
                <td colspan="6" height="25" align="center" style="text-transform: uppercase;">
                    PERMISOS
                </td>
            </tr>
            <tr>
                <th>Oficina</th>
                <th>Fechade creacion</th>
                <th>Tiempo de Permiso</th>
                <th>Colega</th>
                <th>Tipo de permiso</th>
                <th>Motivo</th>				
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
        @endif
        <tr><td></td></tr>
	    <tr><td></td></tr>
        @if(count($vacaciones))
            <tr>
                <td colspan="6" height="25" align="center" style="text-transform: uppercase;">
                    VACACIONES
                </td>
            </tr>
            <tr>
                <th>Oficina</th>
                <th>Fecha de solicitud</th>
                <th>Tiempo de vacaciones</th>					
                <th>Dias solicitados</th>					
                <th>Colega</th>
                <th>Tiempo acumulado</th>           
            </tr>	
            @foreach($vacaciones->where('oficina_id', $oficina->id)->sortBy('created_at') as $vaca)					
                <tr>
                    <td>{{$oficina->oficina}}</td>
                    <td>{{$vaca->created_at->format('d-m-Y')}}</td>
                    <td>{{$vaca->num_dh}} {{$vaca->dh}}</td>
                    <td>@foreach(explode(',', $vaca->fechas) as $fecha)
                        {{$fecha}}<br>
                        @endforeach
                    </td>
                    <td>{{$vaca->user->first_name}} {{$vaca->user->last_name}}</td>
                    <td>{{($vaca->user->acumulado_vacaciones+count($vaca->user->planilla) )*$oficina->pais->vacaciones}}</td>					
                </tr>
            @endforeach
        @endif
        <tr><td></td></tr>
	    <tr><td></td></tr>
        @if(count($viajes))
            <tr>
                <td colspan="7" height="25" align="center" style="text-transform: uppercase;">
                    VIAJES
                </td>
            </tr>
            <tr>
                <th>Oficina</th>
                <th>Fecha de solicitud</th>
                <th>Tiempo del viaje</th>					
                <th>Dias solicitados</th>					
                <th>Colega</th>
                <th>Destino</th>
                <th>Comentarios</th>         
            </tr>
            @foreach($viajes->where('oficina_id', $oficina->id)->sortBy('created_at') as $viaje)	
                <tr>
                    <td>{{$oficina->oficina}}</td>
                    <td>{{$viaje->created_at->format('d-m-Y')}}</td>
                    <td>{{$viaje->num_dh}} {{$viaje->dh}}</td>
                    <td>@foreach(explode(',', $viaje->fechas) as $fecha)
                        {{$fecha}}<br>
                        @endforeach
                    </td>
                    <td>{{$viaje->user->first_name}} {{$viaje->user->last_name}}</td>
                    <td>{{$viaje->destino}}</td>
                    <td>{{$viaje->comentarios}}</td>
                </tr>
            @endforeach	 
        @endif
        <tr><td></td></tr>
	    <tr><td></td></tr>
    @endforeach
</table>
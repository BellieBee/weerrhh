<body>
	@include('reportes.integrador.pdf_encabezado')
		<div style="text-align: center;margin-top: 30px;"><b>REPORTE INTEGRADOR</b></div>
        <br>
	<br>
	@foreach($oficinas as $oficina)
		
		@if(count($reporte->where('oficina_id', $oficina->id)))
			<h2>{{$oficina->oficina}}</h2>
            <div style="text-align: rigth;margin-top: 30px;"><b>FERIADOS</b></div>
			<table border="1"  class="datos_em" style="page-break-after:always;">
				<tr>
					<th >Dia</th>
					<th >Mes</th>
					<th >Pais</th>
					<th >Feriado</th>			
				</tr>
				@foreach($feriados->where('pais_id', $oficina->pais_id) as $feriado)			
					<tr class="empleados">
						<td>{{$feriado->dia}}</td>
						<td>{{$feriado->month_id}}</td>
						<td>{{$oficina->pais->pais}}</td>
						<td>{{$feriado->descripcion_feriado}}</td>				
					</tr>
				@endforeach
			</table>

            <div style="text-align: rigth;margin-top: 30px;"><b>PERMISOS</b></div>
			<table border="1"  class="datos_em" style="page-break-after:always;">
				<tr>
					<th >Oficina</th>
					<th >Fechade creacion</th>
					<th >Tiempo de Permiso</th>
					
					<th >Colega</th>
					<th >Tipo de permiso</th>
					<th >Motivo</th>				
				</tr>
				@foreach($reporte->where('oficina_id', $oficina->id)->sortBy('created_at') as $permiso)			
					<tr class="empleados">
						<td>{{$oficina->oficina}}</td>
						<td>{{$permiso->created_at->format('d-m-Y')}}</td>
						<td>{{$permiso->num_dh}} {{$permiso->dh}}</td>
						<td>{{$permiso->user->first_name}} {{$permiso->user->last_name}}</td>
						<td>{{$permiso->tipo}}</td>
						<td>{{$permiso->motivo}}</td>					
					</tr>
				@endforeach
			</table>

            <div style="text-align: rigth;margin-top: 30px;"><b>VACACIONES</b></div>
			<table border="1"  class="datos_em" style="page-break-after:always;">
				<tr>
					<th >Oficina</th>
					<th >Fechade solicitud</th>
					<th >Tiempo de vacaciones</th>					
					<th >Dias solicitados</th>					
					<th >Colega</th>
					<th >Tiempo acumulado</th>
								
				</tr>				
				@foreach($vacaciones->where('oficina_id', $oficina->id)->sortBy('created_at') as $vaca)			
					
					<tr class="empleados">
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
			</table>

            <div style="text-align: rigth;margin-top: 30px;"><b>VIAJES</b></div>
            <table border="1"  class="datos_em" style="page-break-after:always;">
				<tr>
					<th >Oficina</th>
					<th >Fecha de solicitud</th>
					<th >Tiempo del viaje</th>					
					<th >Dias solicitados</th>					
					<th >Colega</th>
					<th>Destino</th>
					<th>Comentarios</th>
								
				</tr>				
				@foreach($viajes->where('oficina_id', $oficina->id)->sortBy('created_at') as $viaje)			
					
					<tr class="empleados">
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
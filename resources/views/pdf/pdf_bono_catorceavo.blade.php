
@include('pdf.pdf_encabezado')
<h2>{{$pais->id == 1 ? 'LEY DE BONIFICACION ANUAL PARA LOS TRABAJADORES (DECRETO 42-92)' : 'Planilla Bono Catorceavo'}}</h2>
<table class="datos_em" style="page-break-after:always;" >
	<tr class="titulo_tr" >
		<td style="width: 20px;"  >Nº</td>
		<td style="width: 130px;" >Nombres y Apellidos</td>	
		@foreach($meses_catorceavo as $mes)
			<td>{{$mes}}</td>
		@endforeach
		<td>TOTAL BONO CATORCEAVO</td>
	</tr>
		
	@foreach($planilla->empleados as $key => $empleado)
		<tr class="empleados" >
			<td>{{$empleado->id}}</td>
			<td>{{$empleado->nombre}}</td>
				@foreach($acumulado_empleados[$empleado->user_id] as $acumulado)
					<td>{{$acumulado}}</td>
				@endforeach
			<td>{{number_format($empleado->aporte->bonificacion_14,2)}}</td>
		</tr>
	@endforeach
		
		<tr class="empleados totales">
			<td colspan="2"  style="text-align: right;"> TOTALES</td>
				@foreach($acumulado_meses as $acumulado_mes)
					<td>{{$acumulado_mes}}</td>
				@endforeach

			<td>{{number_format($planilla->aportes->sum('bonificacion_14'),2)}}</td>
		</tr> 
</table>

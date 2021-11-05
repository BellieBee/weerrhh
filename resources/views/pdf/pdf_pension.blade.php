@include('pdf.pdf_encabezado')
<h2>Pensión</h2>
<table class="datos_em" style="page-break-after:always;" >
	<tr class="titulo_tr" >
		<td style="width: 20px;"  >Nº</td>
		<td style="width: 130px;" >Nombres y Apellidos</td>		
		<td rowspan="1" width="11">{{$campo->total_salario}}</td>
		@foreach($meses as $mes)
			<td>{{$mes->month}}-{{$year}}</td>
		@endforeach
		<td rowspan="1" width="22">TOTAL PENSIÓN</td>
	</tr>

	@foreach($planilla->empleados as $empleado)

		<tr class="empleados" >
			<td>{{$empleado->id}}</td>
			<td>{{$empleado->nombre}}</td>
			<td>{{number_format($empleado->total_salario,2)}}</td>
			@foreach($pension_meses[$empleado->user_id] as $pension)
				<td>{{$pension}}</td>
			@endforeach
			<td>{{number_format($empleado->total_pension,2)}}</td>		
		</tr>
	@endforeach

	<tr class="empleados totales">
		<td colspan="2" style="text-align:right;"> TOTALES</td>
		<td>{{number_format($planilla->empleados->sum('salario_base'),2)}}</td>
		@foreach($total_meses_pension as $total)
			<td>{{$total}}</td>
		@endforeach
		<td>{{number_format($planilla->empleados->sum('total_pension'),2)}}</td>
	</tr>
</table>		


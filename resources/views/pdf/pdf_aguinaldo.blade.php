@include('pdf.pdf_encabezado')
<h2>Planilla Aguinaldos</h2>
<table class="datos_em" style="page-break-after:always;" >
	<tr class="titulo_tr" >
		<td style="width: 20px;"  >Nº</td>
		<td style="width: 130px;" >Nombres y Apellidos</td>	
		@foreach($meses as $mes)
			<td>{{$mes->month}}-{{$year}}</td>
		@endforeach
		<td >TOTAL AGUINALDOS</td>
	</tr>
		
	@foreach($planilla->empleados as $key => $empleado)
		<tr class="empleados" >
			<td>{{$empleado->id}}</td>
			<td>{{$empleado->nombre}}</td>
			@foreach($aguinaldo_meses[$empleado->user_id] as $aguinaldo)
				<td>{{$aguinaldo}}</td>
			@endforeach
			<td>{{number_format($empleado->total_aguinaldo,2)}}</td>
		</tr>
	@endforeach
		
	<tr class="empleados totales">
		<td colspan="2"  style="text-align: right;"> TOTALES</td>
		@foreach($total_meses_aguinaldo as $total)
			<td>{{$total}}</td>
		@endforeach	
		<td>{{number_format($planilla->empleados->sum('total_aguinaldo'),2)}}</td>
	</tr>
</table>

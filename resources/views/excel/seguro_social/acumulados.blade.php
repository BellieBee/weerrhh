<table>
		<tr>
			<td  colspan="2"></td>			
			<td class="titulo_td" align="center" colspan="{{$planilla->cell_acumulados}}">
				ACUMULADOS
			</td>		

		</tr>
		<tr class="titulo_tr" >
			<td rowspan="1" width="4">Nº</td>
			<td rowspan="1" width="22">Nombres y Apellidos</td>							

			<td rowspan="1" width="11">{{$pais->campo->acumulado_aguinaldo}}</td>
			<td rowspan="1" width="20">{{$pais->campo->acumulado_indemnizacion}}</td>				
		</tr>		

		@foreach($planilla->empleados as $empleado)
		<tr class="din empleados" >
			<td>{{$empleado->id}}</td>
			<td>{{$empleado->nombre}}</td>

			<td>{{number_format($empleado->acumulado->aguinaldo,2)}}</td>
			<td>{{number_format($empleado->acumulado->indemnizacion,2)}}</td>	
	
		</tr>
		@endforeach

		<tr class="din">
			<td  colspan="2"><b>TOTAL</b></td>						

			<td>{{number_format($planilla->acumulados->sum('aguinaldo'),2)}}</td>
			<td>{{number_format($planilla->acumulados->sum('indemnizacion'),2)}}</td>
		</tr>
	</table>
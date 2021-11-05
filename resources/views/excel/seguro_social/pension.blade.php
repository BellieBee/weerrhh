<table >
<tr>
        <td  colspan="2"></td>			
        <td class="titulo_td" height="25" align="center" colspan="2">
		PENSIÓN
        </td>		
        
    </tr>
<tr class="titulo_tr" >
			<td rowspan="1" width="4" >Nº</td>
			<td rowspan="1" width="22" >APELLIDOS y NOMBRES</td>
			<td rowspan="1" width="11">{{$campo->total_salario}}</td>
			<td rowspan="1" width="22">TOTAL PENSIÓN</td>
		</tr>
		
		@foreach($planilla->empleados as $empleado)
		<tr class="din empleado" >
			<td>{{$empleado->id}}</td>
			<td>{{$empleado->nombre}}</td>
			<td>{{number_format($empleado->total_salario,2)}}</td>				
			<td>{{number_format($empleado->total_pension,2)}}</td>		
		</tr>
		@endforeach
		
		<tr class="din" style="font-weight: bold;">
			<td colspan="2"> TOTALES</td>
			<td>{{number_format($planilla->empleados->sum('salario_base'),2)}}</td>
			<td>{{number_format($planilla->empleados->sum('total_pension'),2)}}</td>
		</tr>		
</table>		

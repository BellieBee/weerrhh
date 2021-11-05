<table>
    <tr>
        <td  colspan="2"></td>			
        <td class="titulo_td" height="25" align="center" colspan="13">
            APROPIACIONES
        </td>		
        
    </tr>
    <tr class="titulo_tr" >
        <td width="4" >Nº</td>
        <td width="18" >Nombre y Apellidos</td>		
        
        {{--acumulados--}}
        <td align="center">{{$pais->campo->acumulado_aguinaldo}}</td>
        <td align="center">{{$pais->campo->acumulado_indemnizacion}}</td>
        <td align="center">Interes</td>
        <td align="center">Vacaciones</td>
        {{--patronales--}}
		<td align="center">Caja de Compensación {{$pais->porcentaje_parafiscales}}%</td>
		<td align="center">Arl 2.43%</td>
        <td align="center">Eps {{$pais->porcentaje_eps}}%</td>
        <td align="center">Caja Opción {{$pais->porcentaje_caja_opcion}}%</td>
        <td align="center">Icbf {{$pais->porcentaje_icbf}}%</td>
        <td align="center">Sena {{$pais->porcentaje_sena}}%</td>
		<td align="center">Salud 8.5%</td>
		<td align="center">Pension patronal 12%</td>

        <td align="center" width="20" >TOTAL APORTES PATRONALES</td>					
    </tr>		
    
    @foreach($planilla->empleados as $empleado)
    <tr class="din empleado" >
        <td>{{$empleado->id}}</td>
        <td>{{$empleado->nombre}}</td>			
        
        {{--acumulados--}}
        <td>{{number_format($empleado->acumulado->aguinaldo,2)}}</td>
        <td>{{number_format($empleado->acumulado->indemnizacion,2)}}</td>
        <td>{{number_format($empleado->acumulado->interes_col,2)}}</td>
        <td>{{number_format($empleado->acumulado->vacaciones,2)}}</td>
        {{--patronales--}}
		<td>{{number_format($empleado->aporte->parafiscales,2)}}</td>
		<td>{{number_format($empleado->aporte->arl,2)}}</td>
        <td>{{number_format($empleado->aporte->eps,2)}}</td>
        <td>{{number_format($empleado->aporte->caja_opcion,2)}}</td>
        <td>{{number_format($empleado->aporte->icbf,2)}}</td>
        <td>{{number_format($empleado->aporte->sena,2)}}</td>
		<td>{{number_format($empleado->aporte->salud_patronal,2)}}</td>
		<td>{{number_format($empleado->aporte->pension_patronal,2)}}</td>
        
        <td>{{number_format($empleado->aporte->total_carga_patronal,2)}}</td>	
    </tr>
    @endforeach
    
    
    <tr class="din" style="font-weight: bold;">
        <td  colspan="2" style="text-align: right;"><b>TOTAL</b></td>						
        {{--acumulados--}}
        <td>{{number_format($planilla->acumulados->sum('aguinaldo'),2)}}</td>
        <td>{{number_format($planilla->acumulados->sum('indemnizacion'),2)}}</td>
        <td>{{number_format($planilla->acumulados->sum('interes_col'),2)}}</td>
        <td>{{number_format($planilla->acumulados->sum('vacaciones'),2)}}</td>
        {{--patronales--}}
		<td>{{number_format($planilla->aportes->sum('parafiscales'),2)}}</td>
		<td>{{number_format($planilla->aportes->sum('arl'),2)}}</td>
        <td>{{number_format($planilla->aportes->sum('eps'),2)}}</td>
        <td>{{number_format($planilla->aportes->sum('caja_opcion'),2)}}</td>
        <td>{{number_format($planilla->aportes->sum('icbf'),2)}}</td>
        <td>{{number_format($planilla->aportes->sum('sena'),2)}}</td>
		<td>{{number_format($planilla->aportes->sum('salud_patronal'),2)}}</td>
		<td>{{number_format($planilla->aportes->sum('pension_patronal'),2)}}</td>
        @php
            $total = 0;
            foreach($planillas as $p){
                $total += $p->aportes->sum('total_carga_patronal');
            }
        @endphp
        <td> {{number_format($total,2)}}</td>    
    </tr>	
</table>

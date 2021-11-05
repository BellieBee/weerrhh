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
        <td colspan="6">Rango de fechas: {{$planillas->fecha_inicio}} - {{$planillas->fecha_fin}}</td>
    </tr>
    <tr>
        <td colspan="6">Oficinas: {{implode(' - ', $oficinas->pluck('oficina')->toArray())}}</td>
    </tr>
</table>
<table>
    @foreach($oficinas as $oficina)
        <tr>
            <td colspan="6" height="30" align="center" style="text-transform: uppercase;">
                <b>{{ $oficina->oficina }}</b>
            </td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <th colspan="4" height="25" align="center" style="text-transform: uppercase;">
                REPORTE DE EMPLEADOS
            </th>
        </tr>
        <tr><td></td></tr>
        <tr>
            <th colspan="4" align="center"></th>
            <th colspan="4" align="center" style="background-color: #8bc34a7d;">TOTALES</th>
            <th align="center" style="background-color: #ffeb3b7d">ARPOTES PATRONALES</th>
            <th colspan="4" align="center" style="background-color: #4bc0f757">ACUMULADOS</th>
        </tr>
        <tr>
            <th align="center">Oficina</th>
            <th align="center">Fecha</th>
            <th align="center">Colega</th>
            <th align="center">Tipo de cambio</th>
            <th align="center" style="background-color: #8bc34a7d;">Salario</th>
            <th align="center" style="background-color: #8bc34a7d;">Total aportes</th>
            <th align="center" style="background-color: #8bc34a7d;">Total deducciones</th>
            <th align="center" style="background-color: #8bc34a7d;">Liquido a recibir</th>
            <th align="center" style="background-color: #ffeb3b7d">Total aporte patronales</th>
            <th align="center" style="background-color: #4bc0f757">Aguinaldo (Junio)</th>
            <th align="center" style="background-color: #4bc0f757">Aguinaldo (Diciembre)</th>
            <th align="center" style="background-color: #4bc0f757">Fondo pension</th>
            <th align="center" style="background-color: #4bc0f757">Indemnizacion</th>
        </tr>   
        @foreach($planillas->where('oficina_id',$oficina->id)->sortBy('created_at') as $planilla)
                
            @foreach($planilla->empleados as $empleado)
                <tr>
                    <td>{{$planilla->oficina->oficina}}</td>
                    <td>{{$planilla->m_a}}</td>
                    <td>{{$empleado->nombre}}</td>
                    <td>{{$planilla->cambio}}</td>
                    <td style="background-color: #8bc34a7d;">{{number_format($empleado->total_salario*$planilla->cambio_mensual,2)}}</td>
                    <td style="background-color: #8bc34a7d;">{{number_format($empleado->aporte->total_aportes*$planilla->cambio_mensual,2)}}</td>
                    <td style="background-color: #8bc34a7d;">{{number_format($empleado->deduccion->total_deducciones*$planilla->cambio_mensual,2)}}</td>
                    <td style="background-color: #8bc34a7d;">{{number_format($empleado->liquido_recibir*$planilla->cambio_mensual,2)}}</td>
                    <td style="background-color: #ffeb3b7d">{{number_format($empleado->aporte->total_carga_patronal*$planilla->cambio_mensual,2)}}</td>
                    <td style="background-color: #4bc0f757">{{number_format($empleado->acumulado->catorceavo*$planilla->cambio_mensual,2)}}</td>
                    <td style="background-color: #4bc0f757">{{number_format($empleado->acumulado->aguinaldo*$planilla->cambio_mensual,2)}}</td>
                    <td style="background-color: #4bc0f757">{{number_format($empleado->acumulado->pension*$planilla->cambio_mensual,2)}}</td>
                    <td style="background-color: #4bc0f757">{{number_format($empleado->acumulado->indemnizacion*$planilla->cambio_mensual,2)}}</td>
                </tr>
            @endforeach
        @endforeach
    @endforeach
</table>
   
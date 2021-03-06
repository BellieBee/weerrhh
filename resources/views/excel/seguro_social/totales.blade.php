<table>
    <tr>
        <td  colspan="7"></td>			
        <td class="titulo_td" height="25" colspan="{{$planilla->cell_deducciones}}" >DEDUCCIONES</td>		
        @if($pais->id==1)<!--//GUATEMALA-->					
            <td class="titulo_td" height="25" colspan="5" >APORTES</td>			
        @elseif($pais->id == 7) <!--//COLOMBIA-->
            <td class="titulo_td" height="25" colspan="12" >APORTES</td>
        @else
            @if (str_contains($planilla->m_a, 'Junio'))
                <td class="titulo_td" height="25" colspan="2" >APORTES</td>
            @endif
        @endif	
        
    </tr>
    <tr class="titulo_tr" >
        <td width="4" >Nº</td>
        <td width="18" >Nombre y Apellidos</td>
        <td width="14">Dias Trabajado</td>
        <td width="14">{{$campo->salario_base}}</td>
        <td width="10">{{$campo->ajustes}}</td>
        @if($pais->id == 7)
            <td width="14">Intereses Cesantias</td>
        @endif
        <td width="14">{{$campo->total_salario}}</td>
        
        <!--campo deducciones-->				
        
            @if($pais->id==2)<!--//BOLIVIA-->									
                <td width="10">CTA. IND. 10%</td>
                <td width="9">RIESGO 1,71%</td>
                <td width="10">COM. AFP 0,5%</td>
                <td width="14">AFP APORTE SOLIDARIO 0,5%</td>
                <td width="14">AFP APORTE NACIONAL SOLIDARIO 1%</td>
                <td width="14">RC - IVA 13%: </td>
            
            @elseif($pais->id==3)<!--//NICARAGUA-->	
                <td width="12">Deduccion 1</td>
                <td width="12">Deduccion 2</td>
            
            @elseif($pais->id==4)<!--//HONDURAS-->
                <td width="12">Seguro_medico</td>
                <td width="12">RAP (1.5%)</td>

            @elseif($pais->id==5)<!--//PARAGUAY-->					
                

            @elseif($pais->id==6)<!--//SALVADOR-->					
                <td width="12">AFP (7.25%)</td>

            @elseif($pais->id == 7) <!--//COLOMBIA-->
				<td width="12">Salud 4%</td>
				<td width="12">FSP 1%</td>
				<td width="12">Pension 4%</td>
				<td width="12">RTE FTW UVT</td>
                <td width="12">Otros descuentos celulares</td>
			@endif

            @if( in_array('seguridad_social', explode(',', $planilla->campo_deducciones)) )
                <td>{{$campo->seguridad_social}}</td>	
            @endif
            
            @if( in_array('impuesto_renta', explode(',', $planilla->campo_deducciones)) )
                <td>{{$campo->impuestos}}</td>
            @endif
            
            @if( in_array('prestamo', explode(',', $planilla->campo_deducciones)) )
                <td>{{$campo->prestamo}}</td>
            @endif
            
            @if( in_array('interes', explode(',', $planilla->campo_deducciones)) )
                <td>{{$campo->interes}}</td>
            @endif
            
            @if( in_array('otras_deducciones', explode(',', $planilla->campo_deducciones)) )
                <td width="13">{{$campo->otras_deducciones}}</td>	
            @endif

            <td width="13">{{$campo->total_deducciones}}</td>
        <!--/campo deducciones-->

        <!--campo aportes-->
            @if($pais->id==1)<!--//GUATEMALA-->	
                <td width="12">Bonificacion incentivo</td>
                <td width="12">Bonificación Docto 37 2001</td>
                <td width="12">Reintegros</td>			
                <td width="12">Bonificación 14 (Dto 42-92)({{$planilla->catorceavo_mes}})</td>				
                <td align="center" width="11">TOTAL APORTES</td>			
            @else
                @if(str_contains($planilla->m_a, 'Junio'))
                    <td width="16">Bonificación 14 (Dto 42-92)(Junio)</td>				
                    <td align="center" width="11">TOTAL APORTES</td>
                @endif
            @endif									
        <!--/campo aportes-->
            @if($pais->id == 7)<!--//COLOMBIA-->
                <td>Auxilio de transporte</td>
                <td>Bono salud</td>
                <td>Capacitacion</td>
                <td>Seguro gastos medicos</td>
                <td>INCAP/Licencia</td>
                <td>Total Incapacidad</td>
                <td>Incapacidades</td>
                <td>Aux. Trans</td>
                <td>Aux. Celular</td>
                <td>Medios Transp.</td>
                <td>Otros Devengos</td>
                <td align="center">TOTAL APORTES</td>
            @endif

        <td width="14">{{$campo->liquido}}</td>


                
    </tr>
    
    
    @foreach($planilla->empleados as $empleado)
    <tr class="din empleado" >
        <td>{{$empleado->id}}</td>
        <td>{{$empleado->nombre}}</td>
        <!--<td>{{$empleado->cargo}}</td>
        <td>{{$empleado->documento}}</td>
        <td>{{$empleado->fecha_inicio}}</td>-->
        <td>{{$empleado->dias_trabajados}}</td>
        <td>{{number_format($empleado->salario_base,2)}}</td>
        <td>{{number_format($empleado->ajuste,2)}}</td>
        @if($pais->id == 7)
            <td>{{number_format($empleado->aporte->intereses_cesantias,2)}}</td>
        @endif
        <td>{{number_format($empleado->total_salario,2)}}</td>		
        

        <!--deducciones-->
            

            @if($pais->id==2)<!--//BOLIVIA-->						
                <td>{{number_format($empleado->deduccion->cta_ind,2)}}</td>	
                <td>{{number_format($empleado->deduccion->riesgo,2)}}</td>	
                <td>{{number_format($empleado->deduccion->com_afp,2)}}</td>	
                <td>{{number_format($empleado->deduccion->afp_aporte_solidario,2)}}</td>	
                <td>{{number_format($empleado->deduccion->afp_aporte_nacional_solidario,2)}}</td>	
                <td>{{number_format($empleado->deduccion->rc_iva,2)}}</td>	
            
            @elseif($pais->id==3)<!--//NICARAGUA-->	
                <td>{{number_format($empleado->deduccion->deduccion_1,2)}}</td>
                <td>{{number_format($empleado->deduccion->deduccion_2,2)}}</td>
            
            @elseif($pais->id==4)<!--//HONDURAS-->	
                <td>{{number_format($empleado->deduccion->seguro_medico,2)}}</td>
                <td>{{number_format($empleado->deduccion->rap,2)}}</td>
            
            @elseif($pais->id==5)<!--//PARAGUAY-->				
            
            @elseif($pais->id==6)<!--//SALVADOR-->	
                <td>{{number_format($empleado->deduccion->afp,2)}}</td>			

            @elseif($pais->id == 7)<!--//COLOMBIA-->
                <td>{{number_format($empleado->deduccion->salud,2)}}</td>	
                <td>{{number_format($empleado->deduccion->fsp,2)}}</td>	
                <td>{{number_format($empleado->deduccion->pen,2)}}</td>	
                <td>{{number_format($empleado->deduccion->rte,2)}}</td>
                <td>{{number_format($empleado->deduccion->otros_descuentos_celulares,2)}}</td>
            @endif

            @if( in_array('seguridad_social', explode(',', $planilla->campo_deducciones)) )
            <td width="11">{{number_format($empleado->deduccion->seguridad_social,2)}}</td>		
            @endif

            @if( in_array('impuesto_renta', explode(',', $planilla->campo_deducciones)) )
            <td width="11">{{number_format($empleado->deduccion->impuesto_renta,2)}}</td>
            @endif

            @if( in_array('prestamo', explode(',', $planilla->campo_deducciones)) )
            <td width="11">{{number_format($empleado->deduccion->prestamo,2)}}</td>
            @endif

            @if( in_array('interes', explode(',', $planilla->campo_deducciones)) )
            <td width="11">{{number_format($empleado->deduccion->interes,2)}}</td>
            @endif

            @if( in_array('otras_deducciones', explode(',', $planilla->campo_deducciones)) )
            <td width="11">{{number_format($empleado->deduccion->otras_deducciones,2)}}</td>		
            @endif

            <td width="11">{{number_format($empleado->deduccion->total_deducciones,2)}}</td>
        <!--/deducciones-->	
        
        <!--aportes-->
            @if($pais->id==1)<!--//GUATEMALA-->
                <td>{{number_format($empleado->aporte->bonificacion_incentivo,2)}}</td>	
                <td>{{number_format($empleado->aporte->bonificacion_docto_37_2001,2)}}</td>	
                <td>{{number_format($empleado->aporte->reintegros,2)}}</td>	
                <td>{{number_format($empleado->aporte->bonificacion_14,2)}}</td>	
                <td>{{number_format($empleado->aporte->total_aportes,2)}}</td>
            @else
                @if(str_contains($planilla->m_a, 'Junio'))
                    <td>{{number_format($empleado->aporte->bonificacion_14,2)}}</td>	
                    <td>{{number_format($empleado->aporte->total_aportes,2)}}</td>
                @endif
            @endif	

            @if($pais->id == 7)<!--//COLOMBIA-->
                <td>{{number_format($empleado->aporte->auxilio_transporte,2)}}</td>
                <td>{{number_format($empleado->aporte->bono_salud,2)}}</td>
                <td>{{number_format($empleado->aporte->capacitacion,2)}}</td>
                <td>{{number_format($empleado->aporte->seguro_medico,2)}}</td>
                <td>{{number_format($empleado->aporte->incap_licencia,2)}}</td>
                <td>{{number_format($empleado->aporte->total_incapacidad,2)}}</td>
                <td>{{number_format($empleado->aporte->incapacidades,2)}}</td>
                <td>{{number_format($empleado->aporte->aux_trans,2)}}</td>
                <td>{{number_format($empleado->aporte->aux_celular,2)}}</td>
                <td>{{number_format($empleado->aporte->medios_transp,2)}}</td>
                <td>{{number_format($empleado->aporte->otros_devengos,2)}}</td>
                <td>{{number_format($empleado->aporte->total_aportes,2)}}</td>
            @endif					
        <!--/aportes-->

        <td width="11" >{{number_format($empleado->liquido_recibir,2)}}</td>

                
    </tr>
    @endforeach
    
    <tr class="din" style="font-weight: bold;">
        <td  colspan="3" style="text-align: right;"><b>TOTAL</b></td>
        <!--total salrio base-->
        <td> {{number_format($planilla->empleados->sum('salario_base'),2)}}</td>
        <!--total Ajuste-->
        <td> {{number_format($planilla->empleados->sum('ajuste'),2)}}</td>
        <!--total Ajuste-->
        <td> {{number_format($planilla->aportes->sum('intereses_cesantias'),2)}}</td>

        <!--total de  salarios-->
        <td> {{number_format($planilla->empleados->sum('total_salario'),2)}}</td>			
        
        <!-- total deducciones-->			
            
            @if($pais->id==2)<!--//BOLIVIA-->				
                <td>{{number_format($planilla->deducciones->sum('cta_ind'),2)}}</td>
                <td>{{number_format($planilla->deducciones->sum('riesgo'),2)}}</td>
                <td>{{number_format($planilla->deducciones->sum('com_afp'),2)}}</td>
                <td>{{number_format($planilla->deducciones->sum('afp_aporte_solidario'),2)}}</td>
                <td>{{number_format($planilla->deducciones->sum('afp_aporte_nacional_solidario'),2)}}</td>
                <td>{{number_format($planilla->deducciones->sum('rc_iva'),2)}}</td>
            @elseif($pais->id==3)<!--//NICARAGUA-->					
                <td>{{number_format($planilla->deducciones->sum('deduccion_1'),2)}}</td>
                <td>{{number_format($planilla->deducciones->sum('deduccion_2'),2)}}</td>
            
            @elseif($pais->id==4)<!--//HONDURAS-->				
                <td>{{number_format($planilla->deducciones->sum('seguro_medico'),2)}}</td>
                <td>{{number_format($planilla->deducciones->sum('rap'),2)}}</td>
            
            @elseif($pais->id==5)<!--//PARAGUAY-->				
            
            @elseif($pais->id==6)<!--//SALVADOR-->				
                <td>{{number_format($planilla->deducciones->sum('afp'),2)}}</td>				
            
            @elseif($pais->id == 7)<!--//COLOMBIA-->				
                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->deducciones->sum('salud');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>
        
                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->deducciones->sum('fsp');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>
                
                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->deducciones->sum('pen');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>
            
                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->deducciones->sum('rte');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>

                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->deducciones->sum('otros_descuentos_celulares');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>
            @endif
            
            @if( in_array('seguridad_social', explode(',', $planilla->campo_deducciones)) )
                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->deducciones->sum('seguridad_social');
                    }
                @endphp
            <td> {{number_format($total,2)}}</td>
            @endif

            <!--total de deduccion impuesto_renta -->
            @if( in_array('impuesto_renta', explode(',', $planilla->campo_deducciones)) )			
                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->deducciones->sum('impuesto_renta');
                    }
                @endphp
            <td> {{number_format($total,2)}}</td>
            @endif

            <!--total de deduccion prestamo -->
            @if( in_array('prestamo', explode(',', $planilla->campo_deducciones)) )			
                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->deducciones->sum('prestamo');
                    }
                @endphp
            <td> {{number_format($total,2)}}</td>
            @endif

            <!--total de deduccion ', -->
            @if( in_array('interes', explode(',', $planilla->campo_deducciones)) )
                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->deducciones->sum('interes');
                    }
                @endphp
            <td> {{number_format($total,2)}}</td>
            @endif

            <!--total de deduccion otras_deducciones -->
            @if( in_array('otras_deducciones', explode(',', $planilla->campo_deducciones)) )			
                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->deducciones->sum('otras_deducciones');
                    }
                @endphp
            <td> {{number_format($total,2)}}</td>
            @endif
                
            @php
                $total = 0;
                foreach($planillas as $p){
                    $total += $p->deducciones->sum('total_deducciones');
                }
            @endphp                        
            <td width="11">{{number_format($total,2)}}</td>		
        <!--/ total deducciones-->	
        <!--aportes-->	
            @if($pais->id==1)<!--//GUATEMALA-->
                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('bonificacion_incentivo');
                    }
                @endphp
            <td> {{number_format($total,2)}}</td>
            
            @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('bonificacion_docto_37_2001');
                    }
                @endphp
            <td> {{number_format($total,2)}}</td>
        
            @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('reintegros');
                    }
                @endphp
            <td> {{number_format($total,2)}}</td>
                
            @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('bonificacion_14');
                    }
                @endphp
            <td> {{number_format($total,2)}}</td>
                
            @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('total_aportes');
                    }
                @endphp
            <td> {{number_format($total,2)}}</td>
            @else
                @if(str_contains($planilla->m_a, 'Junio'))
                    <td>{{number_format($planilla->aportes->sum('bonificacion_14'),2)}}</td>			
                    <td>{{number_format($planilla->aportes->sum('total_aportes'),2)}}</td>
                @endif
            @endif

            @if($pais->id == 7)<!--//COLOMBIA-->
                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('auxilio_transporte');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>
        
                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('bono_salud');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>
        
                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('capacitacion');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>
                
                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('seguro_medico');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>

                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('incap_licencia');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>

                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('total_incapacidad');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>

                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('incapacidades');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>

                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('aux_trans');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>

                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('aux_celular');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>

                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('medios_transp');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>

                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('otros_devengos');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>
                
                @php
                    $total = 0;
                    foreach($planillas as $p){
                        $total += $p->aportes->sum('total_aportes');
                    }
                @endphp
                <td>{{number_format($total,2)}}</td>
            @endif

            
        <!--/aportes-->	
        
        <td width="11" >{{number_format($planilla->empleados->sum('liquido_recibir'),2)}}</td>
        
        <!--<td width="11">{{number_format($planilla->deducciones->sum('total_deducciones'),2)}}</td>
        <td width="11">{{number_format($planilla->aportes->sum('total_aportes'),2)}}</td>
        
        
        <td width="11" >{{number_format($planilla->empleados->sum('liquido_recibir'),2)}}</td>
        <td width="11">{{number_format($planilla->aportes->sum('total_carga_patronal'),2)}}</td>-->		
    </tr>	
<tr>
    <td colspan="{{6+$planilla->cell_deducciones+$planilla->cell_aportes+1}}"></td>
</tr>

<tr>
    <td colspan="{{$planilla->cell_aportes}}"></td>
</tr>	
</table>
@extends('layouts.planilla')

@section('page-title', 'Planillas Normal')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Planilla normal 
            <small>{{ $oficina->oficina }} {{ $fecha}}</small>
            <div class="pull-right">
                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
                    <li><a href="{{ route('planilla.normal') }}">Planilla</a></li>
                    <li class="active">@lang('app.create')</li>
                </ol>
            </div>
        </h1>
    </div>
</div>

    <form class="planilla_form" action="{{url('/store/planilla')}}" method="POST">
		
		{!! csrf_field() !!}
		@if ($edit) 
			<input type="hidden" name="id_planilla" value="{{$planilla->id}}">			
		@endif 
		<input type="hidden" name="oficina_id" value="{{$oficina->id}}">
		<input type="hidden" name="porcentaje_seguridad_social" value="{{$pais->porcentaje_seguridad_social}}">
		<input type="hidden" name="nombre_seguridad_social" value="{{$pais->nombre_seguridad_social}}">
		<input type="hidden" name="porcentaje_pension" value="{{$pais->porcentaje_pension}}">
		<input type="hidden" name="nombre_renta_pais" value="{{$pais->nombre_renta_pais}}">
		<input type="hidden" name="campo_deducciones" value="{{$pais->campo_deducciones}}">
		<input type="hidden" name="administradora_id" value="{{$administradora->id}}">
		<input type="hidden" name="pais_id" value="{{$pais->id}}">
		<input type="hidden" name="pago_indemnizacion" value="{{$pais->pago_indemnizacion}}">
		<input type="hidden" name="pago_pension" value="{{$pais->pago_pension}}">
		
		@include('planillas.encabezado')
		
		<ul class="nav nav-tabs">
		  	<li class="active"><a data-toggle="tab" href="#planilla">Planilla</a></li>
		  	@if(str_contains($fecha, $pais->bono_14))
		  	    <li><a data-toggle="tab" href="#bonocatorce">Bono Catorceavo</a></li>
		  	@endif
		  	@if(str_contains($fecha, 'Diciembre'))
			  	<li><a data-toggle="tab" href="#aguinaldo">Aguinaldo</a></li>

			  	@if($pais->pago_indemnizacion=="anual" || $pais->pago_indemnizacion=="retiro")
					<li><a data-toggle="tab" href="#indemnizacion">Indemnizacion</a></li>
				@endif

	
				@if($campos[0] == "fondo_pension")
					<li><a data-toggle="tab" href="#pension">Pensi??n</a></li>
				@endif

		  	@endif
			<li><a data-toggle="tab" href="#permisos">Usuarios con Permisos</a></li>
		</ul>
		<div class="tab-content">
		  	<div id="planilla" class="tab-pane fade in active" style="overflow: auto; white-space: nowrap;">
		    	
		    	<h3 class="page-header">Planilla Mensual</h3>
		    	<div class="col-xs-12 text-right" style="margin-bottom: 10px;">
		    		@role(['Administradora','Coordinadora'])
		    		<button   disabled
			           	class="calculo_salario_todos btn btn_color"
					    data-toggle="tooltip" 
				        title="Se calculara el salario de todo lso empleados en la planilla">
				        CALCULAR SALARIOS <i class="fa fa-question-circle" ></i>
				    </button>
				    @endrole
		    	</div><br>

		    	<table class="table crear_planillas " style="overflow-x: scroll;">
	    		
	            <tbody>

	            @foreach($users as $user)    			    
			        <tr ><td colspan="10" style="border-top: 2px red solid;"></td></tr>
			        <tr>		        	
			           	<td rowspan="7" width="200" style="border:none;">
			           		#{{$user->n_contrato}}<br>

			           		@if($edit)				           		
				           		{{$user->nombre}}<br>
				           		{{$user->cargo}}
			           		@else
			           			{{$user->first_name}} {{$user->last_name}}<br>
			           			{{$user->cargo->cargo}}
			           		@endif		           		
			           		
			           		<input type="hidden" name="planilla[{{$user->id}}][nombre]" 
			           		value="@if($edit) {{$user->nombre}} @else {{$user->first_name}} {{$user->last_name}} @endif">
			           		
			           		<input type="hidden" name="planilla[{{$user->id}}][documento]" 
			           		value="@if($edit) {{$user->documento}} @else {{$user->tipo_documento->tipo_documento}} @endif">

			           		<input type="hidden" name="planilla[{{$user->id}}][fecha_inicio]" 
			           		value="{{$user->fecha_inicio}}">

			           		<input type="hidden" name="planilla[{{$user->id}}][cargo]" 
			           		value="@if($edit) {{$user->cargo}} @else {{$user->cargo->cargo}} @endif">

			           		<input type="hidden" name="planilla[{{$user->id}}][user_id]" 
			           		value="@if($edit) {{$user->user_id}} @else {{$user->id}} @endif">
			           		
			           		<input type="hidden" name="planilla[{{$user->id}}][n_contrato]" 
			           		value="{{$user->n_contrato}}">
			           		<br>
			           		@role(['Administradora','Coordinadora'])
			           	
			           		<button disabled 
			           			class="calculo_acumulados btn btn-primary"
					           	data-toggle="tooltip" 
				           		title="Se calcularan los acumulados como tambien los totales"
				           		id="{{$user->id}}">
				           		CALCULAR <i class="fa fa-question-circle" ></i>
				           	</button>
			           		@endrole 
			           		

			           	</td>
			           	<td align="middle" valign="middle"  style="border:none;"><b>SALARIO</b> </td>

			           	@if($oficina->id!=1)
							<td style="border:none;">
								<label>D??as trabajados</label>
								<input type="text" class="form-control" id="dias_trabajados{{$user->id}}"  name="planilla[{{$user->id}}][dias_trabajados]" 
								@if($edit) value="{{$user->dias_trabajados}}" @else value="{{ $user->dias_trabajados }}" @endif>
							</td>
			           	@endrole
			           	<td style="border:none;">
			           		<label>{{$pais->campo->salario_base}} </label>
			           		<input type="number" step="0.01" 
			           		class="form-control salario_base salario_base{{$user->id}}" 
			           		 
			           		name="planilla[{{$user->id}}][salario_base]" value="{{$user->salario_base}}" >
			           	</td>
			           	<td style="border:none;">
			           		<label 
			           		data-toggle="tooltip" 
			           		title="Bono Antig??edad | Ajustes | Salario Retroactivo">
			           			{{$pais->campo->ajustes}}
			           			<i class="fa fa-question-circle"></i>
			           		</label>
			           		<input type="number" step="0.01" 
			           		class="form-control ajuste ajuste{{$user->id}}"
			           		
			           		name="planilla[{{$user->id}}][ajuste]" 
			           		@if($edit) value="{{$user->ajuste}}" @else value="{{ $user->ajuste }}" @endif>
			           	</td>
			           	@if($pais->id == 7)
				           	<td style="border:none;">
				           		<label>Pago Intereses Cesantias</label>
				           		<input type="number" step="0.01" 
				           		class="form-control" id="intereses_cesantias{{$user->id}}" 
				           		 
				           		name="planilla[{{$user->id}}][intereses_cesantias]" 
				           			@if($edit) 
					           			value="{{$user->aporte->intereses_cesantias}}" 
					           		@else
					           			value="{{$user->intereses_cesantias}}" 
					           		@endif >
				           	</td>
			           	@endif
			        </tr>
					
					@if($pais->id == 7)
			        
			        <tr class="adicional{{$user->id}}">
			        	<td align="middle" valign="middle" ><b>VALORES</b> </td>
			        	<td>
								<label 
									data-toggle="tooltip" 
									title="Auxilio de transporte">
									Auxilio de transporte
									<i class="fa fa-question-circle"></i>
								</label>
								<input type="number" step="0.01" class="form-control" id="auxilio_transporte{{$user->id}}" name="planilla[{{$user->id}}][auxilio_transporte]" 
									@if($edit) 
									value="{{$user->aporte->auxilio_transporte}}"
									@else  
									value="{{$user->auxilio_transporte}}"
								@endif>
							</td>
							<td>
								<label>INCAP/Licencia</label>
					           	<input type="number" step="0.01" class="form-control" id="incap_licencia{{$user->id}}" name="planilla[{{$user->id}}][incap_licencia]" 
					           		
					           		@if($edit) 
					           			value="{{$user->aporte->incap_licencia}}" 
					           		@else
					           			value="{{$user->incap_licencia}}" 
					           		@endif
					           		 >
							</td>
			        </tr>
			        @endif
			        
			        {{--Aqu?? viene un ajuste m??s del bono 14 ahora configurable en ajustes --}}
			        <tr class="aportes{{$user->id}}">				        
				        @if($pais->id == 7 || $pais->id==1 || ($pais->bono_14 != 'No Disponible') && str_contains($fecha, $pais->bono_14))

				        	<td align="middle" valign="middle" ><b>APORTES</b> </td>				        
					    	@if ($pais->bono_14 != 'No Disponible' && str_contains($fecha, $pais->bono_14)) 					           	
						    <td>
						    	<label 
						    		data-toggle="tooltip" 
			           				title="Acumulado de junio a??o anterior a mayo a??o actual">
					           		Bonificaci??n 14 (Dto 42-92)({{$pais->bono_14}})
					           		<i class="fa fa-question-circle"></i>
					           	</label>
						    	<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][bonificacion_14]" id="total_bono14_{{$user->id}}" 
							     @if($edit) 
							     value="{{$user->aporte->bonificacion_14}}"
							     @else  
							     value="{{$user->catorceavo_total}}"
							     @endif>
						    </td>
							@endif				        

				        	@if($pais->id==1) 
					        	<td >
					           		<label>Bonificaci??n incentivo</label>
					           		<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][bonificacion_incentivo]"
						           		@if($edit) 
						           			value="{{$user->aporte->bonificacion_incentivo}}" 
						           		@else
						           			value="{{$user->bonificacion_incentivo}}" 
						           		@endif  
					           		>
					           	</td>
					           	<td>
					           		<label>Bonificaci??n Docto 37 2001</label>
					           		<input type="number" step="0.01" class="form-control"   name="planilla[{{$user->id}}][bonificacion_docto_37_2001]" 
					           			@if($edit) 
						           			value="{{$user->aporte->bonificacion_docto_37_2001}}" 
						           		@else
						           			value="{{$user->bonificacion_docto_37_2001}}" 
						           		@endif
					           		>
					           	</td>
					           	<td>
					           		<label>Reintegros</label>
					           		<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][reintegros]"
					           		 	@if($edit) 
						           			value="{{$user->aporte->reintegros}}" 
						           		@else
						           			value="{{$user->reintegros}}" 
						           		@endif
					           		>
					           	</td> 

					           	<!-- @if (str_contains($fecha, 'Julio')) 					           	
						           	<td>
						           		<label
							           		data-toggle="tooltip" 
				           					title="Monto equivalente a un salario completo">
							           		Bonificaci??n 14 (Dto 42-92)(Julio)
						           		<i class="fa fa-question-circle"></i>
						           	</label>
						           		<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][bonificacion_14]" 
						           		@if($edit) 
						           		value="{{$user->aporte->bonificacion_14}}"
						           		@else  
						           		value="{{$user->salario_base}}"
						           		@endif>
						           	</td>
						       	@endif						          -->
				        	@endif
							@if($pais->id == 7) <!--COLOMBIA -->
								
								<td>
									<label 
										data-toggle="tooltip" 
										title="Bono salud">
										Bono salud
										<i class="fa fa-question-circle"></i>
									</label>
									<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][bono_salud]" 
									@if($edit) 
									value="{{$user->aporte->bono_salud}}"
									@else  
									value="{{$user->bono_salud}}"
									@endif>
								</td>
								<td>
									<label 
										data-toggle="tooltip" 
										title="Capacitaci??n">
										Capacitaci??n
										<i class="fa fa-question-circle"></i>
									</label>
									<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][capacitacion]" 
									@if($edit) 
									value="{{$user->aporte->capacitacion}}"
									@else  
									value="{{$user->capacitacion}}"
									@endif>
								</td>
								<td>
									<label 
										data-toggle="tooltip" 
										title="Seguro gastos m??dicos">
										Seguro gastos m??dicos
										<i class="fa fa-question-circle"></i>
									</label>
									<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][seguro_medico]" 
									@if($edit) 
									value="{{$user->aporte->seguro_medico}}"
									@else  
									value="{{$user->seguro_medico}}"
									@endif>
								</td>
								<td>
									<label>Total Incapacidad</label>
					           		<input type="number" step="0.01" class="form-control" id="total_incapacidad{{$user->id}}" name="planilla[{{$user->id}}][total_incapacidad]" 
					           		
					           		@if($edit) 
					           			value="{{$user->aporte->total_incapacidad}}" 
					           		@else
					           			value="{{$user->total_incapacidad}}" 
					           		@endif
					           		 >
								</td>
								<td>
									<label>Incapacidades</label>
					           		<input type="number" step="0.01" class="form-control" id="incapacidades{{$user->id}}" name="planilla[{{$user->id}}][incapacidades]" 
					           		
					           		@if($edit) 
					           			value="{{$user->aporte->incapacidades}}" 
					           		@else
					           			value="{{$user->incapacidades}}" 
					           		@endif
					           		 >
								</td>
								<td>
									<label>AUX. Transp.</label>
					           		<input type="number" step="0.01" class="form-control" id="aux_trans{{$user->id}}" name="planilla[{{$user->id}}][aux_trans]" 
					           		
					           		@if($edit) 
					           			value="{{$user->aporte->aux_trans}}" 
					           		@else
					           			value="{{$user->aux_trans}}" 
					           		@endif
					           		 >
								</td>
								<td>
									<label>AUX. Celular</label>
					           		<input type="number" step="0.01" class="form-control" id="aux_celular{{$user->id}}" name="planilla[{{$user->id}}][aux_celular]" 
					           		
					           		@if($edit) 
					           			value="{{$user->aporte->aux_celular}}" 
					           		@else
					           			value="{{$user->aux_celular}}" 
					           		@endif
					           		 >
								</td>
								<td>
									<label>Medios Transp.</label>
					           		<input type="number" step="0.01" class="form-control" id="medios_transp{{$user->id}}" name="planilla[{{$user->id}}][medios_transp]" 
					           		
					           		@if($edit) 
					           			value="{{$user->aporte->medios_transp}}" 
					           		@else
					           			value="{{$user->medios_transp}}" 
					           		@endif
					           		 >
								</td>
								<td>
									<label>Otros Devengos</label>
					           		<input type="number" step="0.01" class="form-control" id="otros_devengos{{$user->id}}" name="planilla[{{$user->id}}][otros_devengos]" 
					           		
					           		@if($edit) 
					           			value="{{$user->aporte->otros_devengos}}" 
					           		@else
					           			value="{{$user->otros_devengos}}" 
					           		@endif
					           		 >
								</td>
							@endif
				        @endif				        
					</tr>

			        <tr class="deducciones deducciones{{$user->id}}" >
			        	<td align="center" rowspan="2"><b>DEDUCCIONES</b></td>		        	
			        	
						@if( in_array('seguridad_social', explode(',', $pais->campo_deducciones)) )
			           	<td>
			           		<label>{{$pais->campo->seguridad_social}}</label>
			           		<input type="number" 
			           		step="0.01" class="form-control" 
			           		id="seguridad_social{{$user->id}}"
			           		name="planilla[{{$user->id}}][seguridad_social]" 
			           		
				           		@if($edit)			           			
				           			value="{{$user->deduccion->seguridad_social}}" 
				           		@else
				           			value="{{$user->seguridad_social}}"
				           		@endif
			           		>
			           	</td>
			           	@endif

						@if( in_array('impuesto_renta', explode(',', $pais->campo_deducciones)) )
			           	<td>
			           		<label>{{$pais->campo->impuestos}}</label>
			           		<input type="number" step="0.01" class="form-control impuestos impuestos{{$user->id}}" name="planilla[{{$user->id}}][impuesto_renta]"
			           			@if($edit)			           			
				           			value="{{$user->deduccion->impuesto_renta}}" 
				           		@else
				           			value="{{$user->impuesto_renta}}"
				           		@endif
			           		>
			           		
			           	</td>
			           	@endif	           	
			           	
			           	@if( in_array('prestamo', explode(',', $pais->campo_deducciones)) )
			           	<td>
			           		<label>{{$pais->campo->prestamo}}</label>
			           		<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][prestamo]"
			           			@if($edit)			           			
				           			value="{{$user->deduccion->prestamo}}" 
				           		@else
				           			value="{{$user->prestamo}}"
				           		@endif
			           		>
			           	</td>
			           	@endif
			           	
			           	@if( in_array('interes', explode(',', $pais->campo_deducciones)) )
			           	<td>
			           		<label>{{$pais->campo->interes}}</label>
			           		<input type="number" step="0.01" class="form-control "   name="planilla[{{$user->id}}][interes]"
			           			@if($edit)			           			
				           			value="{{$user->deduccion->interes}}" 
				           		@else
				           			value="{{$user->interes}}"
				           		@endif
			           		>
			           	</td>
			           	@endif
			           	
			           	@if( in_array('otras_deducciones', explode(',', $pais->campo_deducciones)) )
			           	<td>
			           		<label>{{$pais->campo->otras_deducciones}}</label>
			           		<input type="number" step="0.01" class="form-control "   name="planilla[{{$user->id}}][otras_deducciones]"
			           		 	@if($edit)			           			
				           			value="{{$user->deduccion->otras_deducciones}}" 
				           		@else
				           			value="{{$user->otras_deducciones}}"
				           		@endif
			           		>
			           	</td>
			           	@endif
			        </tr>
			        <tr class="deducciones deducciones{{$user->id}}">
			        	@if($pais->id==2)
			        	<td>
			           		<label>RC - IVA 13%</label>
			           		<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][rc_iva]" 
			           		
			           		@if($edit) 
			           			value="{{$user->deduccion->rc_iva}}"
			           		@else
			           			value="{{$user->rc_iva}}"
			           		@endif 
			           		>
			           	</td>
			           	<td>
			           		<label>CTA. IND. 10%</label>
			           		<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][cta_ind]"           		
			           		@if($edit) 
			           			value="{{$user->deduccion->cta_ind}}" 
			           		@else 
			           			value="{{$user->cta_ind}}"
			           		@endif>
			           	</td>

			           	<td>
			           		<label>RIESGO 1,71%</label>
			           		<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][riesgo]"           		
			           		@if($edit) 
			           			value="{{$user->deduccion->riesgo}}" 
			           		@else 
			           			value="{{$user->riesgo}}"
			           		@endif>
			           	</td>

			           	<td>
			           		<label>COM. AFP 0,5%</label>
			           		<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][com_afp]"           		
			           		@if($edit) 
			           			value="{{$user->deduccion->com_afp}}" 
			           		@else 
			           			value="{{$user->com_afp}}"
			           		@endif>
			           	</td>
			           	<td>
			           		<label>AFP APORTE SOLIDARIO 0,5%</label>
			           		<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][afp_aporte_solidario]"           		
			           		@if($edit) 
			           			value="{{$user->deduccion->afp_aporte_solidario}}" 
			           		@else 
			           			value="{{$user->afp_aporte_solidario}}"
			           		@endif>
			           	</td>
			           	<td>
			           		<label>AFP APORTE NACIONAL SOLIDARIO 1% </label>
			           		<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][afp_aporte_nacional_solidario]"           		
			           		@if($edit) 
			           			value="{{$user->deduccion->afp_aporte_nacional_solidario}}" 
			           		@else 
			           			value="{{$user->afp_aporte_nacional_solidario}}"
			           		@endif>
			           	</td>
						@endif

			           	@if($pais->id==4)
			        	<td>
			           		<label>Seguro M??dico</label>
			           		<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][seguro_medico]" 
			           			@if($edit) 
				           			value="{{$user->deduccion->seguro_medico}}" 
				           		@else
				           			value="{{$user->seguro_medico}}" 
				           		@endif
			           		>
			           	</td>
			           	<td>
			           		<label>RAP 1.5%</label>
			           		<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][rap]" 
			           		
			           		@if($edit) 
			           			value="{{$user->deduccion->rap}}" 
			           		@else
			           			value="{{$user->rap}}" 
			           		@endif
			           		 >
			           	</td>
			           	@endif

			           	@if($pais->id==3)
			        	<td>
			           		<label>Deducci??n 1</label>
			           		<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][deduccion_1]"
			           		 	@if($edit) 
				           			value="{{$user->deduccion->deduccion_1}}" 
				           		@else
				           			value="{{$user->deduccion_1}}" 
				           		@endif
			           		>
			           	</td>
			           	<td>
			           		<label>Deducci??n 2</label>
			           		<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][deduccion_2]"
			           		 	@if($edit) 
				           			value="{{$user->deduccion->deduccion_2}}" 
				           		@else
				           			value="{{$user->deduccion_2}}" 
				           		@endif
			           		>
			           	</td>
			           	@endif

			           	@if($pais->id==6)
					        <td>
					           	<label>AFP {{7.25}} % </label>
					           	<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][afp]" 
					           	@if($edit) 
					           		value="{{$user->deduccion->afp}}"
								@else
					           		value="{{$user->afp}}"
					           	@endif>
					        </td>					           	 
				        @endif

						@if($pais->id == 7) <!--COLOMBIA -->
							<td>
					           	<label>Salud 4%</label>
					           	<input type="number" step="0.01" class="form-control" id="salud{{$user->id}}" name="planilla[{{$user->id}}][salud]" 
					           	@if($edit) 
					           		value="{{$user->deduccion->salud}}"
								@else
					           		value="{{$user->salud}}"
					           	@endif>
					        </td>
					        @if($user->salario_base >= $pais->salario_minimo * 4)
					        	<td>
						           	<label>FSP {{intval($pais->porcentaje_fsp)}}%</label>
						           	<input type="number" step="0.01" class="form-control" id="fsp{{$user->id}}" name="planilla[{{$user->id}}][fsp]"					  
						           	@if($edit) 
						           		value="{{$user->deduccion->fsp}}"
									@else
						           		value="{{$user->fsp}}"
						           	@endif>
					        	</td>
					        @endif
							<td>
					           	<label>Pension 4%</label>
					           	<input type="number" step="0.01" class="form-control" id="pension_col{{$user->id}}" name="planilla[{{$user->id}}][pen]" 
					           	@if($edit) 
					           		value="{{$user->deduccion->pen}}"
								@else
					           		value="{{$user->pen}}"
					           	@endif>
					        </td>
							<td>
					           	<label>RTE FTW UVT</label>
					           	<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][rte]" 
					           	@if($edit) 
					           		value="{{$user->deduccion->rte}}"
								@else
					           		value="{{$user->rte}}"
					           	@endif>
					        </td>
					        <td>
					           	<label>Otros descuentos celulares</label>
					           	<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][otros_descuentos_celulares]" 
					           	@if($edit) 
					           		value="{{$user->deduccion->otros_descuentos_celulares}}"
								@else
					           		value="{{$user->otros_descuentos_celulares}}"
					           	@endif>
					        </td>	
						@endif
			        </tr>
			        <tr class="patronales">
			        	<td align="middle" valign="middle" ><b>APORTES PATRONALES</b> </td>


			        	@if($pais->id==1 || $pais->id==3 || $pais->id==4 || $pais->id==5 || $pais->id==6)        	 
				        
				        <td>
					       	<label
					       	data-toggle="tooltip" 
			           		>{{$pais->campo->seguridad_social_patronal}}</label>
					        <input type="number" step="0.01" class="form-control"   
					        name="planilla[{{$user->id}}][seguridad_social_patronal]"
					        id="seguridad_social_p{{$user->id}}"
					         
					        @if($edit) 
					        	value="{{$user->aporte->seguridad_social_patronal}}"
				         	@else
				         		value="{{$user->seguridad_social_patronal}}"
					        @endif>
					    </td>
					    @endif
					    <!--Bolivia-->
					    @if($pais->id==2)
					        <td>
					           	<label>Seguro Universitario 10%</label>
					           	<input type="number" step="0.01" class="form-control"   name="planilla[{{$user->id}}][seguro_universitario]" 
					           	@if($edit) 
					           		value="{{$user->aporte->seguro_universitario}}" 
					           	@else
					           		value="{{$user->seguro_universitario}}"
					           	@endif
					           	>
					        </td>
					        <td>
					           	<label>AFP Previsi??n 1.71%</label>
					           	<input type="number" step="0.01" class="form-control"   name="planilla[{{$user->id}}][afp_prevision]" 
					           	@if($edit) 
					           		value="{{$user->aporte->afp_prevision}}" 
					           	@else
					           		value="{{$user->afp_prevision}}"
					           	@endif
					           	>
					        </td>
					        <td>
					           	<label>AFP PNVS 2%</label>
					           	<input type="number" step="0.01" class="form-control"   name="planilla[{{$user->id}}][afp_prevision_pnvs]" 
					           	@if($edit) 
					           		value="{{$user->aporte->afp_prevision_pnvs}}" 
					           	@else
					           		value="{{$user->afp_prevision_pnvs}}"
					           	@endif
					           	>
					        </td>
					        <td>
					           	<label>AFP Aporte Solidario 3%</label>
					           	<input type="number" step="0.01" class="form-control"   name="planilla[{{$user->id}}][aporte_afp_aporte_solidario]" 
					           	@if($edit) 
					           		value="{{$user->aporte->afp_aporte_solidario}}" 
					           	@else
					           		value="{{$user->aporte_afp_aporte_solidario}}"
					           	@endif
					           	>
					        </td>					           	 
				        @endif
					    
					    <!--nicaragua-->
					    @if($pais->id==3)
					        <td>
					           	<label>INATEC</label>
					           	<input type="number" step="0.01" class="form-control"   name="planilla[{{$user->id}}][INATEC]" 
					           		@if($edit) 
						           		value="{{$user->aporte->INATEC}}" 
						           	@else
						           		value="{{$user->INATEC}}"
						           	@endif
					           	>
					        </td>					           	 
				        @endif
				        <!--honduras-->
				        @if($pais->id==4)
							<td>
								<label>RAP 1.5%</label>
				           		<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][rap_patronal]" 
				           		
				           		@if($edit) 
				           			value="{{$user->aporte->rap}}" 
				           		@else
				           			value="{{$user->rap_patronal}}" 
				           		@endif
				           		 >
							</td>
						@endif

						@if($pais->id == 6)
							@if($edit)
								@if($planilla)
									@if($planilla->created_at->format('Y-m-d') >= date('2018-11-01'))
										<td>
											<label>AFP Patronal 7.75%</label>
											<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][afp_6_75]" 
												value="{{$user->aporte->afp_6_75}}" 
											>
										</td>
									@endif
								@endif
							@else
								<td>
									<label>AFP Patronal 7.75%</label>
									<input type="number" step="0.01" class="form-control" name="planilla[{{$user->id}}][afp_6_75]" 
										value="{{$user->afp_6_75}}" 
									>
								</td>
							@endif
						@endif
						<!--salvador-->

						@if($pais->id == 7) <!--COLOMBIA-->
							<td>
								<label>Caja de Compensaci??n {{$pais->porcentaje_parafiscales}}%</label>
				           		<input type="number" step="0.01" class="form-control" id="parafiscales{{$user->id}}" name="planilla[{{$user->id}}][parafiscales]" 
				           		
				           		@if($edit) 
				           			value="{{$user->aporte->parafiscales}}" 
				           		@else
				           			value="{{$user->parafiscales}}" 
				           		@endif
				           		 >
							</td>
							<td>
								<label>Arl {{$pais->porcentaje_arl}}%</label>
				           		<input type="number" step="0.01" class="form-control" id="arl{{$user->id}}" name="planilla[{{$user->id}}][arl]" 
				           		
				           		@if($edit) 
				           			value="{{$user->aporte->arl}}" 
				           		@else
				           			value="{{$user->arl}}" 
				           		@endif
				           		 >
							</td>
							<td>
								<label>EPS {{$pais->porcentaje_eps}}%</label>
				           		<input type="number" step="0.01" class="form-control" id="eps{{$user->id}}" name="planilla[{{$user->id}}][eps]" 
				           		
				           		@if($edit) 
				           			value="{{$user->aporte->eps}}" 
				           		@else
				           			value="{{$user->eps}}" 
				           		@endif
				           		 >
							</td>
							<td>
								<label>C.Opci??n {{$pais->porcentaje_caja_opcion}}%</label>
				           		<input type="number" step="0.01" class="form-control" id="caja_opcion{{$user->id}}" name="planilla[{{$user->id}}][caja_opcion]" 
				           		
				           		@if($edit) 
				           			value="{{$user->aporte->caja_opcion}}" 
				           		@else
				           			value="{{$user->caja_opcion}}" 
				           		@endif
				           		 >
							</td>
							<td>
								<label>ICBF {{$pais->porcentaje_icbf}}%</label>
				           		<input type="number" step="0.01" class="form-control" id="icbf{{$user->id}}" name="planilla[{{$user->id}}][icbf]" 
				           		
				           		@if($edit) 
				           			value="{{$user->aporte->icbf}}" 
				           		@else
				           			value="{{$user->icbf}}" 
				           		@endif
				           		 >
							</td>
							<td>
								<label>SENA {{$pais->porcentaje_sena}}%</label>
				           		<input type="number" step="0.01" class="form-control" id="sena{{$user->id}}" name="planilla[{{$user->id}}][sena]" 
				           		
				           		@if($edit) 
				           			value="{{$user->aporte->sena}}" 
				           		@else
				           			value="{{$user->sena}}" 
				           		@endif
				           		 >
							</td>
							<td>
								<label>Salud 8.5%</label>
				           		<input type="number" step="0.01" class="form-control" id="salud_patronal{{$user->id}}" name="planilla[{{$user->id}}][salud_patronal]" 
				           		
				           		@if($edit) 
				           			value="{{$user->aporte->salud_patronal}}" 
				           		@else
				           			value="{{$user->salud_patronal}}" 
				           		@endif
				           		 >
							</td>
							<td>
								<label>Pension patronal 12%</label>
				           		<input type="number" step="0.01" class="form-control" id="pension_patronal{{$user->id}}" name="planilla[{{$user->id}}][pension_patronal]" 
				           		
				           		@if($edit) 
				           			value="{{$user->aporte->pension_patronal}}" 
				           		@else
				           			value="{{$user->pension_patronal}}" 
				           		@endif
				           		 >
							</td>
						@endif
						
					   
			        </tr>
			        <tr class="acumulados">
			        	<td align="center" ><b>ACUMULADOS</b></td>
							@if($pais->bono_14 != 'No Disponible')
								<td>
									<label
									data-toggle="tooltip" 
									title="Salario / 12">
									{{$pais->campo->catorceavo}}
									<i class="fa fa-question-circle"></i>
								</label>
									<input type="number" 
									step="0.01" 
									class="form-control" 
									id="catorceavo{{$user->id}}" 
									name="planilla[{{$user->id}}][catorceavo]" 
									@if($edit) 
										value="{{$user->acumulado->catorceavo}}"
									@else 
										value="{{number_format($user->salario_base/12, 2, '.', '')}}"  
									@endif>
								</td>
							@endif
				        @if( !str_contains($fecha, 'Diciembre')) 
			        		
					        	<td>

				           		<label
						           	data-toggle="tooltip" 
						           	@if($pais->id==6) 
					           			title="EXENTO + AGRAVADO - {{$pais->campo->impuestos}}">
					           		@else
					           			title="Salario * 8.33%">
					           		@endif
					           		{{$pais->campo->acumulado_aguinaldo}}<i class="fa fa-question-circle" ></i>
					           	</label>
				           		<input type="number" 
				           		step="0.01" id="aguinaldo{{$user->id}}"
				           		class="form-control aguinaldo_acumulado"
				           		name="planilla[{{$user->id}}][aguinaldo_meses][{{$fecha}}]"							
				           		@if($edit) 
						           	value="{{$user->acumulado->aguinaldo}}"
						        @else 
					           		value="{{$user->ag}}"
					           	@endif
				           		
				           		>				          
				           	</td>	
				           	
				        @endif

				        @if( !str_contains($fecha, 'Diciembre') || $pais->pago_indemnizacion=="retiro")  	
				           	<td>
				           		<label
						           	data-toggle="tooltip" 
					           		title="Salario * 8.33%">
					           		{{$pais->campo->acumulado_indemnizacion}}<i class="fa fa-question-circle" ></i>
					           	</label>
				           		<input type="number" step="0.01" id="indemnizacion{{$user->id}}" 
				           		class="form-control indemnizacion_acumulado"
				           		name="planilla[{{$user->id}}][indemnizacion_meses][{{$fecha}}]"
				           		@if($edit) 
						           	value="{{$user->acumulado->indemnizacion}}"
						        @else 
				           			value="{{$user->inde}}"		           			
				           		@endif
				           		>
				           	</td>
				        @endif

						@if($pais->id == 7)
							<td>
				           		<label
						           	data-toggle="tooltip" 
					           		title="Salario * 1%">
					           		Inter??s 1%<i class="fa fa-question-circle" ></i>
					           	</label>
				           		<input type="number" step="0.01" id="interes_col{{$user->id}}" 
				           		class="form-control interes_col_acumulado"
				           		name="planilla[{{$user->id}}][interes_col_meses][{{$fecha}}]"
				           		@if($edit) 
						           	value="{{$user->acumulado->interes_col}}"
						        @else 
				           			value="{{$user->interes_col}}"		           			
				           		@endif
				           		>
				           	</td>

							<td>
				           		<label
						           	data-toggle="tooltip" 
					           		title="Salario * 4.17%">
					           		Vacaciones 4.17%<i class="fa fa-question-circle" ></i>
					           	</label>
				           		<input type="number" step="0.01" id="vacaciones{{$user->id}}" 
				           		class="form-control vacaciones_acumulado"
				           		name="planilla[{{$user->id}}][vacaciones_meses][{{$fecha}}]"
				           		@if($edit) 
						           	value="{{$user->acumulado->vacaciones}}"
						        @else 
				           			value="{{$user->vacaciones}}"		           			
				           		@endif
				           		>
				           	</td>
						@endif

				        @if( !str_contains($fecha, 'Diciembre') || $pais->pago_pension=="retiro")			           	
				           @if( in_array('fondo_pension', explode(',', $pais->campo_deducciones)) )
				           <td >
					           		<label
					           			data-toggle="tooltip" 
						           		title="Salario * {{$pais->porcentaje_pension}}%">
					           		
					           			Fondo de Pensi??n
					           			<i class="fa fa-question-circle" ></i>
					           		</label>
					           		<input type="number" 
					           		step="0.01" id="fondo{{$user->id}}"
					           		class="form-control pension_acumulado" 
					           		name="planilla[{{$user->id}}][pension_meses][{{$fecha}}]"
					           		@if($edit) 
							           	value="{{$user->acumulado->pension}}"
							        @else 
					           		value="{{$user->pen}}"			           		
					           		@endif
					           		>
					           	</td>
					        @endif
				        @endif
			           			        	
			        </tr>
			        <tr class="totales">
			        	<td align="center"><b>TOTALES</b></td>
			        	<td>
			           		<label style="text-transform: uppercase">{{$pais->campo->total_salario}}</label>
			           		<input type="number" readonly step="0.01" class="form-control " id="total_salario{{$user->id}}" name="planilla[{{$user->id}}][total_salario]" 
			           		@if($edit) value="{{$user->total_salario}}" @endif>        		
			           		
			           	</td>
			           	<td>
			           		<label>TOTAL DEDUCCIONES</label>
			           		<input type="number" readonly step="0.01" class="form-control" id="total_deducciones{{$user->id}}" name="planilla[{{$user->id}}][total_deducciones]" 
			           		
			           		@if($edit) value="{{$user->deduccion->total_deducciones}}" @endif>
			           	</td>
			           	@if($pais->id==1 || (str_contains($fecha, 'Junio') && $pais->id==4 || $pais->id == 7 ))
			           	<td>
			           		<label>TOTAL APORTES</label>
			           		<input type="number" readonly step="0.01" class="form-control" id="total_aportes{{$user->id}}" name="planilla[{{$user->id}}][total_aportes]" 
			           		
			           		@if($edit) value="{{$user->aporte->total_aportes}}" @endif>
			           	</td>
			           	@endif
			           	<td>
			           		<label style="text-transform: uppercase">{{$pais->campo->liquido}}</label>
			           		<input type="number" readonly step="0.01" class="form-control" id="liquido{{$user->id}}" name="planilla[{{$user->id}}][liquido]" 
			           		
			           		@if($edit) value="{{$user->liquido_recibir}}" @endif>
			           	</td>
			        </tr>	       
	    		@endforeach	
	    		<tr ><td colspan="10" style="border-top: 2px red solid;"></td></tr>
	            </tbody>
	    		</table>   
		  	</div>
		  	@if(str_contains($fecha, $pais->bono_14))
		  	    <div id="bonocatorce" class="tab-pane fade">
			    	@include('planillas.bonocatorceavo')
			  	</div>
		  	@endif
		  	@if(str_contains($fecha, 'Diciembre'))
			 	<div id="aguinaldo" class="tab-pane fade">
			    	@include('planillas.aguinaldo')
			  	</div>
			  	
			  	@if($pais->pago_indemnizacion=="anual" || $pais->pago_indemnizacion=="retiro")
					<div id="indemnizacion" class="tab-pane fade">
				    	@include('planillas.indemnizacion')
				  	</div>
			  	@endif

			  	@if($campos[0] =="fondo_pension")
				  	<div id="pension" class="tab-pane fade">
				    	@include('planillas.pension')
				  	</div>
			  	@endif
		  	@endif
			<div id="permisos" class="tab-pane fade">
				<div class="col-sm-12">
					<div class="form-group">
						<label class="col-sm-2 control-label">Personas que pueden Confirmar la Planilla:</label>
						<div class="col-sm-4">
							<table class="table table-bordered" style="margin-bottom: 0px;">
								@foreach ($rolesConfirmar as $rol)
									@if ($rol->id != 8)
										@if ($rol->id == 4 || $rol->id == 5 || $rol->id == 9)
											<tr>
												<th>{{$rol->display_name}}:</th>
												<td>
													<ul>
														@foreach ($rol->users as $u)
															<li>{{$u->first_name}} {{$u->last_name}}</li>
														@endforeach
													</ul>
												</td>
											</tr>
										@elseif (count($rol->users->where('oficina_id', $oficina->id)->where('status', 1)) > 0)
											<tr>
												<th>{{$rol->display_name}}:</th>
												<td>
													<ul>
														@foreach ($rol->users->where('oficina_id', $oficina->id)->where('status', 1) as $u)
															<li>{{$u->first_name}} {{$u->last_name}}</li>
														@endforeach
													</ul>
												</td>
											</tr>
										@endif
									@endif
								@endforeach
							</table>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Personas que pueden aprobar como Coordinadora:</label>
						<div class="col-sm-4">
							<table class="table table-bordered" style="margin-bottom: 0px;">
								@foreach ($rolesAprobCoord as $rol)
									@if ($rol->id != 8)
										@if ($rol->id == 4 || $rol->id == 5 || $rol->id == 9)
											<tr>
												<th>{{$rol->display_name}}:</th>
												<td>
													<ul>
														@foreach ($rol->users as $u)
															<li>{{$u->first_name}} {{$u->last_name}}</li>
														@endforeach
													</ul>
												</td>
											</tr>
										@elseif (count($rol->users->where('oficina_id', $oficina->id)->where('status', 1)) > 0)
											<tr>
												<th>{{$rol->display_name}}:</th>
												<td>
													<ul>
														@foreach ($rol->users->where('oficina_id', $oficina->id)->where('status', 1) as $u)
															<li>{{$u->first_name}} {{$u->last_name}}</li>
														@endforeach
													</ul>
												</td>
											</tr>
										@endif
									@endif
								@endforeach
							</table>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Personas que pueden aprobar como Directora:</label>
						<div class="col-sm-4">
							<table class="table table-bordered" style="margin-bottom: 0px;">
								@foreach ($rolesAprobDir as $rol)
									@if ($rol->id != 8)
										@if ($rol->id == 4 || $rol->id == 5 || $rol->id == 9)
											<tr>
												<th>{{$rol->display_name}}:</th>
												<td>
													<ul>
														@foreach ($rol->users as $u)
															<li>{{$u->first_name}} {{$u->last_name}}</li>
														@endforeach
													</ul>
												</td>
											</tr>
										@elseif (count($rol->users->where('oficina_id', $oficina->id)->where('status', 1)) > 0)
											<tr>
												<th>{{$rol->display_name}}:</th>
												<td>
													<ul>
														@foreach ($rol->users->where('oficina_id', $oficina->id)->where('status', 1) as $u)
															<li>{{$u->first_name}} {{$u->last_name}}</li>
														@endforeach
													</ul>
												</td>
											</tr>
										@endif
									@endif
								@endforeach
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		     	 	
		    
		
		<div class=" text-right"  style="margin-top: 15px;">		       	 		
		    <button type="submit" class="btn btn_color guardar">
		    	{{$edit ? 'Guardar Cambios' : 'Crear Planilla'}}
		    </button>		 	   	    	
	   	</div> 	
    </form>

@stop

@section('scripts')

<script> 
	
	jQuery(document).ready(function($) {
	 	$('[data-toggle="tooltip"]').tooltip();
	 	//$('.calculo_acumulados,.calculo_salario_todos').attr('disabled',true);
	 	
	 	$(window).load(function() {
    		$('.calculo_acumulados,.calculo_salario_todos,.calculo_aguinaldo_todos,.calculo_indemnizacion_todos,.calculo_pension_todos,.calculo_bonocatorce_todos').removeAttr('disabled'); 
    		
    		
    		@if(!$edit)
    			calculo_totales_inputs();  			
    		@endif

	 	});
	 }); 
	function calculo_totales_inputs() {
		$('.calculo_salario_todos').click();   			
    	$('.calculo_aguinaldo_todos').click();    			
    	$('.calculo_indemnizacion_todos').click();
       	$('.calculo_pension_todos').click();
       	$('.total_aguinaldo,.total_indemnizacion,.total_pension').css('backgroundColor', '#eee');
	}
	@role(['Directora','Contralora','Admin'])	    	
    	$('.planilla_form input').attr('disabled', true); 
	@endrole

	@role(['Administradora','Coordinadora'])
	@if(
		(Entrust::hasRole('Administradora') && !$edit) || 
		(Entrust::hasRole('Administradora') && $edit  && !$planilla->confirmada) ||
		(Entrust::hasRole('Coordinadora') && $edit  && !$planilla->confirmada) ||		
		(Entrust::hasRole('Coordinadora') && !$edit && $oficina->id==1 )
		)	    	 		
		$('.planilla_form .tab-content input').attr('disabled', false); 		
	@else
		$('.planilla_form  input,.planilla_form select').attr('disabled', true);
		$('button.guardar,.calculo_acumulados,.calculo_salario_todos,.calculo_aguinaldo_todos,.calculo_indemnizacion_todos,.calculo_pension_todos').remove(); 
		
	@endif
	@endrole

	@if (Entrust::hasRole('Coordinadora') && $edit && $oficina->id!==1)
		$('.encabezado_form select[name="confirmada"],.encabezado_form input[name="cambio"]').attr('disabled', true);
	@endif


//////////////////////////////////////////////////////////////////////////////////
	//calculo acumulado aguinaldo
	$(document).on('click ', '.calculo_acumulados', function(event) {
		
		event.preventDefault();		
		pais={{$pais->id}};
		tipo_seguridad_social="{{$pais->tipo_seguridad_social}}";
		porcentaje_seguridad_social="{{$pais->porcentaje_seguridad_social}}";

		tipo_seguridad_social_p="{{$pais->tipo_seguridad_social_p}}";
		porcentaje_seguridad_social_p="{{$pais->seguridad_social_p}}";
		
		id=$(this).attr('id');
		
		catorceavo_input="#catorceavo"+id;
		fondo_input="#fondo"+id;		
		indemnizacion_input="#indemnizacion"+id;
		aguinaldo_input="#aguinaldo"+id;
		interes_col_input = "#interes_col"+id;
		vacaciones_input = "#vacaciones"+id;
		seguridad_social_input="#seguridad_social"+id;
		seguridad_social_p_input="#seguridad_social_p"+id;
		total_salario_input="#total_salario"+id;
		total_deducciones_input="#total_deducciones"+id;
		total_aportes_input="#total_aportes"+id;
		total_liquido_input="#liquido"+id;
		dias_trabajados_input = '#dias_trabajados'+id;

		salario_base=$('.salario_base'+id).val();
		ajuste=$('.ajuste'+id).val();
		impuestos=$('.impuestos'+id).val();

		salario_base=salario_base ? salario_base : 0;
		ajuste=ajuste ? ajuste : 0;
		impuestos=impuestos ? impuestos : 0;

		if(pais == 7) {
			//I need this
			auxilio_transporte_input = '#auxilio_transporte'+id;
			//nuevos campos de aporte
			intereses_cesantias_input = '#intereses_cesantias'+id;
			incap_licencia_input = '#incap_licencia'+id;
			//
			total_incapacidad_input = '#total_incapacidad'+id;
			incapacidades_input = '#incapacidades'+id;
			aux_trans_input = '#aux_trans'+id;


			salud_input = '#salud'+id;
			fsp_input = '#fsp'+id;
			pension_col_input = '#pension_col'+id;
			parafiscales_input = '#parafiscales'+id;
			arl_input = '#arl'+id;
			eps_input = '#eps'+id;
			caja_opcion_input = '#caja_opcion'+id;
			icbf_input = '#icbf'+id;
			sena_input = '#sena'+id;
			salud_patronal_input = '#salud_patronal'+id;
			pension_patronal_input = '#pension_patronal'+id;


			dias_trabajados = $(dias_trabajados_input).val();
			auxilio_transporte = $(auxilio_transporte_input).val();

			//variables nuevos campos aportes
			intereses_cesantias = $(intereses_cesantias_input).val();
			incap_licencia = $(incap_licencia_input).val();

			total_incapacidad = ((parseFloat(salario_base)/ parseFloat(dias_trabajados)) * {{(float)$pais->porcentaje_incapacidad/100}}) * incap_licencia;
			incapacidades = (parseFloat(salario_base)/ parseFloat(dias_trabajados)) * incap_licencia;
			aux_trans = (parseFloat(dias_trabajados) / 30) * parseFloat(auxilio_transporte);


			total_aportes = totales_inputs('tr.aportes'+id+" input");

			total_salario = (parseFloat(salario_base)/30) * parseFloat(dias_trabajados) + parseFloat(ajuste) + parseFloat(intereses_cesantias) + parseFloat(total_aportes);
			
			aguinaldo = (total_salario) * (8.33/100);


			fondo = total_salario*{{((float)$pais->porcentaje_pension/100)}};
			catorceavo = total_salario*(8.33/100);				
			indemnizacion = (total_salario) * (8.33/100);
			//acumulado colombia
			interes_col = (total_salario) * (1.00/100);
			vacaciones = (total_salario) * (4.17/100);
			
			//Deducciones
			salud = (total_salario) * (4.00/100);
			pension_col = (total_salario) * (4.00/100);
			parafiscales = (total_salario) * {{((float)$pais->porcentaje_parafiscales/100)}};
			eps = (total_salario) * {{((float)$pais->porcentaje_eps/100)}};
			caja_opcion = (total_salario) * {{((float)$pais->porcentaje_caja_opcion/100)}};
			icbf = (total_salario) * {{((float)$pais->porcentaje_icbf/100)}};
			sena = (total_salario) * {{((float)$pais->porcentaje_sena/100)}};
			salud_patronal = (total_salario) * (8.50/100);
			pension_patronal = (total_salario) * (12.00/100);
			if($(fsp_input).val() != ""){
				fsp = parseFloat($(fsp_input).val());
			} else {
				fsp = (total_salario) * {{((float)$pais->porcentaje_fsp/100)}};
			}
			arl = (total_salario) * {{((float)$pais->porcentaje_arl/100)}};

			campos="";

			//nuevos campos aportes
			$(total_incapacidad_input).val(total_incapacidad.toFixed(2)).trigger('change');
			$(incapacidades_input).val(incapacidades.toFixed(2)).trigger('change');
			$(aux_trans_input).val(aux_trans.toFixed(2)).trigger('change');

			$(salud_input).val(salud.toFixed(2)).trigger('change');
			$(fsp_input).val(fsp.toFixed(2)).trigger('change');
			$(pension_col_input).val(pension_col.toFixed(2)).trigger('change');
			$(parafiscales_input).val(parafiscales.toFixed(2)).trigger('change');
			$(arl_input).val(arl.toFixed(2)).trigger('change');
			$(eps_input).val(eps.toFixed(2)).trigger('change');
			$(caja_opcion_input).val(caja_opcion.toFixed(2)).trigger('change');
			$(icbf_input).val(icbf.toFixed(2)).trigger('change');
			$(sena_input).val(sena.toFixed(2)).trigger('change');
			$(salud_patronal_input).val(salud_patronal.toFixed(2)).trigger('change');
			$(pension_patronal_input).val(pension_patronal.toFixed(2)).trigger('change');

			if (tipo_seguridad_social=="porcentaje") {
				seguridad_social=parseFloat(total_salario)*(parseFloat(porcentaje_seguridad_social)/100);			
				$(seguridad_social_input).val(seguridad_social.toFixed(2));
				campos+=seguridad_social_input+",";
			}
			if (tipo_seguridad_social_p=="porcentaje") {
				seguridad_social_p=parseFloat(total_salario)*(parseFloat(porcentaje_seguridad_social_p)/100);			
				$(seguridad_social_p_input).val(seguridad_social_p.toFixed(2));
				campos+=seguridad_social_p_input+",";
			}

			//totales deducciones	
			total_deducciones = totales_inputs('tr.deducciones'+id+" input");
			calcular_total = parseFloat(total_salario) + parseFloat(total_aportes)
			total_liquido = parseFloat(total_salario) - parseFloat(total_deducciones)


		}else {
			total_salario = parseFloat(salario_base) + parseFloat(ajuste);
			if(pais==6){
					//aguinaldo exento
				//aex=503.40;
					//aguinaldo agravado
				//aga=parseFloat(total_salario)-parseFloat(aex);	
				//aguinaldo=parseFloat(aex)+parseFloat(aga)-parseFloat(impuestos);
				aguinaldo=0;
							
			}else{
				aguinaldo=total_salario*(8.33/100);
			}			
				
			fondo=total_salario*{{((float)$pais->porcentaje_pension/100)}};
			catorceavo=total_salario*(8.33/100);				
			indemnizacion=total_salario*(8.33/100);
			
			
			
			//acumulado colombia
			interes_col = total_salario*(1.00/100);
			vacaciones = total_salario*(4.17/100);

			campos="";
			if (tipo_seguridad_social=="porcentaje") {
				seguridad_social=parseFloat(total_salario)*(parseFloat(porcentaje_seguridad_social)/100);			
				$(seguridad_social_input).val(seguridad_social.toFixed(2));
				campos+=seguridad_social_input+",";
			}
			if (tipo_seguridad_social_p=="porcentaje") {
				seguridad_social_p=parseFloat(total_salario)*(parseFloat(porcentaje_seguridad_social_p)/100);			
				$(seguridad_social_p_input).val(seguridad_social_p.toFixed(2));
				campos+=seguridad_social_p_input+",";
			}

			//totales deducciones	
			total_deducciones=totales_inputs('tr.deducciones'+id+" input");
			total_aportes=totales_inputs('tr.aportes'+id+" input");	
			total_liquido=parseFloat(total_salario)+parseFloat(total_aportes)-parseFloat(total_deducciones)
		}	

		//acumulado	

		function totales_inputs(selector){

			var total = 0;	
			$(selector).each(function(){
			  	input= $(this).val();
			  	input=input ? input : 0;		    
			    total += parseFloat(input); 		  
			});
			return total;
		}

		$(catorceavo_input).val(catorceavo.toFixed(2));
		$(fondo_input).val(fondo.toFixed(2)).trigger('change');		
		$(indemnizacion_input).val(indemnizacion.toFixed(2)).trigger('change');
		$(interes_col_input).val(interes_col.toFixed(2)).trigger('change');
		$(vacaciones_input).val(vacaciones.toFixed(2)).trigger('change');
		$(aguinaldo_input).val(aguinaldo.toFixed(2)).trigger('change');
		$(total_salario_input).val(total_salario.toFixed(2));
		$(total_deducciones_input).val(total_deducciones.toFixed(2));
		$(total_aportes_input).val(total_aportes.toFixed(2));
		$(total_liquido_input).val(total_liquido.toFixed(2));

		campos+=catorceavo_input+','+ fondo_input+','+indemnizacion_input+','+aguinaldo_input+','+interes_col_input+','+vacaciones_input;
		campos_totales=total_salario_input+','+total_deducciones_input+','+total_aportes_input+','+total_liquido_input;					
				
		$(campos+","+campos_totales).css('backgroundColor', '#5cb85c');
		setTimeout(input_color, 100);			
				
		function input_color(argument) {					
			$(campos).css('backgroundColor', '#fff');
			$(campos_totales).css('backgroundColor', '#eee');

		};
			
		
	});

	@if(str_contains($fecha, $pais->bono_14))
		$(document).on('click', '.calculo_bonocatorce_todos', function(event) {

			event.preventDefault();
			total_empleados=$('.empleado_bonocatorce');
            meses="td.meses_bonocatorce";
            input_total="total_bono14_";

            total_empleados.each(function() {
            id_empleado=$(this).attr('id');
            total_meses=total_meses(meses+id_empleado+" input");
			//console.log(total_meses);
			//console.log('---');
			$('#'+input_total+id_empleado).val(total_meses.toFixed(2));

				function total_meses(meses){

					var total = 0;
                    var mes = 0;
					//recorre cada mes del empleado
					$(meses).each(function(){
					  	input= $(this).val();
					  	input=input ? input : 0;

                        if(input == 0){
                            mes++;
                        }
					    total += parseFloat(input);

					});

					return total;
				}
			});
		});
	@endif

	@if(str_contains($fecha, 'Diciembre'))
        $(document).on('change ', '.aguinaldo_acumulado, .pension_acumulado , .indemnizacion_acumulado', function(event) {

            id=$(this).attr('id');
            acumulado=$(this).val();
            acumulado=acumulado ? acumulado : 0;
            diciembre=$("#diciembre_"+id).val(acumulado);
            pais_id = pais;
            total_acumulado_sd=$("#total_"+id).attr('acumulado_sd');
            total_acumulado_sd=total_acumulado_sd ? total_acumulado_sd : 0;
            total_aguinaldo=parseFloat(total_acumulado_sd)+parseFloat(acumulado);
            $("#total_"+id).val(total_aguinaldo.toFixed(2));
            //console.log("--"+total_aguinaldo);
        });

		$(document).on('click', '.calculo_aguinaldo_todos, .calculo_indemnizacion_todos,.calculo_pension_todos', function(event) {
			id=$(this).attr('id');
            pais={{$pais->id}};
			switch(id) {
                case "aguinaldo_todos":
                    total_empleados=$('.empleado_aguinaldo');
                    meses="td.meses_aguinaldo";
                    input_total="total_aguinaldo";
                    total_empleados.each(function() {

                        id_empleado=$(this).attr('id');
                        total_meses=total_meses(meses+id_empleado+" input");
                        //console.log(total_meses);
                        //console.log('---');
                        $('#'+input_total+id_empleado).val(total_meses.toFixed(2));

                        function total_meses(meses){
                            var total = 0;
                            var mes = 0;
                            //recorre cada mes del empleado
                            $(meses).each(function(){
                                input= $(this).val();
                                input=input ? input : 0;

                                if(input == 0){
                                    mes++;
                                }
                                if(pais == 3){
                                    total = parseFloat(input);
                                }else{
                                    total += parseFloat(input);
                                }
                            });
                            if(pais == 3){
                                if(mes == 0){
                                    total = total;
                                }else{
                                    total = (total / 12) * (12-mes);
                                }
                            }else{
                                total = total / (12-mes);
                            }

                            return total;
                        }
                    });

                    break;
                case "indemnizacion_todos":
                    total_empleados=$('.empleado_indemnizacion');
                    meses="td.meses_indemnizacion";
                    input_total="total_indemnizacion";

                    total_empleados.each(function() {
                        id_empleado=$(this).attr('id');
                        total_meses=total_meses(meses+id_empleado+" input");
                        //console.log(total_meses);
                        //console.log('---');
                        $('#'+input_total+id_empleado).val(total_meses.toFixed(2));

                        function total_meses(meses){
                            var total = 0;
                            var mes = 0;
                            //recorre cada mes del empleado
                            $(meses).each(function(){
                                input= $(this).val();
                                input=input ? input : 0;

                                if(input == 0){
                                    mes++;
                                }
                                total += parseFloat(input);

                            });

                            return total;
                        }
                    });

                    break;
                case "pension_todos":
                    total_empleados=$('.empleado_pension');
                    meses="td.meses_pension";
                    input_total="total_pension";

                    total_empleados.each(function() {
                        id_empleado=$(this).attr('id');
                        total_meses=total_meses(meses+id_empleado+" input");
				//console.log(total_meses);
				//console.log('---');
				$('#'+input_total+id_empleado).val(total_meses.toFixed(2));

				function total_meses(meses){

					var total = 0;
                    var mes = 0;
					//recorre cada mes del empleado
					$(meses).each(function(){
					  	input= $(this).val();
					  	input=input ? input : 0;

                        if(input == 0){
                            mes++;
                        }
					    total += parseFloat(input);

					});

					return total;
				}
			});

			    break; 
			}
			
			$('.'+input_total).css('backgroundColor', '#5cb85c');
			setTimeout(input_color, 100);			
				
			function input_color(argument) {				
				$('.'+input_total).css('backgroundColor', '#eee');
			}
		});
		/*$(document).on('click', '.total_aguinaldo_btn, .total_indemnizacion_btn', function(event) {
			event.preventDefault();
			//totales deducciones	
			id=$(this).attr('id');
			inputs=$(this).parents('tr').attr('class');

			total_meses=total_aguinaldo('td.meses_aguinaldo'+id+" input");
			$('#total_aguinaldo'+id).val(total_meses.toFixed(2));

			$('#total_aguinaldo'+id).css('backgroundColor', '#5cb85c');
				setTimeout(input_color, 100);			
				
				function input_color(argument) {				
					$('#total_aguinaldo'+id).css('backgroundColor', '#eee');
				}
			function total_meses(meses){

				var total = 0;	
				$(meses).each(function(){
				  	input= $(this).val();
				  	input=input ? input : 0;		    
				    total += parseFloat(input); 		  
				  	//console.log(total);
				});

				return total;
			}
		});*/

	@endif
	
//////////////////////////////////////////////////////////////////////////////////
$(document).on('click', '.calculo_salario_todos', function(event) {
	event.preventDefault();
	calcular=$('.calculo_acumulados');
	
 
	$("tr.acumulados input , tr.totales input").css('backgroundColor', '#5cb85c');
	setTimeout(input_color, 100);			
				
	function input_color(argument) {

		$('tr.acumulados input ').css('backgroundColor', '#fff');
		$('tr.totales input').css('backgroundColor', '#eee');

		calcular.each(function(){		
			$(this).click();
		});

		$('tr.acumulados input ,tr.deducciones input, tr.patronales input').css('backgroundColor', '#fff');
		$('tr.totales input').css('backgroundColor', '#eee');
		
	}	
});
//////////////////////////////////////////////////////////////////////////////////
	$(document).on('submit', 'form', function(event) {	
		event.preventDefault();	
		//calculo_totales_inputs();
		
		$(this)
		.find(':input[type=submit]')
	    .attr('disabled', true)
	    .html('<i class="glyphicon glyphicon-refresh"></i> Guardando Informacion')
	    .css('color', '#000')
	    ;
	    this.submit();
	    
	});	
//////////////////////////////////////////////////////////////////////////////////
	
	
</script>
	   
@stop

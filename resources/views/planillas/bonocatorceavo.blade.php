
<div class="col-xs-12 text-right" style="margin-bottom: 10px;">
	@role(['Administradora', 'Coordinadora', 'WebMaster'])
	<button disabled 
		class="calculo_bonocatorce_todos btn btn_color"
		data-toggle="tooltip" 
		title="Se sumaran todo los meses para cada uno de los empleados"
		id="bonocatorce_todos">
		CALCULAR BONO CATORCEAVO <i class="fa fa-question-circle" ></i>
	</button>
	@endrole
</div>

<table class="table table-bordered" style="margin-bottom: 0px;">
	
	<tbody>
		@foreach($users as $user)
			<tr><td colspan="10" style="border-top: 2px red solid;padding: 0px"></td></tr>
			@php $acumulado_mes=$user->acumulado->where('oficina_id',$oficina->id) @endphp
		
			<tr>
				<td rowspan="2" style="vertical-align: middle;">
					@if($edit)				           		
						{{$user->nombre}}
						@php $acumulado_mes=$user->user->acumulado->where('oficina_id',$oficina->id) @endphp		           		
					@else
						{{$user->first_name}} {{$user->last_name}}			           			
						@php $acumulado_mes=$user->acumulado->where('oficina_id',$oficina->id) @endphp
					@endif						
				</td>

				@foreach($meses_catorceavo as $mes14)

					@if(str_contains($mes14, $year-1))
						<td class="meses_bonocatorce{{$user->id}} empleado_bonocatorce" id="{{$user->id}}">							
							<label>{{$mes14}}</label>
							@if(isset($acumulado_mes->where('m_a', $mes14)->first()->catorceavo))
								<input type="number" step="0.001" 
									name="planilla[{{$user->id}}][catorceavo_meses][{{$mes14}}]" class="form-control" 
									value="{{$acumulado_mes->where('m_a', $mes14)->first()->catorceavo}}">	
							@else
								<input type="number" value="0" step="0.001" class="form-control">
							@endif
						</td>
					@endif
				@endforeach
				{{--<td rowspan="2" style="font-size: 18px" >
					<label>Total Bono Catorceavo</label>
						<input type="text" class="form-control total_bonocatorceavo" id="total_bonocatorceavo{{$user->id}}" name="planilla[{{$user->id}}][total_bonocatorceavo]" 
						@if($edit)
							value="{{$user->aporte->bonificacion_14}}"
						@else  
							value="{{$user->catorceavo_total}}"			
						@endif >
				</td>--}}
			</tr>
			<tr>
				@foreach($meses_catorceavo as $mes14)
					@if(str_contains($mes14, $year))
						<td class="meses_bonocatorce{{$user->id}} empleado_bonocatorce" 	id="{{$user->id}}">							
							<label>{{$mes14}}</label>
							@if(isset($acumulado_mes->where('m_a', $mes14)->first()->catorceavo))
								<input type="number" step="0.001" 
									name="planilla[{{$user->id}}][catorceavo_meses][{{$mes14}}]" class="form-control" 
									value="{{$acumulado_mes->where('m_a', $mes14)->first()->catorceavo}}">	
							@else
								<input type="number" step="0.001" 
									@if($edit) 
										value="{{$user->acumulado->catorceavo}}"
									@else 
										value="{{number_format($user->salario_base/12, 2, '.', '')}}"  
									@endif class="form-control">
							@endif
						</td>
					@endif				
				@endforeach	
			</tr>
		@endforeach
	</tbody>
</table>
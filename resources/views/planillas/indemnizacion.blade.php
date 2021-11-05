
<div class="col-xs-12 text-right" style="margin-bottom: 10px;">
	@role(['Administradora','Coordinadora'])
	<button disabled 
		class="calculo_indemnizacion_todos btn btn_color"
		data-toggle="tooltip" 
		title="Se sumaran todo los meses para cada uno de los empleados"
		id="indemnizacion_todos">
		CALCULAR INDEMNIZACIONES <i class="fa fa-question-circle" ></i>
	</button>
	 @endrole
</div>

<table class="table table-bordered" style="margin-bottom: 0px;">
	
	<tbody>
		@foreach($users as $user)
		<tr ><td colspan="10" style="border-top: 2px red solid;padding: 0px"></td></tr>
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
			
			<td class="meses_indemnizacion{{$user->id}} empleado_indemnizacion" id="{{$user->id}}">							
				<label>Enero</label>
				@if(isset($acumulado_mes->where('m_a','Enero-'.$year)->first()->indemnizacion))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][indemnizacion_meses][Enero-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Enero-'.$year)->first()->indemnizacion}}">	
				@else
				<input readonly value="0" class="form-control">
				@endif
			</td>				
			<td class="meses_indemnizacion{{$user->id}}">
				
				<label>Febrero</label>
				@if(isset($acumulado_mes->where('m_a','Febrero-'.$year)->first()->indemnizacion))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][indemnizacion_meses][Febrero-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Febrero-'.$year)->first()->indemnizacion}}">
				@else
				<input readonly value="0" class="form-control">
				@endif
			</td>
			<td class="meses_indemnizacion{{$user->id}}">
				
				<label>Marzo</label>
				@if(isset($acumulado_mes->where('m_a','Marzo-'.$year)->first()->indemnizacion))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][indemnizacion_meses][Marzo-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Marzo-'.$year)->first()->indemnizacion}}">
				@else
				<input readonly value="0" class="form-control">
				@endif
			</td>
			<td class="meses_indemnizacion{{$user->id}}">
				
				<label>Abril</label>
				@if(isset($acumulado_mes->where('m_a','Abril-'.$year)->first()->indemnizacion))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][indemnizacion_meses][Abril-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Abril-'.$year)->first()->indemnizacion}}">
				@else
				<input readonly value="0" class="form-control">
				@endif
			</td>
			<td class="meses_indemnizacion{{$user->id}}">
				
				<label>Mayo</label>
				@if(isset($acumulado_mes->where('m_a','Mayo-'.$year)->first()->indemnizacion))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][indemnizacion_meses][Mayo-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Mayo-'.$year)->first()->indemnizacion}}">
				@else
				<input readonly value="0" class="form-control">
				@endif
			</td>
			<td class="meses_indemnizacion{{$user->id}}">

				<label>Junio</label>
				@if(isset($acumulado_mes->where('m_a','Junio-'.$year)->first()->indemnizacion))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][indemnizacion_meses][Junio-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Junio-'.$year)->first()->indemnizacion}}">
				@else
				<input readonly value="0" class="form-control">
				@endif
			</td>
			<td rowspan="2" style="font-size: 18px" >
				<label>Total indemnizaci¨®n</label>
				<input type="text" class="form-control total_indemnizacion" id="total_indemnizacion{{$user->id}}" name="planilla[{{$user->id}}][total_indemnizacion]" 
				@if($edit)
				value="{{$user->total_indemnizacion}}"			
				@endif >
			</td>
	</tr>
	<tr >
		<td class="meses_indemnizacion{{$user->id}}">
			
			<label>Julio</label>
			@if(isset($acumulado_mes->where('m_a','Julio-'.$year)->first()->indemnizacion))
			<input type="number" step="0.01" 
			name="planilla[{{$user->id}}][indemnizacion_meses][Julio-{{$year}}]" class="form-control" 
			value="{{$acumulado_mes->where('m_a','Julio-'.$year)->first()->indemnizacion}}">
			@else
			<input readonly value="0" class="form-control">
			@endif
		</td>
		<td class="meses_indemnizacion{{$user->id}}">
			
			<label>Agosto</label>
			@if(isset($acumulado_mes->where('m_a','Agosto-'.$year)->first()->indemnizacion))
			<input type="number" step="0.01" 
			name="planilla[{{$user->id}}][indemnizacion_meses][Agosto-{{$year}}]" class="form-control" 
			value="{{$acumulado_mes->where('m_a','Agosto-'.$year)->first()->indemnizacion}}">	
			@else
			<input readonly value="0" class="form-control">
			@endif
		</td>
		<td class="meses_indemnizacion{{$user->id}}">
			
			<label>Septiembre</label>
			@if(isset($acumulado_mes->where('m_a','Septiembre-'.$year)->first()->indemnizacion))
			<input type="number" step="0.01" 
			name="planilla[{{$user->id}}][indemnizacion_meses][Septiembre-{{$year}}]" class="form-control" 
			value="{{$acumulado_mes->where('m_a','Septiembre-'.$year)->first()->indemnizacion}}">	
			@else
			<input readonly value="0" class="form-control">
			@endif
		</td>
		<td class="meses_indemnizacion{{$user->id}}">
			
			<label>Octubre</label>
			@if(isset($acumulado_mes->where('m_a','Octubre-'.$year)->first()->indemnizacion))
			<input type="number" step="0.01" 
			name="planilla[{{$user->id}}][indemnizacion_meses][Octubre-{{$year}}]" class="form-control" 
			value="{{$acumulado_mes->where('m_a','Octubre-'.$year)->first()->indemnizacion}}">	
			@else
			<input readonly value="0" class="form-control">
			@endif
		</td>
		<td class="meses_indemnizacion{{$user->id}}">
			
			<label>Noviembre</label>
			@if(isset($acumulado_mes->where('m_a','Noviembre-'.$year)->first()->indemnizacion))
			<input type="number" step="0.01" 
			name="planilla[{{$user->id}}][indemnizacion_meses][Noviembre-{{$year}}]" class="form-control" 
			value="{{$acumulado_mes->where('m_a','Noviembre-'.$year)->first()->indemnizacion}}">	
			@else
			<input readonly value="0" class="form-control">
			@endif
		</td>
		<td class="meses_indemnizacion{{$user->id}}" >
			<label>Diciembre</label>
			<input type="number" step="0.01" id="indemnizacion{{$user->id}}" 
				class="form-control indemnizacion_acumulado"
				name="planilla[{{$user->id}}][indemnizacion_meses][Diciembre-{{$year}}]"
				@if($edit) 
					value="{{$user->acumulado->indemnizacion}}"
				@else 
					value="{{$user->inde}}"		           			
				@endif
			>
		</td>
		
		
	</tr>
	@endforeach
</tbody>
</table>
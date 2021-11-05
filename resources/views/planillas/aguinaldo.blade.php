<div class="col-xs-12 text-right" style="margin-bottom: 10px;">
	@role(['Administradora','Coordinadora'])
	<button disabled 
		class="calculo_aguinaldo_todos btn btn_color"
		data-toggle="tooltip" 
		title="Se sumaran todo los meses para cada uno de los empleados"
		id="aguinaldo_todos">
		CALCULAR AGUINALDOS <i class="fa fa-question-circle" ></i>
	</button>
 	@endrole
</div>

<table class="table table-bordered" style="margin-bottom: 0px;">

	<tbody>
		@foreach($users as $user)

		<tr><td colspan="10" style="border-top: 2px red solid;padding: 0px"></td></tr>
		@php $acumulado_mes=$user->acumulado->where('oficina_id',$oficina->id) @endphp
		<tr  >
			<td rowspan="2" style="vertical-align: middle;">
				@if($edit)				           		
				{{$user->nombre}}
				@php $acumulado_mes=$user->user->acumulado->where('oficina_id',$oficina->id) @endphp		           		
				@else
				{{$user->first_name}} {{$user->last_name}}			           			
				@php $acumulado_mes=$user->acumulado->where('oficina_id',$oficina->id) @endphp
				@endif						
			</td>

			<td class="meses_aguinaldo{{$user->id}} empleado_aguinaldo" id="{{$user->id}}">
				<label >Enero</label>
				@if(isset($acumulado_mes->where('m_a','Enero-'.$year)->first()->aguinaldo))
				<input type="number" step="0.01"
				class="form-control "  
				name="planilla[{{$user->id}}][aguinaldo_meses][Enero-{{$year}}]" 
				value="{{$acumulado_mes->where('m_a','Enero-'.$year)->first()->aguinaldo}}">
				@else
				<input type="number" value="0" class="form-control">
				@endif
			</td>				

			<td class="meses_aguinaldo{{$user->id}}">

				<label>Febrero</label>
				@if(isset($acumulado_mes->where('m_a','Febrero-'.$year)->first()->aguinaldo))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][aguinaldo_meses][Febrero-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Febrero-'.$year)->first()->aguinaldo}}">
				@else
				<input type="number" value="0" class="form-control">
				@endif
			</td>

			<td class="meses_aguinaldo{{$user->id}}">

				<label>Marzo</label>
				@if(isset($acumulado_mes->where('m_a','Marzo-'.$year)->first()->aguinaldo))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][aguinaldo_meses][Marzo-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Marzo-'.$year)->first()->aguinaldo}}">
				@else
				<input type="number" value="0" class="form-control">
				@endif
			</td>

			<td class="meses_aguinaldo{{$user->id}}">

				<label>Abril</label>
				@if(isset($acumulado_mes->where('m_a','Abril-'.$year)->first()->aguinaldo))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][aguinaldo_meses][Abril-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Abril-'.$year)->first()->aguinaldo}}">
				@else
				<input type="number" value="0" class="form-control">
				@endif
			</td>

			<td class="meses_aguinaldo{{$user->id}}">

				<label>Mayo</label>
				@if(isset($acumulado_mes->where('m_a','Mayo-'.$year)->first()->aguinaldo))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][aguinaldo_meses][Mayo-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Mayo-'.$year)->first()->aguinaldo}}">
				@else
				<input type="number" value="0" class="form-control">
				@endif
			</td>

			<td class="meses_aguinaldo{{$user->id}}">

				<label>Junio</label>
				@if(isset($acumulado_mes->where('m_a','Junio-'.$year)->first()->aguinaldo))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][aguinaldo_meses][Junio-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Junio-'.$year)->first()->aguinaldo}}">
				@else
				<input type="number" value="0" class="form-control">
				@endif
			</td>

			<td rowspan="2" style="font-size: 18px" >
				<label>Total Aguinaldo</label>
				<input type="text" class="form-control total_aguinaldo" id="total_aguinaldo{{$user->id}}" name="planilla[{{$user->id}}][total_aguinaldo]" 
				@if($edit)
				value="{{$user->total_aguinaldo}}"			
				@endif >				
			</td>
		</tr>
		<tr>
			<td class="meses_aguinaldo{{$user->id}}">

				<label>Julio</label>
				@if(isset($acumulado_mes->where('m_a','Julio-'.$year)->first()->aguinaldo))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][aguinaldo_meses][Julio-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Julio-'.$year)->first()->aguinaldo}}">
				@else
				<input type="number" value="0" class="form-control">
				@endif
			</td>

			<td class="meses_aguinaldo{{$user->id}}">

				<label>Agosto</label>
				@if(isset($acumulado_mes->where('m_a','Agosto-'.$year)->first()->aguinaldo))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][aguinaldo_meses][Agosto-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Agosto-'.$year)->first()->aguinaldo}}">
				@else
				<input type="number" value="0" class="form-control">
				@endif
			</td>

			<td class="meses_aguinaldo{{$user->id}}">

				<label>Septiembre</label>
				@if(isset($acumulado_mes->where('m_a','Septiembre-'.$year)->first()->aguinaldo))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][aguinaldo_meses][Septiembre-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Septiembre-'.$year)->first()->aguinaldo}}">
				@else
				<input type="number" value="0" class="form-control">
				@endif
			</td>

			<td class="meses_aguinaldo{{$user->id}}">

				<label>Octubre</label>
				@if(isset($acumulado_mes->where('m_a','Octubre-'.$year)->first()->aguinaldo))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][aguinaldo_meses][Octubre-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Octubre-'.$year)->first()->aguinaldo}}">
				@else
				<input type="number" value="0" class="form-control">
				@endif
			</td>

			<td class="meses_aguinaldo{{$user->id}}">

				<label>Noviembre</label>
				@if(isset($acumulado_mes->where('m_a','Noviembre-'.$year)->first()->aguinaldo))
				<input type="number" step="0.01" 
				name="planilla[{{$user->id}}][aguinaldo_meses][Noviembre-{{$year}}]" class="form-control" 
				value="{{$acumulado_mes->where('m_a','Noviembre-'.$year)->first()->aguinaldo}}">
				@else
				<input type="number" value="0" class="form-control">
				@endif
			</td>

			<td class="meses_aguinaldo{{$user->id}}">
				<label>Diciembre</label>
				<input id="diciembre_aguinaldo{{$user->id}}" name="planilla[{{$user->id}}][aguinaldo_meses][Diciembre-{{$year}}]"
				class="form-control" 
				@if($edit)							
				value="{{$acumulado_mes->where('m_a','Diciembre-'.$year)->first()->aguinaldo}}"
				@else 
				value="{{$user->ag}}"
				@endif
				>
			</td>


		</tr>
		@endforeach
	</tbody>
</table>

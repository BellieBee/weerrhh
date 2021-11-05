
<form method="post" action='{{url("/user/liquidacion/$user->id/desactivacion")}}'>
	{!! csrf_field() !!}
@if($pais->pago_indemnizacion="retiro")

<div class="panel panel-default">
<div class="panel-heading" ><h3 style="margin: 0px;"><b>Indemnización</b></h3></div>      
    <div class="panel-body">
		<div class="row">
			<div class="col-md-12 text-right" style="margin-bottom: 20px;">
				<button class="calcular_total btn btn_color" id="indemnizacion">Calcular Indemnización</button>
			</div>
			<div class="col-md-12">
				<table class="table table-bordered table-hover">
					
					<tbody>
						@for ($i = $año_entrante; $i < date('Y')+2; $i++)
						<tr ><td colspan="10" style="border-top: 2px red solid;padding: 0px"></td></tr>
						   	<tr>
						   		<td rowspan="2" style="vertical-align: middle;font-size:19px;"><b>{{$i}}</b></td>
						   		@foreach($meses as $mes)
							   		@php $m_a=$mes.'-'.$i @endphp
									<td >
										<label>{{$mes}}</label>
										@if($acumulado_mes->where('m_a',$m_a)->first())

										<input type="number" step="0.01" 
										name="indemnizacion[{{$m_a}}]" 
										class="form-control meses_indemnizacion" 
										value="{{$acumulado_mes->where('m_a',$m_a)->first()->indemnizacion}}">
										@else
										<input readonly value="0" class="form-control">
										@endif
									</td>
									@if($mes=="Junio")
										</tr>	
										<tr>
									@endif
								@endforeach						
							</tr>								
						
						@endfor
					</tbody>
				</table>
				
			</div><!--col-->
			<div class="col-xs-12 text-right ">
					<b>Total indemnización</b>:
					<input type="text" class="form-control  total_indemnizacion" style="width:auto;display:inline-block;" 
					name="indemnizacion" disabled value='{{number_format($acumulado_mes->sum("indemnizacion"),2)}}'>
				</div><!--col-->
		</div> 
    </div>
</div>
@endif


@if($pais->pago_pension="retiro")

<div class="panel panel-default">  
<div class="panel-heading" ><h3 style="margin: 0px;"><b>Pensión</b></h3></div>  
    <div class="panel-body">
		<div class="row">
			<div class="col-md-12 text-right" style="margin-bottom: 20px;">
				<button class="calcular_total btn btn_color" id="pension">Calcular Pensión</button>
			</div>
			<div class="col-md-12">
				<table class="table table-bordered table-hover">
					<tbody>
						@for ($i = $año_entrante; $i < date('Y')+2; $i++)
						<tr ><td colspan="10" style="border-top: 2px red solid;padding: 0px"></td></tr>
						   	<tr>
						   		<td rowspan="2" style="vertical-align: middle;font-size:19px;"><b>{{$i}}</b></td>
						   		@foreach($meses as $mes)
							   		@php $m_a=$mes.'-'.$i @endphp
									<td >
										<label>{{$mes}}</label>
										@if($acumulado_mes->where('m_a',$m_a)->first())

										<input type="number" step="0.01" 
										name="pension[{{$m_a}}]" 
										class="form-control meses_pension" 
										value="{{$acumulado_mes->where('m_a',$m_a)->first()->pension}}">
										@else
										<input readonly value="0" class="form-control">
										@endif
									</td>
									@if($mes=="Junio")
										</tr>	
										<tr>
									@endif
								@endforeach						
							</tr>								
						
						@endfor
					</tbody>
				</table>
				
			</div><!--col-->
			<div class="col-xs-12 text-right">
				<b>Total Pensión</b>: <input type="text" class="form-control total_pension" style="width:auto;display:inline-block;" 
				name="pension" disabled value='{{number_format($acumulado_mes->sum("pension"),2)}}'>
			</div><!--col-->
		</div> 
    </div>
</div>
@endif
@if($pais->pago_pension="retiro" || $pais->pago_indemnizacion="retiro")
<div class="text-right">
<button type="submit" class="btn btn_color">Desactivar y liquidar empleado</button>
</div>
@endif
</form>




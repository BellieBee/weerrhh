<div class="panel panel-default">
		<div class="panel-heading" ><b>Encabezado</b></div>
		<div class="panel-body">

			<div class="form-horizontal " style="text-align: left;">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="col-sm-4 control-label">Oficina:</label>
						<div class="col-sm-8">
							<input type="text" disabled class="form-control" value="{{$oficina->oficina}}">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Fecha:</label>
						<div class="col-sm-8">
							<input type="text" readonly class="form-control" name="m_a" value="{{$fecha}}">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Fecha de Elaboración:</label>
						<div class="col-sm-8">
							<input type="text" disabled class="form-control" value="{{date('d-m-Y')}}">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Usuario:</label>
						<div class="col-sm-8">
							<input type="text"  class="form-control" disabled
							value="{{$administradora->first_name}} {{$administradora->last_name}}">
						</div>
					</div>
					@if(Entrust::hasRole('Administradora') || Entrust::hasRole('Coordinadora') && auth()->user()->oficina_id==1)
					<div class="form-group">
						<label class="col-sm-4 control-label">Planilla Confirmada:</label>
						<div class="col-sm-8" >
							<select name="confirmada" class="form-control">
								<option @if($edit && !$planilla->confirmada)selected @endif value="0">En revisión</option>  
								<option @if($edit && $planilla->confirmada) selected @endif value="1">Confirmada</option> 	
							</select>
						</div>						  
					</div>
					@endrole  
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="col-sm-5 control-label">Aprobación Coordinadora</label>
						<div class="col-sm-7">
							<input type="text" disabled class="form-control" 
							@if($edit)							 
							 	value="{{$planilla->aprobacion_coordinadora?'Aprovado':'Pendiente'}}" 
							 @endif >
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-5 control-label">Fecha de aprobación Coordinadora</label>
						<div class="col-sm-7">
							<input type="text" disabled class="form-control"
							@if($edit) value="{{$planilla->fecha_aprobacion_coordinadora}}" @endif>
						</div>
					</div>

					<div class="form-group">
						<label  class="col-sm-5 control-label">Aprobación Directora</label>
						<div class="col-sm-7">
							<input type="text" disabled class="form-control" 
							@if($edit) value="{{$planilla->aprobacion_directora?'Aprovado':'Pendiente'}}" @endif >
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-5 control-label">Fecha de aprobación Directora</label>
						<div class="col-sm-7">
							<input type="text" disabled class="form-control" 
							@if($edit) value="{{$planilla->fecha_aprobacion_directora}}" @endif>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label" 
						title="Cuanto vale 1{{$pais->moneda_simbolo}} en Corona sueca" 
						data-toggle="tooltip" 
						data-placement="top"> 
						Tipo de cambio a Corona Sueca <i class="fa fa-question-circle" ></i>
					</label>
						<div class="col-sm-4">
							<input type="number" required name="cambio" class="form-control" step="0.01" 

							@if($edit) value="{{$planilla->cambio}}" @endif>
						</div>
					</div>
				</div>
			</div>
			
			
				<div class="col-xs-12 text-right">
					@if(Entrust::hasRole(['Directora','Coordinadora','Contralora']) && $edit)
					
					@if(
					Entrust::hasRole('Directora') && !$planilla->aprobacion_directora ||
					Entrust::hasRole('Contralora') && !$planilla->aprobacion_coordinadora && $oficina->id==1 ||
					Entrust::hasRole('Coordinadora') && !$planilla->aprobacion_coordinadora && $oficina->id!=1
					)	
					 	@if($planilla->confirmada)
					 	<a href='{{url("/aprobacion/planilla/$planilla->id")}}' class="btn btn_color">
							<i class="fa fa-check-square" aria-hidden="true"></i> Aprobar Planilla
						</a>  
						@else
						<span title="Esta planilla aun sigue en revision debe esperar a ser confirmada" data-toggle="tooltip" data-placement="top">
			                <button disabled class="btn btn_color">
			                	<i class="fa fa-check-square" aria-hidden="true"></i> Aprobar Planilla
			                </button>
			            </span>
						@endif 
						 	 	
					@endif
					@endif
				</div>			
		</div>
	</div>
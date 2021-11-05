@extends('layouts.app')

@section('page-title', 'Planillas')

@section('content')

	<h1 class="page-header">Planillas</h1>

	@include('partials.messages')

	
	@if(Entrust::hasRole('Administradora') || Entrust::hasRole('Coordinadora') && auth()->user()->oficina_id==1)
	<div class="row" style="margin-bottom: 20px;">
		<div class="col-xs-12 text-right">
			<form class="form-inline"  action="{{url('/crear/planilla')}}" method="get">   
			   	<div class="form-group ">                   
			       <select class="form-control" name="fecha">
			       	<option >Enero-{{date('Y')}}</option>
			       	<option >Febrero-{{date('Y')}}</option>
			       	<option >Marzo-{{date('Y')}}</option>
			       	<option >Abril-{{date('Y')}}</option>
			       	<option >Mayo-{{date('Y')}}</option>
			       	<option >Junio-{{date('Y')}}</option>
			       	<option >Julio-{{date('Y')}}</option>
			       	<option >Agosto-{{date('Y')}}</option>
			       	<option >Septiembre-{{date('Y')}}</option>
			       	<option >Octubre-{{date('Y')}}</option>
			       	<option >Noviembre-{{date('Y')}}</option>
			       	<option >Diciembre-{{date('Y')}}</option>
			       	<option >Enero-{{date('Y')+1}}</option>
			       </select>
			    </div>
			    <div class="form-group ">
			    	<button type="submit" class="btn btn_color btn_azul">Crear Planilla</button>
			    </div>
			</form>
		</div>
	</div>
	@endrole
	

	<table class="table dataTables-empleados table_planillas table-bordered table-hover">
		<thead>
			<tr>
				<th>#</th>			
				<th>Generada Por</th>			                             
				<th>Oficina</th>                                
				<th width="100">Mes Año</th>                                
				<th>Fecha de Elaboración</th>
				<th>Revisión Administradora</th>
				<th>Aprobación Coordinadora</th>		
				<th>Aprobación Directora</th>		
				<th>Opciones</th>		
				<th>PDF</th>		
			</tr>
		</thead>		
		<tbody>
			
			@foreach($planillas as $planilla)
				<tr>
					<td>{{$planilla->id}}</td>
					<td>{{$planilla->administradora->first_name}} {{$planilla->administradora->last_name}}</td>
					
					<td>{{$planilla->oficina->oficina}}</td>		
					<td>{{$planilla->m_a}}</td>
					<td>{{$planilla->created_at->format('Y-m-d')}}</td>	
					<td>
						
						<!--<label style="margin-bottom: 0px;">
								{{$planilla->administradora->first_name}}
								{{$planilla->administradora->last_name}}								
						</label><br>-->
						@if($planilla->confirmada)						
							<span style="font-size:90%;font-weight:normal;" class="label label-success "><i class="fa fa-check"></i> Revisada</span>		
						@else								
							<span style="font-size:90%;font-weight:normal;" class="label label-warning ">En revisión</span>
						@endif
					</td>			
					<td>
						@if($planilla->aprobacion_coordinadora)
							
							<!--<label style="margin-bottom: 0px;">
								{{$planilla->coordinadora->first_name}}
								{{$planilla->coordinadora->last_name}}								
							</label><br>-->
							<span style="font-size:90%;font-weight:normal;" class="label label-success "><i class="fa fa-check"></i> Aprobado</span>		
						@else
							<span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>
						@endif
					</td>
					<td>
						@if($planilla->aprobacion_directora)
							<!--<label style="margin-bottom: 0px;">
								{{$planilla->directora->first_name}}
								{{$planilla->directora->last_name}}								
							</label><br>-->
							<span style="font-size:90%;font-weight:normal;" class="label label-success "><i class="fa fa-check"></i> Aprobado</span>			
						@else
							<span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>
						@endif

					</td>
					<td>
							@role(['Directora','Coordinadora','Contralora','Admin'])					
								<a href='{{url("/edit/planilla/$planilla->id")}}' class="btn btn_color btn_azul  "> Ver</a>
							@endrole			
							@role(['Administradora','Coordinadora'])
							<a href='{{url("/edit/planilla/$planilla->id")}}' class="btn btn_color btn_azul"
								title="Editar PLanilla {{$planilla->m_a}}"
		                        data-toggle="tooltip"
		                        data-placement="top">
							 	<i class="glyphicon glyphicon-pencil fa-fw"></i>
							</a>
							@endrole

							@if(Entrust::hasRole('Administradora') || Entrust::hasRole('Admin') || Entrust::hasRole('Coordinadora') && $planilla->oficina_id==1)
							<a href='{{ url("/delete/planilla/$planilla->id") }}'  class="btn btn_color btn_azul" 
								title="Eliminar Planilla {{$planilla->m_a}}"
		                        data-toggle="tooltip"
		                        data-placement="top"
		                        data-method="DELETE"
		                        data-confirm-title="Eliminar planilla {{$planilla->m_a}}"
		                        data-confirm-text="Esta seguro de querer eliminar esta planilla"
		                        data-confirm-delete="Borrar"
		                        style="background: #F44336;" >
		                        <i class="glyphicon glyphicon-trash"></i>
		                    </a>					
							@endif

											
						
					
					</td>
					<td>
						@if(Entrust::hasRole(['Directora', 'Coordinadora']) && !$planilla->confirmada) 
							<span title="Debe esperar que la administradora finalice la revisión"
			                    data-toggle="tooltip"
			                    data-placement="top">
								<a 					 
									class=" btn btn_color btn_rojo  descargar_planilla" disabled>						
									PDF 
								</a>
							</span>	
						@else
						<a href='{{url("/descargar/planilla/$planilla->id")}}' target="_blank" 						 
							class=" btn btn_color btn_rojo  descargar_planilla">						
							PDF
						</a>
						@endif
						
					</td>		
					

				</tr>
			@endforeach
		</tbody>
	</table>

	
	
@stop
@section('scripts')
{!! HTML::script('assets/js/moment.min.js') !!}
{!! HTML::script('assets/js/moment_es.js') !!}

{!! HTML::script('assets/js/bootstrap-datepicker.min.js') !!}
{!! HTML::script('assets/js/bootstrap-datepicker.es.min.js') !!}	
<script>
				/*$('.descargar_planilla').click(function(event) {
					$(this).attr('disabled', true);

					setInterval(function(){
						$(this).removeAttr('disabled');
					},1000)
				});*/
				

				$('.dataTables-empleados').DataTable({

					"order": [[ 0, "desc" ]],
					"info": false,					
					"lengthChange": false,

					"pageLength": 20,
					"language": {
						"sProcessing":     "Procesando...",
						"sLengthMenu":     "Mostrar _MENU_ registros",
						"sZeroRecords":    "No se encontraron resultados",
						"sEmptyTable":     "Ningún dato disponible en esta tabla",
						"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
						"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
						"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
						"sInfoPostFix":    "",
						"sSearch":         "Buscar:",
						"sUrl":            "",
						"sInfoThousands":  ",",
						"sLoadingRecords": "Cargando...",
						"oPaginate": {
							"sFirst":    "Primero",
							"sLast":     "Último",
							"sNext":     "Siguiente",
							"sPrevious": "Anterior"
						},
						"oAria": {
							"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
							"sSortDescending": ": Activar para ordenar la columna de manera descendente"
						}                    
					}
				});
					


			</script>
			@stop
@section('styles')
    {!! HTML::style('assets/css/bootstrap-datepicker.min.css') !!}
    {!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop
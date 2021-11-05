@extends('layouts.app')

@section('page-title', 'Recepciones')

@section('content')

	<h1 class="page-header">Recepcion de documentos</h1>

	@include('partials.messages')

	
	@role('Administradora')
       <div class="row" style="margin-bottom: 20px;">
		    <div class="col-xs-12 text-right">	        

		        @role('Administradora')
		        <form class="form-inline"  action="{{url('/recepciones/create')}}" method="post">   
		            {!! csrf_field() !!}
		            <div class="form-group ">                   
		                <select class="form-control" name="id" required>
		                    <option></option>
		                    @foreach($users as $user)
		                        <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
		                    @endforeach
		                </select>
		            </div>
		            <div class="form-group ">
		                <button type="submit" class="btn btn_color btn_azul">Crear Recepcion</button>
		            </div>
		        </form>
		        @endrole
		    
		    </div>

		</div>
    @endrole
	

	<table class="table dataTables-empleados table_planillas table-bordered table-hover">
		<thead>
			<tr>
				<th>Empleado</th>			                             
				<th>Oficina</th>                          
				<th>Fecha en que se recibe</th>
				<th>documentos</th>					
				<th>Recogido</th>					
				<th>Opciones</th>	
			</tr>
		</thead>		
		<tbody>
			
			@foreach($recepciones as $recepcion)
			<tr id="{{$recepcion->id}}">
				<td>{{$recepcion->user->first_name}}</td>
				<td>{{$recepcion->oficina->oficina}}</td>
				<td>{{$recepcion->fecha_recibe}}</td>
				<td>
					@foreach($recepcion->documentos as $documento)
					<span  class="label label_status label-primary"> {{$documento->titulo}}</span>
					@endforeach
				</td>
				<td>{{$recepcion->recogido?'SI':'NO'}}</td>
				<td>
					<div class="dropdown">
                        <button class="btn btn_gris dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                            Opciones
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                            <li >
                                <a href='{{url("/recepciones/$recepcion->id/edit")}}'>
			                    @if(Entrust::hasRole(['Coordinadora','Directora','Contralora','Admin']) || $recepcion->recogido)                        
			                       <i class="glyphicon glyphicon-pencil"></i> Ver
			                    @else                    
			                       <i class="glyphicon glyphicon-pencil"></i> Editar
			                    @endif

			                    </a>
                            </li>                       
                            
                            @role('Administradora')				               
                            <li >
				                <a href='{{ url("/recepciones/$recepcion->id/delete") }}'
				                    title="Eliminar recepcion "
				                    data-toggle="tooltip"
				                    data-placement="top"
				                    data-method="DELETE"
				                    data-confirm-title="Eliminar recepcion"
				                    data-confirm-text="Esta seguro de querer eliminar este recepcion"
				                    data-confirm-delete="Borrar"
				                	><i class="glyphicon glyphicon-trash"></i>	Borrar
				                </a>    
                            </li>
                            @endrole

                            <li class="divider"></li>
                            
                            <li >                         
                               <a href='{{url("/recepciones/$recepcion->id/email")}}' class="ENVIAR_EMAIL"><i class="glyphicon glyphicon-envelope"></i> Enviar correo de notificacion </a>        
                            </li> 
                            @if(!$recepcion->recogido)
                            <li>
                               <a href='{{url("/recepciones/$recepcion->id/recogido")}}' class="MARCAR_RECOGIDO"><i class="glyphicon glyphicon-ok"></i> Marcar como recogido </a>        
                            </li>
                            @endif                    
                        </ul>
                    </div> 
        
				</td>
			</tr>	
			@endforeach
		</tbody>
	</table>

	
	
@stop
@section('scripts')

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

				$(document).on('click', '.ENVIAR_EMAIL, .MARCAR_RECOGIDO', function(event) {
					
					boton=$(this).closest('tr').find('.dropdown-toggle');
					console.log(boton);
					boton.html('<i class="glyphicon glyphicon-refresh"></i> Cargando..')
					/* Act on the event */
				});
					


			</script>
			@stop
@section('styles')
    {!! HTML::style('assets/css/bootstrap-datepicker.min.css') !!}
    {!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop
@extends('layouts.app')

@section('page-title', 'Actividades')

@section('content')

<h1 class="page-header">Actividades</h1>

@include('partials.messages')

    <div class="row" style="margin-bottom: 20px;">
        <div class="col-xs-12 text-right">
        
            @if(Entrust::can(['crear-actividad-todos','crear-actividad-oficina']))
                <form class="form-inline"  action="{{url('/actividad/create')}}" method="get">   
                    <div class="form-group ">                   
                        <select class="form-control" name="id" required>
                            <option disabled selected>Seleccione un Colega</option>
                            @foreach($colegas as $colega)
                                <option value="{{$colega->id}}">{{$colega->first_name}} {{$colega->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group ">
                        <button type="submit" class="btn btn_color btn_azul">Solicitud de Actividad</button>
                    </div>
                </form>
            @else
                <a href="{{url('actividad/create')}}"  class="btn btn_color btn_azul">Solicitud de Actividad</a href="!#">
            @endif
    
        </div>
    </div>

    <table class="table dataTables-empleados table_planillas table-bordered table-hover">
        <thead>
            <tr>
                <th>Oficina</th>
                <th>Nº Solicitud</th>
                <th>Actividad</th>
                <th>Tipo de Compra</th>
                <th>Nombre</th>         
                <th>Fecha de Solicitud</th>
                <th>Aprobación 1</th>
                <th>Aprobación 2</th>
                <th>Opciones</th>       
            </tr>
        </thead>
        <tbody>
        @foreach($actividades as $actividad)
            <tr>
                <td>{{$actividad->oficina->oficina}}</td>
                <td>{{$actividad->correlativo}}</td>
                <td>{{$actividad->actividad}}</td>
                <td>{{$actividad->tipo_compra->valor_generico}}</td>
                <td>{{$actividad->user->first_name}} {{$actividad->user->last_name}}</td>
                    
                <td>
                    {{date('d-m-Y', strtotime($actividad->fecha))}}
                </td>

                <td>
                    @if($actividad->aprobacion_1==0)                            
                        <span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>          
                    @elseif($actividad->aprobacion_1==1)          
                        <span style="font-size:90%;font-weight:normal;" class="label label-success "><i class="fa fa-check"></i> Aprobado</span>
                    @elseif($actividad->aprobacion_1==2)
                        <span style="font-size:90%;font-weight:normal;" class="label label-danger "><i class="fa fa-close"></i> Rechazado</span>
                    @elseif($actividad->aprobacion_1==3)
                        <span style="font-size:90%;font-weight:normal;" class="label label-info "><i class="fa fa-close"></i> Anulado</span>
                    @endif

                </td>               
                   
                <td>
                    @if($actividad->aprobacion_2==0)                            
                        <span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>          
                    @elseif($actividad->aprobacion_2==1)          
                        <span style="font-size:90%;font-weight:normal;" class="label label-success "><i class="fa fa-check"></i> Aprobado</span>
                    @elseif($actividad->aprobacion_2==2)
                        <span style="font-size:90%;font-weight:normal;" class="label label-danger "><i class="fa fa-close"></i> Rechazado</span>
                    @elseif($actividad->aprobacion_2==3)
                        <span style="font-size:90%;font-weight:normal;" class="label label-info "><i class="fa fa-close"></i> Anulado</span>
                    @endif

                </td>
                
                <td>         
                        
                    @if(Entrust::can('editar-actividad') /*&& $actividad->aprobacion_1 == 0 || $actividad->aprobacion_2 == 0*/)
                        <a href='{{url("/actividad/$actividad->id/edit")}}' class="btn btn_color">
                            Editar </a>
                    @endif

                    @if(Entrust::can('ver-actividad-info'))
                        <a href='{{url("/actividad/$actividad->id/show")}}' class="btn btn_color">
                            Ver </a>
                    @endif
                        
                    @if(Entrust::can('eliminar-actividad') /*&& $actividad->aprobacion_1 == 0 && $actividad->aprobacion_2 == 0*/)
                        
                        <a href='{{ url("/actividad/$actividad->id/delete") }}'  class="btn btn_color btn_rojo" 
                            title="Eliminar Solicitud de Actividad"
                            data-toggle="tooltip"
                            data-placement="top"
                            data-method="DELETE"
                            data-confirm-title="Eliminar Solicitud de Actividad"
                            data-confirm-text="Esta seguro de querer eliminar esta Solicitud de Actividad"
                            data-confirm-delete="Borrar">
                            Borrar
                        </a>    
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@stop
@section('scripts')

    <script>

        $('.dataTables-empleados').DataTable({
                    
            "info": false,
            "pageLength": 15,
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
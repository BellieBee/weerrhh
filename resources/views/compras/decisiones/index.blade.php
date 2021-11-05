@extends('layouts.app')

@section('page-title', 'Decisiones')

@section('content')

<h1 class="page-header">Decisiones</h1>

@include('partials.messages')

<table class="table dataTables-empleados table_planillas table-bordered table-hover">
    <thead>
        <tr>
            <th>Oficina</th>
            <th>No Solicitud</th>
            <th>Decisión</th>
            <th>Nombre</th>         
            <th>Fecha de Decision</th>
            <th>Aprobación 1</th>
            <th>Aprobación 2</th>
            <th>Opciones</th>       
        </tr>
    </thead>
    <tbody>
        @foreach($decisiones as $decision)
            <tr>
                <td>{{$decision->oficina->oficina}}</td>
                <td>{{$decision->correlativo}}</td>
                <td>{{$decision->decision}}</td>
                <td>{{$decision->actividad->admin->first_name}} {{$decision->actividad->admin->last_name}}</td>
                    
                <td>
                    {{date('d-m-Y', strtotime($decision->fecha))}}
                </td>

                <td>
                    @if($decision->aprobacion_1==0)
                        <span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>          
                    @elseif($decision->aprobacion_1==1)          
                        <span style="font-size:90%;font-weight:normal;" class="label label-success "><i class="fa fa-check"></i> Aprobado</span>
                    @elseif($decision->aprobacion_1==2)
                        <span style="font-size:90%;font-weight:normal;" class="label label-danger "><i class="fa fa-close"></i> Rechazado</span>
                    @elseif($decision->aprobacion_1==3)
                        <span style="font-size:90%;font-weight:normal;" class="label label-info "><i class="fa fa-close"></i> Anulado</span>
                    @endif

                </td>               
                   
                <td>
                    @if($decision->aprobacion_2==0)                            
                        <span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>          
                    @elseif($decision->aprobacion_2==1)          
                        <span style="font-size:90%;font-weight:normal;" class="label label-success "><i class="fa fa-check"></i> Aprobado</span>
                    @elseif($decision->aprobacion_2==2)
                        <span style="font-size:90%;font-weight:normal;" class="label label-danger "><i class="fa fa-close"></i> Rechazado</span>
                    @elseif($decision->aprobacion_2==3)
                        <span style="font-size:90%;font-weight:normal;" class="label label-info "><i class="fa fa-close"></i> Anulado</span>
                    @endif

                </td>
                <td>         
                        
                    @if(Entrust::can('editar-decision') /*&& $decision->aprobacion_1 == 0 || $decision->aprobacion_2 == 0*/)
                        <a href='{{url("/decision/$decision->id/edit")}}' class="btn btn_color">
                            Editar </a>
                    @endif

                    @if(Entrust::can('ver-decision-info'))
                        <a href='{{url("/decision/$decision->id/show")}}' class="btn btn_color">
                            Ver </a>
                    @endif

                    @if(Entrust::can('descargar-decision') /*&& $decision->aprobacion_1 != 0 && $decision->aprobacion_2 != 0*/)
                        <a href='{{route('decision.download', $decision->id)}}' class="btn btn_color btn_rojo">
                            PDF</a>
                    @endif
                        
                    @if(Entrust::can('eliminar-decision') /*&& $decision->aprobacion_1 == 0 && $decision->aprobacion_2 == 0*/)
                        
                        <a href='{{ url("/decision/$decision->id/delete") }}'  class="btn btn_color btn_rojo" 
                            title="Eliminar Decisión"
                            data-toggle="tooltip"
                            data-placement="top"
                            data-method="DELETE"
                            data-confirm-title="Eliminar Decisión"
                            data-confirm-text="Esta seguro de querer eliminar esta Decisión"
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
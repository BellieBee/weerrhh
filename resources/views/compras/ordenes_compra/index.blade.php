@extends('layouts.app')

@section('page-title', 'Ordenes de Compra')

@section('content')

<h1 class="page-header">Ordenes de Compra</h1>

@include('partials.messages')

    <table class="table dataTables-empleados table_planillas table-bordered table-hover">
        <thead>
            <tr>
                <th>Oficina</th>
                <th>No Actividad</th>
                <th>No Orden de Compra</th>
                <th>Nombre</th> 
                <th>Proveedor</th>
                <th>Tipo de Compra</th>        
                <th>Fecha de Solicitud</th>                
                <th>Aprobación Solicitante</th>
                <th>Primera Aprobación</th>
                <th>Segunda Aprobación</th>
                <th>Opciones</th>       

            </tr>
        </thead>
        <tbody>

            @foreach($ordenes_compra as $orden)
                <tr>
                    <td>{{$orden->oficina->oficina}}</td>
                    <td>{{$orden->actividad_id != null ? $orden->actividad->correlativo : $orden->decision->actividad->correlativo}}</td>
                    <td>{{$orden->correlativo}}</td>
                    <td>{{$orden->solicitante->first_name}} {{$orden->solicitante->last_name}}</td>
                    <td>{{$orden->proveedor_id != 0 ? $orden->proveedor->razon_social : $orden->proveedorUser->first_name.' '. $orden->proveedorUser->last_name}}</td>
                    <td>{{$orden->actividad_id != null ? $orden->actividad->tipo_compra->valor_generico : $orden->decision->actividad->tipo_compra->valor_generico}}</td>
                    <td>
                        {{date('d-m-Y', strtotime($orden->fecha))}} 
                        @if(date('d-m-Y', strtotime($orden->fecha)) == date('d-m-Y')) 
                            <span class="label label-success"> Hoy</span>
                        @endif
                    </td>               
                   
                    <td>
                        @if($orden->aprobacion_solicitante == 0)              
                            <span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>   
                        @elseif($orden->aprobacion_solicitante == 1)          
                            <span style="font-size:90%;font-weight:normal;" class="label label-success ">Aprobado</span>
                        @elseif($orden->aprobacion_solicitante == 2)
                            <span style="font-size:90%;font-weight:normal;" class="label label-danger ">Rechazado</span>
                        @elseif($orden->aprobacion_solicitante == 3)
                            <span style="font-size:90%;font-weight:normal;" class="label label-info ">Anulado</span>
                        @endif
                    </td>

                    <td>
                        @if($orden->aprobacion_1 == 0)              
                            <span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>   
                        @elseif($orden->aprobacion_1 == 1)          
                            <span style="font-size:90%;font-weight:normal;" class="label label-success ">Aprobado</span>
                        @elseif($orden->aprobacion_1 == 2)
                            <span style="font-size:90%;font-weight:normal;" class="label label-danger ">Rechazado</span>
                        @elseif($orden->aprobacion_1 == 3)
                            <span style="font-size:90%;font-weight:normal;" class="label label-info ">Anulado</span>
                        @endif
                    </td>

                    <td>
                        @if($orden->aprobacion_2 == 0)              
                            <span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>   
                        @elseif($orden->aprobacion_2 == 1)          
                            <span style="font-size:90%;font-weight:normal;" class="label label-success ">Aprobado</span>
                        @elseif($orden->aprobacion_2 == 2)
                            <span style="font-size:90%;font-weight:normal;" class="label label-danger ">Rechazado</span>
                        @elseif($orden->aprobacion_2 == 3)
                            <span style="font-size:90%;font-weight:normal;" class="label label-info ">Anulado</span>
                        @endif
                    </td>
                    <td>         
                        
                        @if(Entrust::can('editar-ordencompra') /*&& $orden->aprobacion_solicitante == 0 || $orden->aprobacion_1 == 0*/)
                            <a href='{{route('ordencompra.edit', $orden->id)}}' class="btn btn_color">
                                Editar </a>
                        @endif

                        @if(Entrust::can('ver-ordencompra-info'))
                            <a href='{{url("/ordencompra/$orden->id/show")}}' class="btn btn_color">
                                Ver </a>
                        @endif

                        @if(Entrust::can('descargar-ordencompra') /*&& $orden->aprobacion_solicitante !=0*/)
                            <a href='{{route('ordencompra.download', $orden->id)}}' class="btn btn_color btn_rojo">PDF</a>
                        @endif
                        
                        @if(Entrust::can('eliminar-ordencompra') && $orden->aprobacion_solicitante != 1)
                        
                            <a href='{{ route('ordencompra.delete', $orden->id) }}'  class="btn btn_color btn_rojo" 
                                title="Eliminar Solicitud de Orden de Compra "
                                data-toggle="tooltip"
                                data-placement="top"
                                data-method="DELETE"
                                data-confirm-title="Eliminar Solicitud de Orden de Compra"
                                data-confirm-text="Esta seguro de querer eliminar esta Solicitud de Orden de Compra"
                                data-confirm-delete="Borrar"
                                 >
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
@extends('layouts.app')

@section('page-title', 'Solicitudes de Pago')

@section('content')

<h1 class="page-header">Solicitudes de Pago</h1>

@include('partials.messages')

    <table class="table dataTables-empleados table_planillas table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Oficina</th>
                <th>No Actividad</th>
                <th>No Empleado</th>
                <th>Nombre</th>         
                <th>Fecha de Solicitud</th>
                <th>Concepto</th>
                <th>Proveedor</th>
                <th>Revisión</th>                
                <th>Opciones</th>       
            </tr>
        </thead>
        <tbody>

            @foreach($pagos as $pago)
                <tr>
                    <td>{{$pago->id}}</td>
                    <td>{{$pago->oficina->oficina}}</td>
                    @if($pago->actividad_id != null)
                            @if($pago->actividad != '')
                                <td>{{$pago->actividad->correlativo}}</td>
                            @else
                                <td><b>ACTIVIDAD</b> ELIMINADA</td>
                            @endif
                    @else
                        @if($pago->orden_compra_id != null)
                            @if($pago->ordencompra != '')
                                @if($pago->ordencompra->actividad != '')
                                    <td>{{$pago->ordencompra->actividad->correlativo}}</td>
                                @else
                                    <td><b>ACTIVIDAD</b> ORDENCOMPRAS ELIMINADA</td>
                                @endif
                            @else
                                <td><b>ORDENCOMPRA</b> ELIMINADA</td>
                            @endif
                        @elseif($pago->contrato_id != null)
                            @if($pago->contrato != '')
                                @if($pago->contrato->ordencompra != '')
                                    @if($pago->contrato->ordencompra->decision != '')
                                        @if($pago->contrato->ordencompra->decision->actividad != '')
                                            <td>{{$pago->contrato->ordencompra->decision->actividad->correlativo}}</td>
                                        @else
                                            <td><b>ACTIVIDAD</b> DECISION ORDENCOMPRA CONTRATO ELIMINADA</td>
                                        @endif
                                    @else
                                        <td><b>DECISION</b> ORDENCOMPRA CONTRATO ELIMINADA</td>
                                    @endif
                                @else
                                    <td><b>ORDENCOMPRA</b> CONTRATO ELIMINADA</td>
                                @endif
                            @else
                                <td><b>CONTRATO</b> ELIMINADO</td>
                            @endif
                        @endif
                    @endif
                    <td>{{$pago->user->n_contrato}}</td>
                    <td>{{$pago->user->first_name}} {{$pago->user->last_name}}</td>
                    <td>
                        {{date('d-m-Y', strtotime($pago->fecha))}} 
                        @if(date('d-m-Y', strtotime($pago->fecha)) == date('d-m-Y')) 
                            <span class="label label-success"> Hoy</span>
                        @endif
                    </td>
                    <td>{{$pago->concepto}}</td>
                    <td>{{$pago->proveedor_id != 0 ? $pago->proveedor->razon_social : $pago->proveedorUser->first_name. ' ' .$pago->proveedorUser->last_name}}</td>
                    <td>
                        @if($pago->revision == 0)              
                            <span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>   
                        @elseif($pago->revision == 1)          
                            <span style="font-size:90%;font-weight:normal;" class="label label-success ">Aprobado</span>
                        @elseif($pago->revision == 2)
                            <span style="font-size:90%;font-weight:normal;" class="label label-danger ">Rechazado</span>
                        @elseif($pago->revision == 3)
                            <span style="font-size:90%;font-weight:normal;" class="label label-info ">Anulado</span>
                        @endif
                    </td>               
                    <td>           
                        @if(Entrust::can('editar-pago'))
                            <a href='{{route('pago.edit', $pago->id)}}' class="btn btn_color">
                                Editar </a>
                        @endif

                        @if(Entrust::can('ver-pago-info'))
                            <a href='{{url("/pago/$pago->id/show")}}' class="btn btn_color">
                                Ver </a>
                        @endif

                        @if(Entrust::can('descargar-pago') /*&& $pago->revision == 1*/)
                            <a href='{{route('pago.download', $pago->id)}}' class="btn btn_color btn_rojo">PDF</a>
                        @endif
                        
                        @if(Entrust::can('eliminar-pago') && $pago->revision == 0)
                        
                            <a href='{{ route('pago.delete', $pago->id) }}'  class="btn btn_color btn_rojo" 
                                title="Eliminar Solicitud de Pago "
                                data-toggle="tooltip"
                                data-placement="top"
                                data-method="DELETE"
                                data-confirm-title="Eliminar Solicitud de Pago"
                                data-confirm-text="Esta seguro de querer eliminar esta Solicitud de Pago"
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
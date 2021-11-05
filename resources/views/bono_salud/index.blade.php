@extends('layouts.app')

@section('page-title', 'Bono Salud')

@section('content')

<h1 class="page-header">Actividad de Salud</h1>

@include('partials.messages')


<div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-12 text-right">

        
    @if(Entrust::can(['crear-bonossalud-todos','crear-bonossalud-oficina']))
        <form class="form-inline"  action="{{url('/bonosalud/create')}}" method="get">   
            <div class="form-group ">                   
                <select class="form-control" name="id" required>
                    <option></option>
                    @foreach($colegas as $colega)
                        <option value="{{$colega->id}}">{{$colega->first_name}} {{$colega->last_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group ">
                <button type="submit" class="btn btn_color btn_azul">Solicitud de Actividad de Salud</button>
            </div>
        </form>
    @else
    <a href="{{url('bonosalud/create')}}"  class="btn btn_color btn_azul">Solicitud de Actividad de Salud</a href="!#">
    @endif
    
    </div>

</div>


    <table class="table dataTables-empleados table_planillas table-bordered table-hover">
        <thead>
            <tr>
                <th>Oficina</th>
                <th>No Empleado</th>
                <th>Nombre</th>         
                <th>Fecha de Solicitud</th>                
                <th>Status</th>
                <th>Opciones</th>       

            </tr>
        </thead>
        <tbody>

            @foreach($bonos_salud as $bono)
                <tr>
                    <td>{{$bono->oficina->oficina}}</td>
                    <td>{{$bono->user->n_contrato}}</td>
                    <td>{{$bono->user->first_name}} {{$bono->user->last_name}}</td>
                    
                    <td>
                        {{date('d-m-Y', strtotime($bono->fecha_solicitud))}}
                        {{--{{$bono->fecha_solicitud->format('d-m-Y')}} 
                        @if($bono->fecha_solicitud->format('d-m-Y') == date('d-m-Y')) 
                            <span class="label label-success"> Hoy</span>
                        @endif --}}
                    </td>               
                   
                    <td>
                        @if($bono->status==0)                            
                            <span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>          
                        @elseif($bono->status==1)          
                            <span style="font-size:90%;font-weight:normal;" class="label label-success ">Aprobado</span>
                        @elseif($bono->status==2)
                            <span style="font-size:90%;font-weight:normal;" class="label label-danger ">Rechazado</span>
                        @elseif($bono->status==3)
                            <span style="font-size:90%;font-weight:normal;" class="label label-info ">Anulado</span>
                        @endif
                    </td>
                    <td>         
                        
                        @if(Entrust::can('editar-bonossalud') && $bono->status == 0)
                            <a href='{{route('bonosalud.edit', $bono->id)}}' class="btn btn_color">
                                Editar </a>
                        @endif

                        @if(Entrust::can('descargar-bonossalud') && $bono->status!=0)
                            <a href='{{route('bonosalud.download', $bono->id)}}' class="btn btn_color btn_rojo">PDF</a>
                        @endif

                        @if(Entrust::can('ver-bonossalud-info'))
                            <a href='{{route('bonosalud.show', $bono->id)}}' class="btn btn_color">
                                Ver </a>
                        @endif
                        
                        @if(Entrust::can('eliminar-bonossalud') && $bono->status==0)
                        
                            <a href='{{ route('bonosalud.delete', $bono->id) }}'  class="btn btn_color btn_rojo" 
                                title="Eliminar Solicitud de Bono Salud "
                                data-toggle="tooltip"
                                data-placement="top"
                                data-method="DELETE"
                                data-confirm-title="Eliminar Solicitud de Bono Salud"
                                data-confirm-text="Esta seguro de querer eliminar esta Solicitud de Bono Salud"
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
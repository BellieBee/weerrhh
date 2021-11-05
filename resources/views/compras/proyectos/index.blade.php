@extends('layouts.app')

@section('page-title', 'Proyectos')

@section('content')

<h1 class="page-header">Proyectos</h1>

@include('partials.messages')


<div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-12 text-right">   
        @if(Entrust::can(['crear-proyecto-todos','crear-proyecto-oficina']))  
            <a href="{{url('proyecto/create')}}"  class="btn btn_color btn_azul">Crear Proyecto</a>
        @endif
    </div>
</div>

<table class="table dataTables-empleados table_planillas table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Oficina</th>
            <th>País</th>
            <th>Proyecto</th>
            <th>Fecha Inicio</th>         
            <th>Fecha Fin</th>
            <th>Opciones</th>       
        </tr>
    </thead>
    <tbody>
        @foreach($proyectos as $key => $proyecto)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$proyecto->oficina->oficina}}</td>
                <td>{{$proyecto->pais->pais}}</td>
                <td>{{$proyecto->nombre}}</td>
                <td>{{date('d-m-Y', strtotime($proyecto->fecha_inicio))}}</td>
                <td>{{date('d-m-Y', strtotime($proyecto->fecha_fin))}}</td>     
                <td>                
                    @if(Entrust::can('editar-proyecto'))
                        <a href='{{route('proyecto.edit', $proyecto->id)}}' class="btn btn_color">
                            Editar </a>
                    @endif

                    @if(Entrust::can('ver-proyecto-info'))
                        <a href='{{route('proyecto.show', $proyecto->id)}}' class="btn btn_color">
                            Ver </a>
                    @endif

                    @if(Entrust::can('eliminar-proyecto'))
                        <a href='{{ route('proyecto.delete', $proyecto->id) }}' class="btn btn_color btn_rojo" 
                            title="Eliminar Proyecto "
                            data-toggle="tooltip"
                            data-placement="top"
                            data-method="DELETE"
                            data-confirm-title="Eliminar Proyecto"
                            data-confirm-text="Esta seguro de querer eliminar este Proyecto"
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
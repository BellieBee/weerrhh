@extends('layouts.app')

@section('page-title', 'Líneas Presupuestarias')

@section('content')

<h1 class="page-header">Líneas Presupuestarias</h1>

@include('partials.messages')


<div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-12 text-right">   
        @if(Entrust::can(['crear-lineapresupuestaria-todos','crear-lineapresupuestaria-oficina']))  
            <a href="{{url('lineapresupuestaria/create')}}"  class="btn btn_color btn_azul">Crear Línea Presupuestaria</a>
        @endif
    </div>
</div>

<table class="table dataTables-empleados table_planillas table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Oficina</th>
            <th>País</th>
            <th>Código</th>
            <th>Opciones</th>       
        </tr>
    </thead>
    <tbody>
        @foreach($lineaspresupuestarias as $key => $linea)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$linea->oficina->oficina}}</td>
                <td>{{$linea->pais->pais}}</td>
                <td>{{$linea->codigo}}</td>     
                <td>                
                    @if(Entrust::can('editar-lineapresupuestaria'))
                        <a href='{{route('lineapresupuestaria.edit', $linea->id)}}' class="btn btn_color">
                            Editar </a>
                    @endif

                    @if(Entrust::can('ver-lineapresupuestaria-info'))
                        <a href='{{route('lineapresupuestaria.show', $linea->id)}}' class="btn btn_color">
                            Ver </a>
                    @endif

                    @if(Entrust::can('eliminar-lineapresupuestaria'))
                        <a href='{{ route('lineapresupuestaria.delete', $linea->id) }}' class="btn btn_color btn_rojo" 
                            title="Eliminar Línea Presupuestaria "
                            data-toggle="tooltip"
                            data-placement="top"
                            data-method="DELETE"
                            data-confirm-title="Eliminar Línea Presupuestaria"
                            data-confirm-text="Esta seguro de querer eliminar esta Línea Presupuestaria"
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
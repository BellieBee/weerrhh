@extends('layouts.app')

@section('page-title', 'Centros de Costo')

@section('content')

<h1 class="page-header">Centros de Costo</h1>

@include('partials.messages')


<div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-12 text-right">   
        @if(Entrust::can(['crear-centrocosto-todos','crear-centrocosto-oficina']))  
            <a href="{{url('centrocosto/create')}}"  class="btn btn_color btn_azul">Crear Centro de Costo</a>
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
        @foreach($centroscosto as $key => $centro)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$centro->oficina->oficina}}</td>
                <td>{{$centro->pais->pais}}</td>
                <td>{{$centro->codigo}}</td>     
                <td>                
                    @if(Entrust::can('editar-centrocosto'))
                        <a href='{{route('centrocosto.edit', $centro->id)}}' class="btn btn_color">
                            Editar </a>
                    @endif

                    @if(Entrust::can('ver-centrocosto-info'))
                        <a href='{{route('centrocosto.show', $centro->id)}}' class="btn btn_color">
                            Ver </a>
                    @endif

                    @if(Entrust::can('eliminar-centrocosto'))
                        <a href='{{ route('centrocosto.delete', $centro->id) }}' class="btn btn_color btn_rojo" 
                            title="Eliminar Centro de Costo "
                            data-toggle="tooltip"
                            data-placement="top"
                            data-method="DELETE"
                            data-confirm-title="Eliminar Centro de Costo"
                            data-confirm-text="Esta seguro de querer eliminar este Centro de Costo"
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
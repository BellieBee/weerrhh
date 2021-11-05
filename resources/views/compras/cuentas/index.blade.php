@extends('layouts.app')

@section('page-title', 'Cuentas Contables')

@section('content')

<h1 class="page-header">Cuentas Contables</h1>

@include('partials.messages')


<div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-12 text-right">   
        @if(Entrust::can(['crear-cuenta-todos','crear-cuenta-oficina']))  
            <a href="{{url('cuenta/create')}}"  class="btn btn_color btn_azul">Crear Cuenta</a>
        @endif
    </div>
</div>

<table class="table dataTables-empleados table_planillas table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Oficina</th>
            <th>País</th>
            <th>Cuenta</th>
            <th>Opciones</th>       
        </tr>
    </thead>
    <tbody>
        @foreach($cuentas as $key => $cuenta)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$cuenta->oficina->oficina}}</td>
                <td>{{$cuenta->pais->pais}}</td>
                <td>{{$cuenta->nombre}}</td>     
                <td>                
                    @if(Entrust::can('editar-cuenta'))
                        <a href='{{route('cuenta.edit', $cuenta->id)}}' class="btn btn_color">
                            Editar </a>
                    @endif

                    @if(Entrust::can('ver-cuenta-info'))
                        <a href='{{route('cuenta.show', $cuenta->id)}}' class="btn btn_color">
                            Ver </a>
                    @endif

                    @if(Entrust::can('eliminar-cuenta'))
                        <a href='{{ route('cuenta.delete', $cuenta->id) }}' class="btn btn_color btn_rojo" 
                            title="Eliminar Cuenta Contable "
                            data-toggle="tooltip"
                            data-placement="top"
                            data-method="DELETE"
                            data-confirm-title="Eliminar Cuenta"
                            data-confirm-text="Esta seguro de querer eliminar esta Cuenta Contable"
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
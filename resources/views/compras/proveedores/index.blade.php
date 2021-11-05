@extends('layouts.app')

@section('page-title', 'Proveedores')

@section('content')

<h1 class="page-header">Proveedores</h1>

@include('partials.messages')


<div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-12 text-right">   
        @if(Entrust::can(['crear-proveedor-todos','crear-proveedor-oficina']))  
            <a href="{{url('proveedor/create')}}"  class="btn btn_color btn_azul">Crear Proveedor</a>
        @endif
    </div>
</div>

<table class="table dataTables-empleados table_planillas table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Oficina</th>
            <th>Razón Social</th>
            <th>Email</th>         
            <th>Telf</th>
            <th>Proveedor Desde</th>
            <th>Opciones</th>       
        </tr>
    </thead>
    <tbody>
        @foreach($proveedores as $key => $proveedor)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$proveedor->oficina->oficina}}</td>
                <td>{{$proveedor->razon_social}}</td>
                <td>{{$proveedor->email}}</td>
                <td>{{$proveedor->telf}}</td>
                <td>
                    {{date('d-m-Y', strtotime($proveedor->proveedor_desde))}}
                </td>               
                <td>                
                    @if(Entrust::can('editar-proveedor'))
                        <a href='{{route('proveedor.edit', $proveedor->id)}}' class="btn btn_color">
                            Editar </a>
                    @endif

                    @if(Entrust::can('ver-proveedor-info'))
                        <a href='{{route('proveedor.show', $proveedor->id)}}' class="btn btn_color">
                            Ver </a>
                    @endif

                    @if(Entrust::can('eliminar-proveedor'))
                        <a href='{{ route('proveedor.delete', $proveedor->id) }}' class="btn btn_color btn_rojo" 
                            title="Eliminar Proveedor "
                            data-toggle="tooltip"
                            data-placement="top"
                            data-method="DELETE"
                            data-confirm-title="Eliminar Proveedor"
                            data-confirm-text="Esta seguro de querer eliminar este Proveedor"
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
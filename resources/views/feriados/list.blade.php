@extends('layouts.app')

@section('page-title', 'Feriados')

@section('content')


    <h1 class="page-header">
        Feriados
    </h1>


@include('partials.messages')
    
    @permission(['crear-feriado','crear-feriado-pais'])
    <div class=" tab-search text-right">    
        <a href="{{ route('feriados.create') }}" class="btn btn_color" id="add-user">
            <i class="glyphicon glyphicon-plus"></i>
            Añadir Feriado
        </a>    
            
    </div>
    @endpermission


    <table class="table dataTables-empleados">
        <thead>
            <tr>                               
                <th>#</th>
                <th>Día feriado</th>
                <th>País</th>
                <th>Descripción</th>                              
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($feriados as $feriado)

                <tr>                             
                    <td>#{{$feriado->id}}</td>
                    <td>{{$feriado->dia}} de {{ $feriado->month }}</td>
                    <td>{{$feriado->pais}}</td>
                    <td>{{$feriado->descripcion_feriado}}</td>
                    <td>
                        @permission('editar-feriado')
                        <a href="{{ route('feriados.edit', $feriado->id) }}" >
                            <i class="glyphicon glyphicon-pencil" ></i></a>
                        @endpermission
                        
                        @permission('eliminar-feriado')        
                        <a href="{{ route('feriados.delete', $feriado->id) }}" class="btn" title="
                            borrar feriado"
                            data-toggle="tooltip"
                            data-placement="top"
                            data-method="DELETE"
                            data-confirm-title="@lang('app.please_confirm')'"
                            data-confirm-text="¿Seguro que desea borrar el día feriado?"
                            data-confirm-delete="Borrar">
                            <i class="glyphicon glyphicon-trash"></i>
                        </a>
                        @endpermission
                              
                    </td>

                </tr>
            @endforeach


        </tbody>
    </table>

@stop

@section('scripts')
    <script>
        $("#status").change(function () {
            $("#users-form").submit();
        });
        $('.dataTables-empleados').DataTable({
        "pageLength": 15,
        buttons: 
        [                    
          {
            customize: function (win){
              $(win.document.body).addClass('white-bg');
              $(win.document.body).css('font-size', '10px');

              $(win.document.body).find('table')
              .addClass('compact')
              .css('font-size', 'inherit');
            }
          }
        ],
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

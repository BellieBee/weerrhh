@extends('layouts.app')

@section('page-title', trans('app.users'))

@section('content')


    <h1 class="page-header">
            @lang('app.users')
            <small>@lang('app.list_of_registered_users')</small>
        </h1>


@include('partials.messages')
    
    @permission(['crear-colegas','crear-colegas-oficina', 'crear-usuario-todos', 'crear-usuario-colega', 'crear-usuario-colega-consultor'])
        <div class=" tab-search text-right">    
            <a href="{{ route('user.create') }}" class="btn btn_color" id="add-user">
                <i class="glyphicon glyphicon-plus"></i>
                @lang('app.add_user')
            </a>           
        </div>
    @endpermission
    
    <table class="table dataTables-empleados table-bordered table-hover table-condensed">
        <thead>
            <tr>
                                
                <th>ID</th>
                <th>Nombre</th>
                <th>Oficina</th>
                {{--<th>Cargo</th>--}}                                
                <th>Tipo</th>                                
                <th>Status</th> 
                <th>Fecha de Ingreso</th>                               
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)

                @if($user->id != 145)

                    <tr>                             
                        <td>{{$user->id}}</td>
                        <td>{{$user->first_name}} {{$user->last_name}}
                            {{--({{$user->roles->first()->name}})--}}</td>
                        <td>{{$user->oficina->oficina}}</td>
                        {{--@if($user->cargo->cargo !== null)
                                <td>{{$user->cargo->cargo}}</td>
                            @else
                                <td>Sin Cargo</td>
                        @endif --}}                             
                        <td>{{$user->categoria->categoria}}</td>
                        <td>{{$user->status ? 'Activo' : 'Inactivo'}}</td>
                        <td>{{$user->created_at->format('d-m-Y')}}</td>
                        <td>
                            @permission('ver-colegas-detalle')
                                <a href="{{ route('user.show', $user->id) }}" class="btn btn_color">
                                    <i class="glyphicon glyphicon-eye-open"></i> Ver</a>
                            @endpermission
                                        
                            @permission('descargar-colega-pdf')
                                <a href="{{ route('user.descargar', $user->id) }}" class="btn btn_color">
                                    <i class="glyphicon glyphicon-save"></i> PDF</a>
                            @endpermission

                            @permission('editar-colegas')
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn_color">
                                    <i class="fa fa-pencil fa-fw" ></i> Editar</a>          
                            @endpermission

                            {{--@role(['Administradora','Admin'])
                                <a href="{{ route('user.delete', $user->id) }}" class="btn btn_color" title="
                                    @lang('app.delete_user')"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        data-method="DELETE"
                                        data-confirm-title="@lang('app.please_confirm')'"
                                        data-confirm-text="@lang('app.are_you_sure_delete_user')"
                                        data-confirm-delete="@lang('app.yes_delete_him')'"
                                style="background: #F44336;">
                                    <i class="glyphicon glyphicon-trash"></i> Borrar
                                </a>
                            @endrol--}}
                        </td>

                    </tr>

                @endif
            @endforeach
        </tbody>
    </table>
 
{{--<div class="table-responsive top-border-table" id="users-table-wrapper">
    <table class="table dataTables-empleados">
        <thead>
            <th>@lang('app.username')</th>
            <th>@lang('app.full_name')</th>
            <th>@lang('app.email')</th>
            <th>Rol</th>
            <th>Oficina</th>
            <th>@lang('app.status')</th>
            <th class="text-center">@lang('app.action')</th>
        </thead>
        <tbody>
            @if (count($users))
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->username ?: trans('app.n_a') }}</td>
                        <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles()->first()->name }}</td>
                        <td>{{ $user->oficina->oficina }}</td>
                        <td>
                            <span class="label label-{{ $user->present()->labelClass }}">{{ trans("app.{$user->status}") }}</span>
                        </td>
                        <td class="text-center">
                            @if (config('session.driver') == 'database')
                                <a href="{{ route('user.sessions', $user->id) }}" class="btn btn-info btn-circle"
                                   title="@lang('app.user_sessions')" data-toggle="tooltip" data-placement="top">
                                    <i class="fa fa-list"></i>
                                </a>
                            @endif
                            <a href="{{ route('user.show', $user->id) }}" class="btn btn-success btn-circle"
                               title="@lang('app.view_user')" data-toggle="tooltip" data-placement="top">
                                <i class="glyphicon glyphicon-eye-open"></i>
                            </a>
                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary btn-circle edit" title="@lang('app.edit_user')"
                                    data-toggle="tooltip" data-placement="top">
                                <i class="glyphicon glyphicon-edit"></i>
                            </a>
                            <a href="{{ route('user.delete', $user->id) }}" class="btn btn-danger btn-circle" title="@lang('app.delete_user')"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    data-method="DELETE"
                                    data-confirm-title="@lang('app.please_confirm')'"
                                    data-confirm-text="@lang('app.are_you_sure_delete_user')"
                                    data-confirm-delete="@lang('app.yes_delete_him')'">
                                <i class="glyphicon glyphicon-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6"><em>@lang('app.no_records_found')</em></td>
                </tr>
            @endif
        </tbody>
    </table>

    !! $users->render() 
</div> --}}

@stop

@section('scripts')
    <script>
        $("#status").change(function () {
            $("#users-form").submit();
        });
        $('.dataTables-empleados').DataTable({
           "order": [
                        [ 5, "asc" ],
                        [ 0, "desc" ]
                       
                    ],
            "pageLength": 20,
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

@extends('layouts.app')

@section('page-title', 'Viajes')

@section('content')

<h1 class="page-header">Viajes</h1>

@include('partials.messages')


<div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-12 text-right">

        
    @if(Entrust::can(['crear-viajes-todos','crear-viajes-oficina']))
        <form class="form-inline"  action="{{url('/create/viajes')}}" method="get">   
            <div class="form-group ">                   
                <select class="form-control" name="id" required>
                    <option></option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group ">
                <button type="submit" class="btn btn_color btn_azul">Solicitud de Viajes</button>
            </div>
        </form>
    @else
    <a href="{{url('/create/viajes')}}"  class="btn btn_color btn_azul">Solicitud de Viajes</a href="!#">
    @endif
    
    </div>

</div>


    <table class="table dataTables-empleados table_planillas table-bordered table-hover">
        <thead>
            <tr>
                <th>País</th>
                <th>No Empleado</th>
                <th>Nombre</th>         
                <th>Fecha de Solicitud</th>                                
                <th>Tiempo</th>                 
                <th>Opciones</th>       

            </tr>
        </thead>
        <tbody>

            @foreach($viajes as $viaje)
                <tr>
                    <td>{{$viaje->oficina->pais->pais}}</td>
                    <td>{{$viaje->user->n_contrato}}</td>
                    <td>{{$viaje->user->first_name}} {{$viaje->user->last_name}}</td>
                    
                     <td>
                        <span style='display:none'>{{$viaje->created_at->format('Ymd')}}</span>
                        {{$viaje->created_at->format('d-m-Y')}} 
                        @if($viaje->created_at->format('d-m-Y')==date('d-m-Y')) 
                            <span class="label label-success "> Hoy</span>
                        @endif
                    </td>               

                    <td>{{$viaje->num_dh}} {{$viaje->dh}}</td>  
                   
                    <td>         
                        
                        @if(Entrust::can('editar-viajes') && $viaje->aprobacion_directora == 0)
                            <a href='{{url("/edit/viajes/$viaje->id")}}' class="btn btn_color">
                                Editar
                            </a>
                        @endif

                        @if(Entrust::can('ver-viajes-info') && $viaje->aprobacion_directora != 0)
                            <a href='{{url("/edit/viajes/$viaje->id")}}' class="btn btn_color">
                                Ver
                            </a>
                        @endif

                        @if(Entrust::can('eliminar-viajes') && $viaje->aprobacion_directora == 0)
                            <a href='{{ url("/delete/viajes/$viaje->id") }}'  class="btn btn_color btn_rojo" 
                                title="Eliminar Solicitud de viajes "
                                data-toggle="tooltip"
                                data-placement="top"
                                data-method="DELETE"
                                data-confirm-title="Eliminar Solicitud de viajes"
                                data-confirm-text="Esta seguro de querer eliminar esta Solicitud de viajes"
                                data-confirm-delete="Borrar"
                             >
                            Borrar
                            </a>
                        @endif

                        {{--<a href='{{url("/edit/viajes/$viaje->id")}}' class="btn btn_color">
                        @if(
                                $viaje->aprobacion_directora!=0 ||
                                Entrust::hasRole(['Coordinadora','Directora','Contralora']) && auth()->user()!=$viaje->user
                                
                            )
                            
                            Ver

                        @else
                        
                            Editar

                        @endif  
                        </a> --}}
                        
                        {{--@if(
                                (Entrust::hasRole(['Coordinadora','Directora','Contralora']) && auth()->user()->id==$viaje->user->id ||
                                Entrust::hasRole(['Administradora', 'Coordinadora', 'Admin'])) && $viaje->aprobacion_directora==0 ||
                                Entrust::hasRole(['Admin', 'Coordinadora'])
                            )
                        <a href='{{ url("/delete/viajes/$viaje->id") }}'  class="btn btn_color btn_rojo" 
                            title="Eliminar Solicitud de viajes "
                            data-toggle="tooltip"
                            data-placement="top"
                            data-method="DELETE"
                            data-confirm-title="Eliminar Solicitud de viajes"
                            data-confirm-text="Esta seguro de querer eliminar esta Solicitud de viajes"
                            data-confirm-delete="Borrar"
                             >
                            Borrar
                        </a>    
                        @endif--}}
                    </td>
                </tr>
            @endforeach
            </tbody>
    </table>
    
    @stop
    @section('scripts')

    <script>


                /*$('.descargar_planilla').click(function(event) {
                    $(this).attr('disabled', true);

                    setInterval(function(){
                        $(this).removeAttr('disabled');
                    },1000)
                });*/

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
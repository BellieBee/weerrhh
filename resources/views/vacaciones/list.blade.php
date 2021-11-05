@extends('layouts.app')

@section('page-title', 'Vacaciones')

@section('content')

<h1 class="page-header">Vacaciones</h1>

@include('partials.messages')


<div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-12 text-right">

        
    @if(Entrust::can(['crear-vacaciones-todos','crear-vacaciones-oficina']))
        <form class="form-inline"  action="{{url('/create/vacaciones')}}" method="get">   
            <div class="form-group ">                   
                <select class="form-control" name="id" required>
                    <option></option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group ">
                <button type="submit" class="btn btn_color btn_azul">Solicitud de Vacaciones</button>
            </div>
        </form>
    @else
    <a href="{{url('/create/vacaciones')}}"  class="btn btn_color btn_azul">Solicitud de Vacaciones</a href="!#">
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
                <th>Status</th>
                <th>Opciones</th>       

            </tr>
        </thead>
        <tbody>

            @foreach($vacaciones as $vacacion)
                <tr>
                    <td>{{$vacacion->oficina->pais->pais}}</td>
                    <td>{{$vacacion->user->n_contrato}}</td>
                    <td>{{$vacacion->user->first_name}} {{$vacacion->user->last_name}}</td>
                    
                     <td>
                        <span style='display:none'>{{$vacacion->created_at->format('Ymd')}}</span>
                        {{$vacacion->created_at->format('d-m-Y')}} 
                        @if($vacacion->created_at->format('d-m-Y')==date('d-m-Y')) 
                            <span class="label label-success "> Hoy</span>
                        @endif
                    </td>               

                    <td>{{$vacacion->num_dh}} {{$vacacion->dh}}</td>  
                   
                    <td>
                        @if($vacacion->aprobacion_directora==0)                            
                            <span style="font-size:90%;font-weight:normal;" class="label label-warning ">Pendiente</span>          
                        @elseif($vacacion->aprobacion_directora==1)          
                            <span style="font-size:90%;font-weight:normal;" class="label label-success "><i class="fa fa-check"></i> Aprobado</span>
                        @elseif($vacacion->aprobacion_directora==2)
                            <span style="font-size:90%;font-weight:normal;" class="label label-danger "><i class="fa fa-close"></i> Rechazada</span>
                        @elseif($vacacion->aprobacion_directora==3)
                            <span style="font-size:90%;font-weight:normal;" class="label label-info ">Anulada</span>
                        @endif

                    </td>
                    <td>         
                        
                        @if(Entrust::can('editar-vacaciones') && $vacacion->aprobacion_directora == 0)
                            <a href='{{url("/edit/vacaciones/$vacacion->id")}}' class="btn btn_color">
                                Editar </a>
                        @endif

                        @if(Entrust::can('ver-vacaciones-info') && $vacacion->aprobacion_directora != 0)
                            <a href='{{url("/edit/vacaciones/$vacacion->id")}}' class="btn btn_color">
                                Ver </a>
                        @endif
                                                    

                        {{--<a href='{{url("/edit/vacaciones/$vacacion->id")}}' class="btn btn_color">
                        @if(
                                $vacacion->aprobacion_directora!=0 ||
                                Entrust::hasRole(['Coordinadora','Directora','Contralora']) && auth()->user()!=$vacacion->user
                                
                            )
                            
                            Ver

                        @else
                        
                            Editar

                        @endif  
                        </a> --}}


                        @if(Entrust::can('descargar-vacaciones') && $vacacion->aprobacion_directora!=0)
                            <a href='{{url("/descargar/vacaciones/$vacacion->id")}}' class="btn btn_color btn_rojo">PDF</a>
                        @endif
                        
                        @if(Entrust::can('eliminar-vacaciones') && $vacacion->aprobacion_directora==0)
                        
                            <a href='{{ url("/delete/vacaciones/$vacacion->id") }}'  class="btn btn_color btn_rojo" 
                                title="Eliminar Solicitud de vacaciones "
                                data-toggle="tooltip"
                                data-placement="top"
                                data-method="DELETE"
                                data-confirm-title="Eliminar Solicitud de vacaciones"
                                data-confirm-text="Esta seguro de querer eliminar esta Solicitud de vacaciones"
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
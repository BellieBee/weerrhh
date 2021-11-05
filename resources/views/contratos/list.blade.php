@extends('layouts.app')

@section('page-title', 'Contratos')

@section('content')

    <h1 class="page-header">Contrato</h1>

    @include('partials.messages')

    <div class="col-xs-12 text-right" style="margin-bottom: 20px;">
        @permission(['crear-contratos-todos','crear-contratos-oficina'])
        <form class="form-inline"  action="{{url('/contratos/create')}}" method="post">   
            {!! csrf_field() !!}
            <input type="hidden" name="ordencompra" value="0">
            <div class="form-group ">                   
                <select class="form-control" required name="id">
                    <option disabled selected></option>
                    @foreach($oficinas as $oficina)                             
                    <optgroup label="{{$oficina->oficina}}">
                        @foreach($users->where('oficina_id',$oficina->id) as $user)
                        <option value="{{$user->id}}">
                            {{$user->first_name}} {{$user->last_name}}                                  
                        </option>
                        @endforeach
                    </optgroup>

                    @endforeach
                </select>
            </div>
            <div class="form-group ">
                <button type="submit" class="btn btn_color btn_azul">Generar Contrato</button>
            </div>
        </form>
        @endpermission
    </div>
    <table class="table dataTables-empleados table_planillas table-bordered table-hover">
        <thead>
            <tr>
                <th>N°</th>
                @if(Entrust::can('ver-contratos-todos'))
                    <th>Oficina</th>
                @endif
                {{--<th>Representante País</th>--}}
                <th>Consultor</th>                     
                <th>Fecha de Contrato</th>         
                <th>Periodo de Tiempo</th>                                
                <th>Tiempo de contrato</th>                                
                <th>Consultoria</th>                              

                <th>Confirmacion Coordinadora</th>        
                <th>Aprobación Directora</th>                 
                <th>Status</th>
                <th>Opciones</th>       
                    

            </tr>
        </thead>
        <tbody>

            @foreach($contratos as $contrato)
            <tr>
                <td>{{$contrato->n_contrato}}</td>
                @if(Entrust::can('ver-contratos-todos'))
                    <td>{{$contrato->oficina->oficina}}</td>
                @endif

                {{--<td>{{Vanguard\Role::where('name', 'RepresentantePais')->first()->users->where('oficina_id', $contrato->oficina_id)->where('status', 1)->first()}}</td>--}}

                <td class="nombre" >
                    @if($contrato->user_id != null)
                        {{$contrato->user->first_name}} {{$contrato->user->last_name}}
                    @elseif($contrato->proveedor_id != null)
                        {{$contrato->proveedor->nombre}}
                    @else
                        {{$contrato->proveedorUser->first_name}} {{$contrato->proveedorUser->last_name}}
                    @endif
                </td>
                <td>{{$contrato->fecha_contrato}}</td>
                <td>
                    <span>{{$contrato->fecha_inicio}}</span>
                    <br>hasta<br>
                    <span class="fecha_finalizcion">{{$contrato->fecha_fin}}</span>
                </td>
                <td>{{$contrato->tiempo_contrato}} dias</td>
                <td >{{$contrato->consultoria}} </td>  
                <td>
                    @if($contrato->aprobacion_coordinadora)

                    <span  class="label label-success label_status "><i class="fa fa-check"></i> Confirmada</span>            
                    @else
                    <span  class="label label-warning label_status ">Pendiente</span>
                    @endif

                </td>                  
                <td>
                    @if($contrato->aprobacion_directora)

                    <span  class="label label-success label_status "><i class="fa fa-check"></i> Aprobado</span>            
                    @else
                    <span  class="label label-warning label_status ">Pendiente</span>
                    @endif
                </td>
                <td>
                    @if($contrato->status==0) 
                        <span  class="label label-warning label_status ">  Pendiente</span>
                    @elseif($contrato->status==1) 
                      <span  class="label label-success label_status "> Aprobado</span>                          
                    
                    @elseif($contrato->status==2) 
                      <span  class="label label-danger label_status "> Rechazado</span>                          
                    
                    @elseif($contrato->status==3) 
                      <span  class="label label-success label_status " style="background:#bbdefb;color:#000;" > Terminado</span>                          
                    
                    @elseif($contrato->status==4) 
                      <span  class="label label-info label_status" style="background:#9e9e9e ;" > Anulado</span>                          
                    
                    @elseif($contrato->status==5) 
                      <span  class="label label-success label_status" style="background:#9e9e9e ;" >Vencido</span>
                    
                    @endif

                </td>

                <td>
                    <div class="dropdown">
                        <button class="btn btn_gris dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                            Opciones
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                            <li >
                                <a href='{{url("/contrato/view/$contrato->id")}}' href="#"><i class="glyphicon glyphicon-eye-open"></i> Ver contrato</a>
                            </li>

                            @permission('editar-contratos')
                                <a href='{{url("/contrato/edit/$contrato->id")}}' href="#"><i class="glyphicon glyphicon-pencil"></i> Editar contrato</a>
                            @endpermission

                            @permission('eliminar-contratos')
                                <li >
                                    <a href='{{ url("/contrato/delete/$contrato->id") }}'                                    
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        data-method="DELETE"
                                        data-confirm-title="Eliminar contrato "
                                        data-confirm-text="Esta seguro de querer eliminar este contrato"
                                        data-confirm-delete="Borrar">
                                        <i class="glyphicon glyphicon-trash"></i> Borrar contrato
                                    </a>
                                </li>
                            @endpermission

                            @permission('descargar-contratos')
                                <li>                         
                                    <a href='{{url("/contrato/descargar/$contrato->id")}}' target="_black">
                                        <i class="glyphicon glyphicon-file"></i> Descargar PDF
                                    </a>                                                            
                                </li>
                            @endpermission

                            @if(Entrust::can('ver-adendas-contratos') || Entrust::can('crear-adendas-contratos'))
                                <li class="divider"></li>
                                @if(Entrust::can('ver-adendas-contratos'))
                                    @if(count($contrato->adendas))
                                        <li>                               
                                            <a href="!#" class="btn_modal" data-toggle="modal" data-target="#myModal" id="{{$contrato->id}}">
                                                <i class="glyphicon glyphicon-time"></i>  Adendas({{count($contrato->adendas)}})
                                            </a>
                                        </li>
                                    @else
                                        <li class="dropdown-header">                         
                                            <i class="glyphicon glyphicon-time"></i> Adendas ({{count($contrato->adendas)}})          
                                        </li>
                                    @endif 
                                @endif

                                @if(Entrust::can('crear-adendas-contratos'))
                                    <li>
                                        <a 
                                            class="agregar_adenda" 
                                            id="{{$contrato->id}}" 
                                            href="#"
                                            data-toggle="modal" data-target="#crear_adenda">
                                            <i class="glyphicon glyphicon-plus"></i>Agregar Adenda
                                        </a>
                                    </li>
                                @endif
                                <li class="divider"></li>
                            @endif



                            {{--@role(['Administradora','Coordinadora','Directora', 'Colaborador'])
                            <li >
                                <a href='{{url("/contrato/edit/$contrato->id")}}' href="#"><i class="glyphicon glyphicon-pencil"></i> Editar contrato</a>
                            </li>
                            
                            
                            <li >
                                <a href='{{ url("/contrato/delete/$contrato->id") }}'                                    
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    data-method="DELETE"
                                    data-confirm-title="Eliminar contrato "
                                    data-confirm-text="Esta seguro de querer eliminar este contrato"
                                    data-confirm-delete="Borrar">
                                    <i class="glyphicon glyphicon-trash"></i> Borrar contrato
                                </a>
                            </li>
                            @endrole --}}

                            {{--@role(['Administradora','Coordinadora','Directora'])
                            <li class="divider"></li>                                           
                            @if(count($contrato->adendas))
                            <li>                               
                                <a href="!#" class="btn_modal" data-toggle="modal" data-target="#myModal" id="{{$contrato->id}}">
                                  <i class="glyphicon glyphicon-time"></i>  Adendas({{count($contrato->adendas)}})
                                </a>
                            </li>
                            @else
                            <li class="dropdown-header">                         
                               <i class="glyphicon glyphicon-time"></i> Adendas ({{count($contrato->adendas)}})          
                            </li>
                            @endif 
                            <li>
                                <a 
                                class="agregar_adenda" 
                                id="{{$contrato->id}}" 
                                href="#"
                                data-toggle="modal" data-target="#crear_adenda">
                                    <i class="glyphicon glyphicon-plus"></i>Agregar Adenda
                                </a>
                            </li> 
                            <li class="divider"></li>                 
                            
                            @endrole --}}
                            
                        </ul>
                    </div>                    
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>  
    
    <div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>        
                    <h4 class="modal-title">Lista de Adendas</h4>                            
                </div>
                <div class="modal-body">                    
                     <div class="modal_load">
                         <i class="glyphicon glyphicon-refresh" style="font-size: 25px;padding: 40px;"></i>Cargando...
                     </div>
                     <div class="modal_table" >
                        <table class="table table-bordered">
                            <thead>                            
                            <tr>
                                <th>Fecha de Creación</th>
                                <th>Fecha de Finalización antes -> ahora </th>
                                <!--<th>Fecha de Cumplimiento</th>-->                                
                                <th>Motivo</th>
                                <th colspan="2"></th>                              
                                                             
                            </tr>
                            </thead> 
                            <tbody class="adenda_list">
                                
                            </tbody>  
                        </table>
                        
                     </div>               
                </div>        
            </div>
        </div>
    </div>
    
    <div class="modal fade " id="crear_adenda" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>        
                    <h4 class="modal-title">Crear Adenda</h4>                            
                </div>
                <div class="modal-body">
                    
                    <form class="form-horizontal modal_form_adenda" action="{{url('/adenda/create')}}" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" class="modal_input_contrato_id" name="contrato_id" value="" >
                                                  

                            <div class="form-group">
                                <label class="control-label col-sm-3">Consultor:</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control modal_input_nombre" disabled>
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3">Actual fecha de finlaizacion </label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control modal_input_fecha" disabled>
                                </div>

                            </div>  

                            <div class="form-group">
                                <label class="control-label col-sm-3">Nueva fecha de finlaizacion</label>
                                <div class="col-sm-4">
                                    <div class="input-group datepicker" style="padding: 0px;">
                                        <input type="text" class="form-control" name="fecha_contrato_nueva">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </div>
                                    </div>
                                </div>                                   
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3">Motivo de adenda:</label>
                                <div class="col-sm-8">
                                    <textarea required type="text" class="form-control" name="motivo"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3">Nota:</label>
                                <div class="col-sm-8">
                                    Las aprobaciones de la <b>Directora</b> y <b>Coordinadora</b> volveran a el status:
                                    <label class="label label-warning label_status ">Pendiente</label>
                                    ya que con esta adenda se modificara la fecha de finlaizacion del contrato
                                </div>
                            </div>
                            <br>
                            <br>
                            <br>
                            <div class="form-group">
                                <div class="col-xs-12 text-right">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn_color">Crear Adenda</button>
                                </div> 
                            </div>
                        
                    </form>                   
                </div>
                
            </div>
        </div>
    </div>  

@endsection

 @section('scripts')
{!! HTML::script('assets/js/moment.min.js') !!}
{!! HTML::script('assets/js/moment_es.js') !!}

{!! HTML::script('assets/js/bootstrap-datepicker.min.js') !!}
{!! HTML::script('assets/js/bootstrap-datepicker.es.min.js') !!}
 <script>
                 

    $('.dataTables-empleados,.dataTables-adenda').DataTable({

        "info": false,
        "pageLength": 15,
        "order": [[ 2, "desc" ]],
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

    $(document).on('click', '.btn_modal', function(event) {
        event.preventDefault();

        $('modal-header,.modal_table').hide()
        $('.modal_load').show();
        console.log('dada');
        id=$(this).attr('id');
        $.ajax({
            url: '{{url("/adenda/list")}}/'+id,
            dataType: 'json',            
        })
        .done(function(data) {
           $('.adenda_list').html(data);
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            $('.modal_load').hide();
            $('modal-header,.modal_table').show()
        });
        
        
    });

    $(document).on('click', '.agregar_adenda', function(event) {
        event.preventDefault();
        id=$(this).attr('id');
        fecha_fin=$(this).closest('tr').find('span.fecha_finalizcion').text();
        nombre=$(this).closest('tr').find('td.nombre').text();
        
        $('.modal_input_nombre').val(nombre);
        $('.modal_input_contrato_id').val(id);
        $('.modal_input_fecha').val(fecha_fin);
        $('.modal .datepicker input').datepicker({ 
            language:'es',      
            format: 'yyyy-mm-dd',             
            startDate: fecha_fin,
        });
        console.log(fecha_fin);
    });
    

</script>
            @stop
@section('styles')
    {!! HTML::style('assets/css/bootstrap-datepicker.min.css') !!}
    {!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop

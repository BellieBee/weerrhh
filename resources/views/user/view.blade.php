@extends('layouts.app')

@section('page-title', $user->present()->nameOrEmail)

@section('content')


<h1 class="page-header">
    {{$user->first_name}} {{$user->last_name}}
    <small>@lang('app.user_details')</small>
        <div class="pull-right">
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
                <li><a href="{{ route('user.list') }}">@lang('app.users')</a></li>
                <li class="active">{{ $user->present()->nameOrEmail }}</li>
            </ol>
        </div>
    </h1>
    <div style="display: inline-block;">

        
        @role(['Administradora','Admin'])
        <div class="text-right" style="margin-bottom: 40px;">
            <a href="{{ route('user.edit', $user->id) }}" class="btn btn_color">
                <i class="glyphicon glyphicon-edit"></i> Editar 
            </a>            
            <!--<a class="btn btn_color">Impresion </a>-->
        </div>
        @endrole
        <form role="form"  action='{{url("/rrhh/solicitud/permiso/$user->id")}}' method="POST" class="form_view_user">
            {{ csrf_field() }}
            
            <div class="col-xs-12">
                <div class="form-group col-sm-3">
                    <label class="">Oficina</label>
                    <p class="form-control-static">{{$user->oficina->oficina}}</p>
                </div>

                <div class="form-group col-sm-3">
                    <label>Status</label>            
                    <p class="form-control-static">{{$user->status}}</p>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="form-group col-sm-3">
                    <label class="">Correo Electrónico</label>
                    <p class="form-control-static">{{$user->email}}</p>
                </div>

                <div class="form-group col-sm-3">
                    <label>Contraseña</label>            
                    <p class="form-control-static">******</p>
                </div>
                <div class="form-group col-sm-3">
                    <label>Rol</label>            
                    <p class="form-control-static">{{$user->roles()->first()->name}}</p>
                </div>             
            </div>

            <div class="col-xs-12">
                <div class="form-group col-sm-3">
                    <label class="">Nombre</label>
                    <p class="form-control-static">{{$user->first_name}} {{$user->last_name}}</p>
                </div>

                <div class="form-group col-sm-3">
                    <label>No contrato</label>            
                    <p class="form-control-static">#{{$user->n_contrato}}</p>
                </div>
                <div class="form-group col-sm-4">
                    <label >Tipo de Empleado</label> 
                    <p class="form-control-static">{{$user->categoria->categoria}} </p>            
                </div>
            </div>

            <div class="col-xs-12">
                <div class="form-group col-sm-3 ">
                  <label >Tipo de Documento</label> 
                  <p class="form-control-static">{{$user->tipo_documento->tipo_documento}} </p>            
                </div>
                <div class="form-group col-sm-3">
                  <label >Documento</label> 
                  <p class="form-control-static">{{$user->documento}} </p>            
                </div>
                <div class="form-group col-sm-3">
                  <label >Cargo</label> 
                  <p class="form-control-static">{{$user->cargo->cargo}} </p>            
                </div>
            </div>

            <div class="col-xs-12">                 
                <div class="form-group col-sm-3">
                    <label >Fecha de inicio</label> 
                    <p class="form-control-static">{{$user->fecha_inicio}} </p>            
                </div>
                <div class="form-group col-sm-3">
                    <label >Fecha de Finalización</label>
                    <p class="form-control-static">{{$user->fecha_finalizacion}} </p>            
                </div>
                <div class="form-group col-sm-3">
                    <label >Profesión</label>
                    <p class="form-control-static">{{$user->profesion->profesion}} </p>            
                </div> 
                                           
            </div>

            <div class="col-xs-12">
                <div class="form-group col-sm-3">
                    <label >No. Afiliación </label> 
                    <p class="form-control-static">{{$user->n_afiliacion}} </p>                   
                </div>
                <div class="form-group col-sm-3">
                    <label >Identificación Tributaria</label> 
                    <p class="form-control-static">{{$user->n_identificacion_tributaria}} </p>                   
                </div>

                <div class="form-group col-sm-3">
                    <label >Régimen Tributario</label>
                    <p class="form-control-static">{{$user->regimen_tributario}} </p>             
                </div>
                <div class="form-group col-sm-3">
                    <label >Salario</label>
                    <p class="form-control-static">{{$user->salario}} </p>             
                </div>             
            </div>

            <div class="col-xs-12">
                <div class="form-group col-sm-3">
                    <label >Teléfono</label>
                    <p class="form-control-static">{{$user->phone}} </p>       
                </div>

                <div class="form-group col-sm-3">
                    <label >Skype</label>
                    <p class="form-control-static">{{$user->skype}} </p>            
                </div>

                <div class="form-group col-sm-3">
                    <label >Celular</label>
                    <p class="form-control-static">{{$user->cellphone}} </p>            
                </div>
            </div>

            <div class="col-xs-12">
                <div class="form-group col-sm-3">
                    <label > Sexo </label>
                    <p class="form-control-static">{{$user->sexo}}</p>         
                </div>  
                <div class="form-group col-sm-3">
                    <label > Fecha de Nacimiento </label>
                    <p class="form-control-static">{{Carbon\Carbon::parse($user->birthday)->format('d-m-Y')}} </p>       
                </div>
                <div class="form-group col-sm-3">
                    <label > Edad </label>
                    <p class="form-control-static">{{Carbon\Carbon::parse($user->birthday)->age}}</p>         
                </div>                 
            </div>

            <div class="col-xs-12">                
                 
                <div class="form-group col-sm-3">
                    <label >Contacto de emergencia</label>              
                    <p class="form-control-static">{{$user->contacto_emergencia}}</p>                               
                </div>

                <div class="form-group col-sm-3">
                    <label >Tlf Contacto Emergencia </label>              
                    <p class="form-control-static">{{$user->tlf_contacto_emergencia}}                             
                </div>

                <div class="form-group col-sm-3">
                    <label >Tipo de Sangre </label>              
                    <p class="form-control-static">{{$user->profesion->profesion}}                             
                </div>
                <div class="form-group col-sm-3">
                    <label >Salario Base </label>              
                    <p class="form-control-static">
                        {{$user->oficina->pais->moneda_simbolo}}
                        {{$user->salario_base}} 
                    </p>                            
                </div>
            </div>                 
    </form>
</div>
<!--<div class="row">
    <div class="col-lg-4 col-md-5">
        <div id="edit-user-panel" class="panel panel-default">
            <div class="panel-heading">
                @lang('app.details')
                <div class="pull-right">
                    <a href="{{ route('user.edit', $user->id) }}" class="edit"
                       data-toggle="tooltip" data-placement="top" title="@lang('app.edit_user')">
                        @lang('app.edit')
                    </a>
                </div>
            </div>
            <div class="panel-body panel-profile">
                <div class="image">
                    <img alt="image" class="img-circle" src="{{ $user->present()->avatar }}">
                </div>
                <div class="name"><strong>{{ $user->present()->name }}</strong></div>

                @if ($socialNetworks)
                    <div class="icons">
                        @if ($socialNetworks->facebook)
                            <a href="{{ $socialNetworks->facebook }}" class="btn btn-circle btn-facebook">
                                <i class="fa fa-facebook"></i>
                            </a>
                        @endif

                        @if ($socialNetworks->twitter)
                            <a href="{{ $socialNetworks->twitter }}" class="btn btn-circle btn-twitter">
                                <i class="fa fa-twitter"></i>
                            </a>
                        @endif

                        @if ($socialNetworks->google_plus)
                            <a href="{{ $socialNetworks->google_plus }}" class="btn btn-circle btn-google">
                                <i class="fa fa-google-plus"></i>
                            </a>
                        @endif

                        @if ($socialNetworks->linked_in)
                            <a href="{{ $socialNetworks->linked_in }}" class="btn btn-circle btn-linkedin">
                                <i class="fa fa-linkedin"></i>
                            </a>
                        @endif

                        @if ($socialNetworks->skype)
                            <a href="{{ $socialNetworks->skype }}" class="btn btn-skype">
                                <i class="fa fa-skype"></i>
                            </a>
                        @endif

                        @if ($socialNetworks->dribbble)
                            <a href="{{ $socialNetworks->dribbble }}" class="btn btn-circle btn-dribbble">
                                <i class="fa fa-dribbble"></i>
                            </a>
                        @endif
                    </div>
                @endif

                <br>

                <table class="table table-hover table-details">
                    <thead>
                        <tr>
                            <th colspan="3">@lang('app.contact_informations')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>@lang('app.email')</td>
                            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                        </tr>
                        @if ($user->phone)
                            <tr>
                                <td>@lang('app.phone')</td>
                                <td><a href="telto:{{ $user->phone }}">{{ $user->phone }}</a></td>
                            </tr>
                        @endif

                        @if ($socialNetworks && $socialNetworks->skype)
                            <tr>
                                <td>Skype</td>
                                <td>{{ $socialNetworks->skype }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th colspan="3">@lang('app.additional_informations')</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>@lang('app.birth')</td>
                        <td>{{ $user->present()->birthday }}</td>
                    </tr>
                    <tr>
                        <td>@lang('app.address')</td>
                        <td>{{ $user->present()->fullAddress }}</td>
                    </tr>
                    <tr>
                        <td>@lang('app.last_logged_in')'</td>
                        <td>{{ $user->present()->lastLogin }}</td>
                    </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-8 col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                @lang('app.latest_activity')
                <div class="pull-right">
                    <a href="{{ route('activity.user', $user->id) }}" class="edit"
                       data-toggle="tooltip" data-placement="top" title="@lang('app.complete_activity_log')">
                        @lang('app.view_all')
                    </a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table user-activity">
                    <thead>
                        <tr>
                            <th>@lang('app.action')</th>
                            <th>@lang('app.date')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userActivities as $activity)
                            <tr>
                                <td>{{ $activity->description }}</td>
                                <td>{{ $activity->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>-->

@stop
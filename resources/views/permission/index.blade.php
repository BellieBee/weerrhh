@extends('layouts.app')

@section('page-title', trans('app.permissions'))

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            @lang('app.permissions')
            <small>@lang('app.available_system_permissions')</small>
            <div class="pull-right">
                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
                    <li class="active">@lang('app.permissions')</li>
                </ol>
            </div>
        </h1>
    </div>
</div>

@include('partials.messages')

@if(Entrust::hasRole(['WebMaster']))
    <div class="row tab-search">
        <div class="col-md-2">
            <a href="{{ route('permission.create') }}" class="btn btn-success">
                <i class="glyphicon glyphicon-plus"></i>
                @lang('app.add_permission')
            </a>
        </div>
    </div>
@endif

{!! Form::open(['route' => 'permission.save']) !!}

<div class="table-responsive" id="users-table-wrapper">
    <table class="table">
        <thead>
            <th>@lang('app.name')</th>
            @foreach ($roles as $role)
                @if(Entrust::hasRole('WebMaster'))
                    <th class="text-center">{{ $role->display_name }}</th>
                @else
                    @if($role->id != 8)
                        <th class="text-center">{{ $role->display_name }}</th>
                    @endif
                @endif
            @endforeach
            @if(Entrust::hasRole(['WebMaster']))
                <th class="text-center">@lang('app.action')</th>
            @endif
            </thead>
        <tbody>
        @if (count($permissions))
            @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $permission->display_name ?: $permission->name }}</td>

                    @foreach ($roles as $role)
                        @if(Entrust::hasRole('WebMaster'))
                            <td class="text-center">
                                <div class="checkbox">
                                    {!! Form::checkbox("roles[{$role->id}][]", $permission->id, $role->hasPermission($permission->name)) !!}
                                    <label class="no-content"></label>
                                </div>
                            </td>
                        @else
                            @if($role->id != 8)
                                <td class="text-center">
                                    <div class="checkbox">
                                        {!! Form::checkbox("roles[{$role->id}][]", $permission->id, $role->hasPermission($permission->name)) !!}
                                        <label class="no-content"></label>
                                    </div>
                                </td>
                            @endif
                        @endif 
                    @endforeach

                    @if(Entrust::hasRole(['WebMaster']))

                        <td class="text-center">
                            <a href="{{ route('permission.edit', $permission->id) }}" class="btn btn-primary btn-circle"
                               title="@lang('app.edit_permission')" data-toggle="tooltip" data-placement="top">
                                <i class="glyphicon glyphicon-edit"></i>
                            </a>
                            @if ($permission->removable)
                                <a href="{{ route('permission.destroy', $permission->id) }}" class="btn btn-danger btn-circle"
                                   title="@lang('app.delete_permission')"
                                   data-toggle="tooltip"
                                   data-placement="top"
                                   data-method="DELETE"
                                   data-confirm-title="@lang('app.please_confirm')"
                                   data-confirm-text="@lang('app.are_you_sure_delete_permission')"
                                   data-confirm-delete="@lang('app.yes_delete_it')">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            @endif
                        </td>
                        
                    @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4"><em>@lang('app.no_records_found')</em></td>
            </tr>
        @endif
        </tbody>
    </table>
</div>

@if (count($permissions))
    <div class="row">
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">@lang('app.save_permissions')</button>
        </div>
    </div>
@endif

{!! Form::close() !!}

@stop

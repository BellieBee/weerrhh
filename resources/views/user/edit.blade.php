@extends('layouts.app')

@section('page-title', trans('app.edit_user'))

@section('content')

<h1 class="page-header">
    {{ $user->present()->nameOrEmail }}
    <small>@lang('app.edit_user_details')</small>
    <div class="pull-right">
        <ol class="breadcrumb">
            <li><a href="javascript:;">@lang('app.home')</a></li>
            <li><a href="{{ route('user.list') }}">@lang('app.users')</a></li>
            <li><a href="{{ route('user.show', $user->id) }}">{{ $user->present()->nameOrEmail }}</a></li>
            <li class="active">@lang('app.edit')</li>
        </ol>
    </div>
</h1>

@include('partials.messages')
<div class="row">
    <div class="col-lg-12 col-md-12">        

        <div class="col-md-8">
            {!! Form::open(['route' => ['user.update.details', $user->id], 'method' => 'PUT', 'id' => 'details-form']) !!}
            @include('user.partials.details'/*, ['profile' => false]*/)
            {!! Form::close() !!}
        </div>

        <div class="col-md-4">
            {!! Form::open(['route' => ['user.update.login-details', $user->id], 'method' => 'PUT', 'id' => 'login-details-form']) !!}
            @include('user.partials.auth')
            {!! Form::close() !!}
        </div>
        @if(in_array($user->roles->first()->name, ['Administradora','Coordinadora','Directora','Contralora', 'RepresentantePais']))
            <div class="col-lg-4 col-md-4">
                {!! Form::open(['route' => ['user.update.firma', $user->id], 'files' => true, 'id' => 'avatar-form']) !!}
                    @include('user.partials.avatar')
                {!! Form::close() !!}
            </div>
        @endif
    </div>
</div>
<!-- Nav tabs -->
<!--<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active">
        <a href="#details" aria-controls="details" role="tab" data-toggle="tab">
            <i class="glyphicon glyphicon-th"></i>
            @lang('app.details')
        </a>
    </li>    
   
    <li role="presentation">
        <a href="#auth" aria-controls="auth" role="tab" data-toggle="tab">
            <i class="fa fa-lock"></i>
            @lang('app.authentication')
        </a>
    </li>
    @if($edit && ($pais->pago_pension="retiro" || $pais->pago_indemnizacion="retiro") )
    <li role="presentation">
        <a href="#liquidacion" aria-controls="liquidacion" role="tab" data-toggle="tab">
            <i class="fa fa-lock"></i>
            Liquidaci??n
        </a>
    </li>
    @endif
</ul>-->

<!-- Tab panes 
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="details">
        
    </div>
    
    <div role="tabpanel" class="tab-pane" id="auth">
        <div class="row">
            <div class="col-md-8">
                {!! Form::open(['route' => ['user.update.login-details', $user->id], 'method' => 'PUT', 'id' => 'login-details-form']) !!}
                    @include('user.partials.auth')
                {!! Form::close() !!}
            </div>
             @if(in_array($user->roles->first()->name, ['Administradora','Coordinadora','Directora']))
            <div class="col-lg-4 col-md-4">
                {!! Form::open(['route' => ['user.update.firma', $user->id], 'files' => true, 'id' => 'avatar-form']) !!}
                    @include('user.partials.avatar')
                {!! Form::close() !!}
            </div>
            @endif
        </div>-->

        <!--<div class="row">
            <div class="col-md-8">
                @if (settings('2fa.enabled'))
                    <?php $route = Authy::isEnabled($user) ? 'disable' : 'enable'; ?>

                    {!! Form::open(['route' => ["user.two-factor.{$route}", $user->id], 'id' => 'two-factor-form']) !!}
                        @include('user.partials.two-factor')
                    {!! Form::close() !!}
                @endif
            </div>
        </div>
    </div>-->

    <!--@if($edit && ($pais->pago_pension="retiro" || $pais->pago_indemnizacion="retiro") )
    <div role="tabpanel" class="tab-pane" id="liquidacion">
             
    </div>
    @endif-->
</div>

@stop

@section('styles')
    {!! HTML::style('assets/css/bootstrap-datetimepicker.min.css') !!}
    {!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop

@section('scripts')
    {!! HTML::script('assets/plugins/croppie/croppie.js') !!}
    {!! HTML::script('assets/js/moment.min.js') !!}
    {!! HTML::script('assets/js/bootstrap-datetimepicker.min.js') !!}
    {!! HTML::script('assets/js/as/btn.js') !!}
    {!! HTML::script('assets/js/as/profile.js') !!}    
    {!! JsValidator::formRequest('Vanguard\Http\Requests\User\UpdateLoginDetailsRequest', '#login-details-form') !!}

    
    
@stop
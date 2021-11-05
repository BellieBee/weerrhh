@extends('layouts.app')

@section('page-title', trans('app.edit_user'))

@section('content')

<h1 class="page-header">
    {{ $user->present()->nameOrEmail }}
    <small>@lang('app.edit_user_details')</small>
    <div class="pull-right">
        <ol class="breadcrumb">
            <li><a href="javascript:;">@lang('app.home')</a></li>
            <li><a href="{{ route('user.show', $user->id) }}">{{ $user->present()->nameOrEmail }}</a></li>
        </ol>
    </div>
</h1>

@include('partials.messages')
<div class="row">
    <div class="col-lg-12 col-md-12">        
        <div class="col-lg-4 col-md-4">
            {!! Form::open(['route' => ['user.update.firma', $user->id], 'files' => true, 'id' => 'avatar-form']) !!}
                @include('user.partials.avatar')
            {!! Form::close() !!}
        </div>
    </div>
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
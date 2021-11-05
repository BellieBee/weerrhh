@extends('layouts.app')

@section('page-title', trans('app.edit_user'))

@section('content')

<h1 class="page-header">
    {{ $user->present()->nameOrEmail }}
    <small>@lang('app.edit_user_details')</small>   
</h1>

@include('partials.messages')
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="col-md-4">
            {!! Form::open(['route' => ['user.update.login-details', $user->id], 'method' => 'PUT', 'id' => 'login-details-form']) !!}
            @include('user.partials.auth')
            {!! Form::close() !!}
        </div>
        
    </div>
</div>

</div>

@stop

@section('scripts')
    {!! HTML::script('assets/plugins/croppie/croppie.js') !!}
    {!! HTML::script('assets/js/moment.min.js') !!}
    {!! HTML::script('assets/js/bootstrap-datetimepicker.min.js') !!}
    {!! HTML::script('assets/js/as/btn.js') !!}
    {!! HTML::script('assets/js/as/profile.js') !!}    
    {!! JsValidator::formRequest('Vanguard\Http\Requests\User\UpdateLoginDetailsRequest', '#login-details-form') !!}   
   
@stop
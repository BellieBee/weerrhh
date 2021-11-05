@extends('layouts.auth')

@section('page-title', trans('app.login'))

@section('content')

<div class="form-wrap login_rola col-md-5 auth-form" id="login">
    <div class="row" style=" margin-bottom: 28px;">
        <div class="col-xs-5" style="text-align: center;">
        <img src="{{ url('/img/logo-t.png') }}" class="img-responsive">
    </div>
    <div class="col-xs-7" style="text-align: right;">
        <div style="font-size: 25px;">SISTEMA ROLA</div>
    </div>
    </div>

    {{-- This will simply include partials/messages.blade.php view here --}}
    @include('partials/messages')

    <form role="form" action="<?= url('login') ?>" method="POST" id="login-form" autocomplete="off">
        <input type="hidden" value="<?= csrf_token() ?>" name="_token">

        @if (Input::has('to'))
            <input type="hidden" value="{{ Input::get('to') }}" name="to">
        @endif

        <div class="form-group input-icon">
            <label for="username" class="sr-only">@lang('app.email_or_username')</label>
            <i class="fa fa-user"></i>
            <input type="email" name="username" id="username" class="form-control" placeholder="@lang('app.email_or_username')" >
        </div>
        <div class="form-group password-field input-icon">
            <label for="password" class="sr-only">@lang('app.password')</label>
            <i class="fa fa-lock"></i>
            <input type="password" name="password" id="password" class="form-control" placeholder="@lang('app.password')" >
            
        </div>
        <div class="text-right btn-reset-password">
             <a href="<?= url('password/remind') ?>" class="forgot">@lang('app.i_forgot_my_password')</a>
        </div>
       
        <div class="form-group">
             <button type="submit" class="btn btn-custom btn-lg btn-block" id="btn-login">
                @lang('app.log_in')
            </button>
        </div>
       
    </form>

   

</div>

@stop

@section('scripts')
    {!! HTML::script('assets/js/as/login.js') !!}
    {!! JsValidator::formRequest('Vanguard\Http\Requests\Auth\LoginRequest', '#login-form') !!}
@stop
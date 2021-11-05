@extends('layouts.app')

@section('page-title', trans('app.add_user'))

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            @lang('app.create_new_user')
            <small>@lang('app.user_details')</small>
            <div class="pull-right">
                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
                    <li><a href="{{ route('user.list') }}">@lang('app.users')</a></li>
                    <li class="active">@lang('app.create')</li>
                </ol>
            </div>
        </h1>
    </div>
</div>

@include('partials.messages')

{!! Form::open(['route' => 'user.store', 'files' => true, 'id' => 'create_user_form']) !!}
    {!! csrf_field() !!}
    <div class="row">
        <div class="col-md-8">
            @include('user.partials.details', ['edit' => false, 'profile' => false])
        </div>
        <div class="col-md-4">
            @include('user.partials.auth', ['edit' => false])
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-right">
            <button type="submit" class="btn btn_color">
                <i class="fa fa-save"></i>
                @lang('app.create_user')
            </button>
        </div>
    </div>
{!! Form::close() !!}

@stop

@section('styles')
    {!! HTML::style('assets/css/bootstrap-datetimepicker.min.css') !!}
@stop

@section('scripts')
    {!! HTML::script('assets/js/moment.min.js') !!}
    {!! HTML::script('assets/js/bootstrap-datetimepicker.min.js') !!}
    {!! HTML::script('assets/js/as/profile.js') !!}
    
    <script type="text/javascript">
    //$('#create_user_form input').attr('required',false);
    
    $(document).on('submit', '#create_user_form', function(event) {
        event.preventDefault();
        form=this;
        email=$('#email').val();
        password=$('#password').val();
        password_confirmation=$('#password_confirmation').val();
        
        
        if(password=="" || password_confirmation==""){
            $('.auth_msg ul').html('<li> Campo de Contraseña vacio </li>');
            return false;  
        }else if (password!="" && password_confirmation!="" && password!=password_confirmation) {
            $('.auth_msg ul').html('<li> Las Contraseñas no coinciden </li>');
            return false;  
        }
        $('.auth_msg ul').html('<li><i class="glyphicon glyphicon-refresh"></i> Validando..</li>');

        data=$('#create_user_form').serialize();
        console.log(data);
        $.ajax({
            url: '{{url("user/validacion")}}',
            type: 'post',
            dataType: 'json',
            data: data,
        })
        .done(function(data) {
        $('.auth_msg ul').html('<li><i class="glyphicon glyphicon-refresh"></i> Guardando Informacion</li>')
           $(form)
            .find(':input[type=submit]')
            .attr('disabled', true)
            .html('<i class="glyphicon glyphicon-refresh"></i> Guardando Informacion')
            .css('color', '#000')
            ;
            form.submit(); 
        })
        .fail(function(data) {
            console.log(data);
            $('.auth_msg ul').html('');
            $.each( data.responseJSON, function( key, value ) {
                $(".auth_msg ul").append('<li>'+value[0]+'</li>');
            });
        })
        .always(function() {
            console.log("complete");
        });
        

    });
</script>
@stop
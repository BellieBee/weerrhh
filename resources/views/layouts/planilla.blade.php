<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title') | {{ settings('app_name') }}</title>

 
    <link rel="shortcut icon" href="{{ url('/favico.ico') }}">
    <meta name="application-name" content="{{ settings('app_name') }}"/>
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta name="msapplication-TileImage" content="{{ url('assets/img/icons/mstile-144x144.png') }}" />
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    {{-- For production, it is recommended to combine following styles into one. --}}
    {!! HTML::style('assets/css/bootstrap.min.css') !!}
    
    {!! HTML::style('assets/css/metisMenu.css') !!}
    {!! HTML::style('assets/css/sweetalert.css') !!}
    
    {!! HTML::style('assets/css/app.css') !!}
    
    @yield('styles')
</head>
<body class="gray-bg">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            
            <div id="navbar" class="navbar-collapse">
                <a href="{{url('/planilla/normal')}}" class="btn btn_color" style="margin: 8px 5px 5px 6px"> <i class="glyphicon glyphicon-chevron-left"></i> Volver</a>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            
                            {{ Auth::user()->present()->name }}
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">                            
                            @if (config('session.driver') == 'database' && Entrust::hasRole('Administradora'))
                                <li>
                                    <a href="{{ route('profile.sessions') }}">
                                        <i class="fa fa-list"></i>
                                        @lang('app.active_sessions')
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('auth.logout') }}">
                                    <i class="fa fa-sign-out"></i>
                                    @lang('app.logout')
                                </a>
                            </li>

                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav> 
    <div  class=" container-fluid"  style="margin-top: 15px;">
        <div class="animated fadeInRight">
            <div class="row">
                <div class="col-xs-12 col-lg-12 ">
                    <div class="ibox float-e-margins">                
                        <div class="ibox-content">
                            
                        @yield('content')
                        </div>  
                    </div>  
                </div>  
            </div>  
        </div>  
    </div>

    {{-- For production, it is recommended to combine following scripts into one. --}}
    {!! HTML::script('assets/js/jquery-2.1.4.min.js') !!}
    {!! HTML::script('assets/js/bootstrap.min.js') !!}
    {!! HTML::script('assets/js/metisMenu.min.js') !!}
    {!! HTML::script('assets/js/sweetalert.min.js') !!}
    {!! HTML::script('assets/js/delete.handler.js') !!}
    {!! HTML::script('assets/plugins/js-cookie/js.cookie.js') !!}
    <link href="{{url('/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">
    <script src="{{url('/js/plugins/dataTables/datatables.min.js')}}"></script>
    
    <script type="text/javascript">
        
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
    </script>
    {!! HTML::script('assets/js/jsvalidation/js/jsvalidation.js') !!}
    {!! HTML::script('assets/js/as/app.js') !!}
    @yield('scripts')
</body>
</html>
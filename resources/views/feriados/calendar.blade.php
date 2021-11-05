@extends('layouts.app')

@section('page-title', 'Feriados')

@section('css')
    {!! HTML::style('fullcalendar/fullcalendar.css') !!}

    <style type="text/css">
        .pais {padding: 7px; color: #fff; border-radius: 3px 3px;}
    </style>

@section('content')


    <h1 class="page-header">
        Feriados
    </h1>
    <div class="form-group">
        <h3>Leyenda</h3>
        <div class="row">
            <div class="col-sm-1" style="width: 14.2%">
               <span class="pais" style="background-color: #4577EA">Guatemala</span> 
            </div>
            <div class="col-sm-1" style="width: 14.2%">
               <span class="pais" style="background-color: #30A530">Bolivia</span> 
            </div>
            <div class="col-sm-1" style="width: 14.2%">
               <span class="pais" style="background-color: #D77C23">Nicaragua</span> 
            </div>
            <div class="col-sm-1" style="width: 14.2%">
               <span class="pais" style="background-color: #4542C0">Honduras</span> 
            </div>
            <div class="col-sm-1" style="width: 14.2%">
               <span class="pais" style="background-color: #D83737">Paraguay</span> 
            </div>
            <div class="col-sm-1" style="width: 14.2%">
               <span class="pais" style="background-color: #9FE151">Salvador</span> 
            </div>
            <div class="col-sm-1" style="width: 14.2%">
               <span class="pais" style="background-color: #e6b71d">Colombia</span> 
            </div>
        </div>
    </div>

    @permission('descargar-feriados')
    <div class="form-group ">
        <a href="{{ route('feriados.calendar.descargar') }}"                       
            class=" btn btn_color btn_gris descargar_planilla">                       
            <i class="glyphicon glyphicon-download-alt" aria-hidden="true"></i> Descargar Calendario
        </a>
    </div>
    @endpermission

    <div class="col l7">
        <div id="calendar"></div>
    </div>


@stop

@section('scripts')
    {!! HTML::script('fullcalendar/lib/jquery.min.js') !!}
    {!! HTML::script('fullcalendar/lib/moment.min.js') !!}
    {!! HTML::script('fullcalendar/fullcalendar.js') !!}
    {!! HTML::script('fullcalendar/locale/es.js') !!}

    <script>
        //inicializamos el calendario al cargar la pagina
        $(document).ready(function() {
            var currentLangCode = 'es';
            

            $('#calendar').fullCalendar({
                
                eventClick: function(calEvent, jsEvent, view) {
                    $(this).css('background', 'red');
                },

                header: {
                    left: 'prev,next today myCustomButton',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },

                lang: currentLangCode,
                editable: true,
                eventLimit: true,
                events: {
                    url: "{{url('/feriados/calendar/event')}}"
                },
                eventBorderColor: '#ccc',
 
            });
 
        });
    </script>

@stop
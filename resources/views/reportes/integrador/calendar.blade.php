@extends('layouts.app')

@section('page-title', 'Feriados')

@section('css')
    {!! HTML::style('fullcalendar/fullcalendar.css') !!}

    <style type="text/css">
        .pais {padding: 7px; color: #fff; border-radius: 3px 3px;}
    </style>

@section('content')


    <h1 class="page-header">
        Reporte Integrador
    </h1>
    <div class="form-group">
        <h3>Leyenda</h3>
        <div class="row">
            <div class="col-sm-6">
               <span class="pais" style="background-color: {{ $pais->color }}">{{ $pais->pais }}</span> 
            </div>
        </div>
    </div>

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
                    url: "{{url('/integrador/calendar/event')}}"
                },
                eventBorderColor: '#ccc',
 
            });
 
        });
    </script>

@stop
@extends('layouts.app')

@section('page-title', 'Editar Feriado')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Editar feriado
            <div class="pull-right">
                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
                    <li><a href="{{ route('feriados.list') }}">Feriados</a></li>
                    <li class="active">Editar Feriado</li>
                </ol>
            </div>
        </h1>
    </div>
</div>

@include('partials.messages')

{!! Form::model($feriado, array ('route' => ['feriados.update', $feriado->id], 'method' => 'PUT')) !!}
	<div class="row">

		<div class="panel panel-default">
			<div class="panel-heading">
				Modifica el día feriado
			</div>
			<div class="panel-body">
				<input type="hidden" name="id" value="{{ $feriado->id }}">
				<div class="form-group col-sm-4">
					{!! Form::label('dia', 'Día') !!}
					{!! Form::selectRange('dia', 01, 31, $feriado->dia, ['class' => 'form-control']) !!}
				</div>
				<div class="form-group col-sm-2">
					{!! Form::label('mes', 'Mes') !!}
					{!! Form::select('month_id', $meses->pluck('month', 'id')->all(), $feriado->month_id, ['class' => 'form-control']) !!}
				</div>
				<div class="form-group col-sm-4">
					{!! Form::label('fecha', 'Edita la fecha en el calendario por favor') !!}
					{!! Form::date('fecha', Carbon\Carbon::parse($feriado->fecha), ['class' => 'form-control']); !!}
				</div>
				@if(Entrust::can('crear-feriado') && Entrust::can('editar-feriado'))
					<div class="form-group col-sm-4">
						{!! Form::label('pais', 'Edita el país por favor') !!}
						{!! Form::select('pais_id', $paises->pluck('pais', 'id')->all(), $feriado->pais_id, ['class' => 'form-control']); !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('descripcion', 'Descripción') !!}
						{!! Form::text('descripcion_feriado', $feriado->descripcion_feriado, ['class' => 'form-control']) !!}
					</div>
				@elseif(Entrust::can('crear-feriado-pais') || Entrust::can('editar-feriado'))
					<div class="form-group col-sm-2">
						{!! Form::label('pais', 'País: '.$feriado->pais->pais) !!}
						<input type="hidden" name='pais_id' value="{{$feriado->pais_id}}">
					</div>
					<div class="form-group col-sm-5">
						{!! Form::label('descripcion', 'Descripción') !!}
						{!! Form::text('descripcion_feriado', null, ['class' => 'form-control']) !!}
					</div>
				@endif
			</div>
			
		</div>
		<div class="row">
	        <div class="col-md-12 text-right">
	            <button type="update" class="btn btn_color">
	                <i class="fa fa-save"></i>
	                Actualizar Feriado
	            </button>
	        </div>
    	</div>
		
	</div>
{!! Form::close() !!}

@stop
@extends('layouts.app')

@section('page-title', 'Crear Feriado')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Crear nuevo feriado
            <div class="pull-right">
                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
                    <li><a href="{{ route('feriados.list') }}">Feriados</a></li>
                    <li class="active">Crear Feriado</li>
                </ol>
            </div>
        </h1>
    </div>
</div>

@include('partials.messages')

{!! Form::open(array ('route' => 'feriados.store')) !!}
	<div class="row">

		<div class="panel panel-default">
			<div class="panel-heading">
				Ingresa un día feriado
			</div>
			<div class="panel-body">
				<div class="form-group col-sm-2">
					{!! Form::label('dia', 'Día') !!}
					{!! Form::selectRange('dia', 01, 31, null, ['class' => 'form-control']) !!}
				</div>
				<div class="form-group col-sm-2">
					{!! Form::label('mes', 'Mes') !!}
					{!! Form::select('month_id', $meses->pluck('month', 'id')->all(), null, ['class' => 'form-control']) !!}
				</div>
				<div class="form-group col-sm-4">
					{!! Form::label('fecha', 'Apunta la fecha en el calendario por favor') !!}
					{!! Form::date('fecha', null, ['class' => 'form-control']); !!}
				</div>
				@if(Entrust::can('crear-feriado'))
					<div class="form-group col-sm-4">
						{!! Form::label('pais', 'Ingresa un país') !!}
						{!! Form::select('pais_id', $paises->pluck('pais', 'id')->all(), null, ['class' => 'form-control']) !!}
					</div>
					<div class="form-group col-sm-4">
						{!! Form::label('descripcion', 'Descripción') !!}
						{!! Form::text('descripcion_feriado', null, ['class' => 'form-control']) !!}
					</div>
				@elseif(Entrust::can('crear-feriado-pais'))
					<div class="form-group col-sm-2">
						{!! Form::label('pais', 'País: '.$pais->pais) !!}
						<input type="hidden" name="pais_id" value="{{$pais->id}}">
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
	            <button type="submit" class="btn btn_color">
	                <i class="fa fa-save"></i>
	                Guardar Feriado
	            </button>
	        </div>
    	</div>
		
	</div>
{!! Form::close() !!}

@stop


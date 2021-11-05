@extends('layouts.app')

@section('page-title', 'Centros de Costo')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Actualizar Centro de Costo           
        </h1>
    </div>
</div>

@include('partials.messages')

<form class="form-horizontal orden_form" action="{{route('centrocosto.update', $centrocosto->id)}}" method="post" enctype="multipart/form-data">
	{!! csrf_field() !!}
	@if(Entrust::can('crear-centrocosto-oficina'))
		<input type="hidden" name="oficina_id" value="{{$centrocosto->oficina_id}}">
		<input type="hidden" name="pais_id" value="{{$centrocosto->pais_id}}">
	@endif
		
	<div class="row">

		<div class="form-group">
		    <label class="control-label col-sm-2">Oficina:</label>
		    <div class="col-sm-4">
		    	@if(Entrust::can('crear-centrocosto-todos'))
		    		<select class="form-control" id="oficinas" name="oficina_id" required>
		    			<option value="{{$centrocosto->oficina_id}}">{{$centrocosto->oficina->oficina}}</option>
		    			<option disabled>Seleccione una oficina</option>
		    			@foreach($oficinas as $oficina)
		    				@if($oficina->id != $centrocosto->oficina_id)
		    					<option value="{{$oficina->id}}">{{$oficina->oficina}}</option>
		    				@endif
		    			@endforeach
		    		</select>
		    	@elseif(Entrust::can('crear-centrocosto-oficina'))
		      		<input type="text" class="form-control" disabled  value="{{$centrocosto->oficina->oficina}}">
		      	@endif
		    </div>
		    <label class="control-label col-sm-2">País:</label>
		    <div class="col-sm-2">
		    	@if(Entrust::can('crear-centrocosto-todos'))
		    		<input type="hidden" id="pais_id" name="pais_id" value="{{$centrocosto->pais_id}}">
		    		<input type="text" class="form-control" id="pais" disabled name="pais" value="{{$centrocosto->pais->pais}}">
		    	@elseif(Entrust::can('crear-centrocosto-oficina'))
		      		<input type="text" class="form-control" disabled  value="{{$centrocosto->pais->pais}} " >
		      	@endif
		    </div>
		</div>	

		<div class="form-group">
			<label class="control-label col-sm-2">Código:</label>
		    <div class="col-sm-4">
		      	<input type="text" class="form-control" name="codigo" value="{{$centrocosto->codigo}}" required>
		    </div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-2">Descripción:</label>
		    <div class="col-sm-4">
		    	<textarea class="form-control" name="descripcion" required>{{$centrocosto->descripcion}}</textarea>
		    </div>
		</div>
		
		<div class="col-md-12 text-right">

			@if(Entrust::can(['editar-centrocosto']))
				<button type="submit" class="btn btn_color submit" >
		           Actualizar Centro de Costo
		        </button>
			@endif
    	</div>
	</form>

@stop

@section('scripts')
{!! HTML::script('assets/js/moment.min.js') !!}
{!! HTML::script('assets/js/moment_es.js') !!}

{!! HTML::script('assets/js/bootstrap-datepicker.min.js') !!}
{!! HTML::script('assets/js/bootstrap-datepicker.es.min.js') !!}


<script type="text/javascript">	

	$(document).on('submit', '.orden_form', function(event) {	
		event.preventDefault();	
		
		$(this)
			.find(':input[type=submit]')
		    .attr('disabled', true)
		    .html('<i class="glyphicon glyphicon-refresh"></i> Guardando datos')
		    .css('color', '#000');
		
		this.submit();
	});

	$(document).on('change', '#oficinas', function(event) {
		event.preventDefault();

		let oficina = $("#oficinas").val();

		$.ajax({
			type:'post',
            url: '{{ url('proveedor/buscarPais') }}',
            dataType: 'json',
            data: {"oficina": oficina},
            success: function(data) {
                $("#pais_id").attr('value', data['id']);
                $("#pais").attr('value', data['pais']); 

                //console.log(data);
                //alert(data.success);
            },
            error : function() {
                alert('error...');
                //console.log(data);
            },
		});
	});
	
</script>
@stop
@section('styles')
    {!! HTML::style('assets/css/bootstrap-datepicker.min.css') !!}
    {!! HTML::style('assets/plugins/croppie/croppie.css') !!}
@stop
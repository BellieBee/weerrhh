@extends('layouts.app')

@section('page-title', 'Proveedores')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Crear Proveedor            
        </h1>
    </div>
</div>

@include('partials.messages')

<form class="form-horizontal orden_form" action="{{route('proveedor.store')}}" method="post" enctype="multipart/form-data">
	{!! csrf_field() !!}
	@if(Entrust::can('crear-proveedor-oficina'))
		<input type="hidden" name="oficina_id" value="{{$oficinas[0]->id}}">
		<input type="hidden" name="pais_id" value="{{$oficinas[0]->pais_id}}">
	@endif
		
	<div class="row">

		<div class="form-group">
		    <label class="control-label col-sm-2">Oficina:</label>
		    <div class="col-sm-4">
		    	@if(Entrust::can('crear-proveedor-todos'))
		    		<select class="form-control" id="oficinas" name="oficina_id">
		    			<option value="">Seleccione una oficina</option>
		    			@foreach($oficinas as $oficina)
		    				<option value="{{$oficina->id}}">{{$oficina->oficina}}</option>
		    			@endforeach
		    		</select>
		    	@elseif(Entrust::can('crear-proveedor-oficina'))
		      		<input type="text" class="form-control" disabled  value="{{$oficinas[0]->oficina}}">
		      	@endif
		    </div>
		    <label class="control-label col-sm-2">País:</label>
		    <div class="col-sm-2">
		    	@if(Entrust::can('crear-proveedor-todos'))
		    		<input type="hidden" id="pais_id" name="pais_id">
		    		<input type="text" class="form-control" id="pais" disabled name="pais">
		    	@elseif(Entrust::can('crear-proveedor-oficina'))
		      	<input type="text" class="form-control" disabled  value="{{$oficinas[0]->pais->pais}} " >
		      	@endif
		    </div>
		</div>	

		<div class="form-group">
			<label class="control-label col-sm-2">Nombre:</label>
		    <div class="col-sm-4">
		      	<input type="text" class="form-control" name="nombre" required>
		    </div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Email:</label>
		    <div class="col-sm-4">
		      	<input type="email" class="form-control" name="email" required>
		    </div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Teléfono:</label>
		    <div class="col-sm-4">
				<input type="text" class="form-control" name="telf">
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Razón Social:</label>
		    <div class="col-sm-4">
				<input type="text" class="form-control" name="razon_social">
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Nit:</label>
		    <div class="col-sm-4">
				<input type="text" class="form-control" name="nit">
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Dirección Fiscal:</label>
		    <div class="col-sm-6">
				<textarea class="form-control" required name="direccion_fiscal"></textarea>
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Giro Negocio:</label>
		    <div class="col-sm-4">
				<input type="text" class="form-control" name="giro_negocio">
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Proveedor desde:</label>
		    <div class="col-sm-4">
				<input type="date" class="form-control" name="proveedor_desde">
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Banco:</label>
		    <div class="col-sm-4">
				<input type="text" class="form-control" name="banco">
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">N° Cuenta Bancaria:</label>
		    <div class="col-sm-4">
				<input type="text" class="form-control" name="n_cuenta_bancaria">
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Tipo de Cuenta:</label>
		    <div class="col-sm-4">
				<input type="text" class="form-control" name="tipo_cuenta">
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Moneda:</label>
		    <div class="col-sm-4">
				<input type="text" class="form-control" name="moneda">
			</div>
		</div>

		<div class="form-group">
		    <label class="control-label col-sm-2">Email de Notificación de Pago:</label>
		    <div class="col-sm-4">
				<input type="email" class="form-control" name="email_notf_pago">
			</div>
		</div>
		
		<div class="col-md-12 text-right">

			@if(Entrust::can(['crear-proveedor-todos','crear-proveedor-oficina']))
				<button type="submit" class="btn btn_color submit" >
		           Crear Proveedor
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
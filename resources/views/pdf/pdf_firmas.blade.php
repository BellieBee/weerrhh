@include('pdf.pdf_encabezado')
<h2>Firmas Digitales</h2>
<div style="margin-top: 100px;">
	<div class="firmas" >

		{!! $planilla->firma_administradora !!}

		________________________<br>
		<b>{{$planilla->administradora->roles->first()->name}}</b><br>		
		{{$planilla->administradora->first_name}} {{$planilla->administradora->last_name}}<br>
		{{$planilla->administradora->cargo->cargo}}<br>
	</div>

	<div class="firmas">

		{!! $planilla->firma_coordinadora !!}
		
		________________________<br>
		@if($planilla->coordinadora)
		<b>{{$planilla->coordinadora->roles->first()->name}}</b><br>
		{{$planilla->coordinadora->first_name}} {{$planilla->coordinadora->last_name}}<br>
		{{$planilla->coordinadora->cargo->cargo}}<br>
		@else
		<br><br>
		@endif

	</div>

	<div class="firmas">

		{!! $planilla->firma_directora !!}
		________________________<br>
		
		@if($planilla->directora)
		<b>{{$planilla->directora->roles->first()->name}}</b><br>
		{{$planilla->directora->first_name}} {{$planilla->directora->last_name}}<br>
		{{$planilla->directora->cargo->cargo}}<br>
		@else
		<b>Directora</b><br>
		<br><br>
		@endif

	</div>
</div>

<div style="width: 65%;font-size: 12px;" >
	<b>NOMBRE DE QUIEN LO EXPIDE: </b>{{auth()->user()->first_name}} {{auth()->user()->last_name}}<br>
	<b>FECHA: </b> {{date("Y-m-d H:i:s")}}<br>
	<b>Rango de fechas: </b>{{$planillas->fecha_inicio}} - {{$planillas->fecha_fin}} <br>
	<b>Oficinas: </b>{{implode(' - ', $oficinas->pluck('oficina')->toArray())}} <br>
	<b>Moneda: </b>{{(count($oficinas->unique())>1)?'Corona Sueca':$planillas->first()->oficina->pais->moneda_nombre}} <br>
	</div>
	<div style="position: absolute;
	    right: 0;
	    top: 0;">
		<img src="img/logo-p1.png" >
	</div>

	<div style="text-align: center;margin-top: 30px;"><b>REPORTE DE EMPLEADOS</b></div>
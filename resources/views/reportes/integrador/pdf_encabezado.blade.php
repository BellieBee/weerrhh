<div style="width: 65%;font-size: 12px;" >
	<b>NOMBRE DE QUIEN LO EXPIDE: </b>{{auth()->user()->first_name}} {{auth()->user()->last_name}}<br>
	<b>FECHA: </b> {{date("Y-m-d H:i:s")}}<br>
	<b>Rango de fechas: </b>{{$reporte->fecha_inicio}} - {{$reporte->fecha_fin}} <br>
	<b>Oficnas: </b>{{implode(' - ', $oficinas->pluck('oficina')->toArray())}} <br>	
	</div>
	<div style="position: absolute;
	    right: 0;
	    top: 0;">
		<img src="img/logo-p1.png" >
	</div>


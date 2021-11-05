@if ($oficina->oficina == 'Guatemala UE' || $oficina->oficina == 'Colombia UE' || $oficina->oficina == 'Honduras UE')
<div class="container" style="    position: absolute;
    left: 0;
    top: 0;">
	<img src="img/logo-p1.png" >
</div>

<div style="    position: absolute;
    right: 0;
    top: 0;">
	<img src="img/logo-p2.png" >
</div>
@else
<div class="container" style="    position: absolute;
    right: 0;
    top: 0;">
	<img src="img/logo-p1.png" >
</div>
@endif

<div class="container">
    <br>
    <br>
    <br>
    <br>
</div>

<div class="container" style="width: 45%;font-size: 12px;" >
<b>NIT: </b>{{$oficina->nit}}<br>
<b>Nº PATRONAL: </b> {{$oficina->num_patronal}}<br>
<b>Direccion: </b>{{$oficina->direccion}}<br>
<b>Telf: </b>{{$oficina->telf}}<br>
<b>Pais: </b>{{$pais->pais}}<br>
</div>

<center><b>PLANILLA DE SUELDOS CORRESPONDIENTE AL MES DE {{strtoupper($planilla->m_a)}}</b></center>
<center><b>(Expresado en {{$pais->moneda_nombre}})  </b></center>
<br>

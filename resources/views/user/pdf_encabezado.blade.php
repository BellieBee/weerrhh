<div class="container" style="    position: absolute;
    right: 0;
    top: 0;">
	<img src="img/logo-p1.png" >
</div>

<div class="container">
    <br>
    <br>
    <br>
    <br>
</div>

<div class="container" style="width: 45%;font-size: 12px;" >
<b>NIT: </b>{{$user->oficina->nit}}<br>
<b>Nº PATRONAL: </b> {{$user->oficina->num_patronal}}<br>
<b>Direccion: </b>{{$user->oficina->direccion}}<br>
<b>Telf: </b>{{$user->oficina->telf}}<br>
<b>Pais: </b>{{$user->oficina->pais->pais}}<br>
</div>

<center><h3><b>DATOS DEL COLEGA {{$user->first_name}} {{$user->last_name}}</b></h3></center>

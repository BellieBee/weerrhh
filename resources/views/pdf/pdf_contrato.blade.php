
  <body>  	
	<div id="header">
    	<span> F-GR-RH-02</span><br> 
		<span>Contrato de prestación de servicios</span>
  	</div>
							
	<div style="text-align: center;margin-top: 50px;">
		<img src="{{url('/img/logo-p1.png')}}"><br>
	
		OFICINA REGIONAL PARA AMERICA LATINA 	<br>							
		Telf: (502) 2439 2798 - 2439 5948<br>
		15 Avenida "A" 5-00, zona 13, Guatemala · E-mail: americalatina@weeffect.org
		<br><br>
		<b>CONTRATO DE PRESTACION DE SERVICIOS DE CONSULTORIA</b><br>
		<b>{{$contrato->n_contrato}}</b>
		<br>
		<p>
			Entre nosotros, <b>We Effect </b> - Oficina Regional para América Latina
									
			, bajo el registro jurídico en
			 <b>{{$director->oficina->pais->pais}} nro. 9702-161067-101-9</b>
			 , representada por  
			 <b>{{$directora->first_name}} {{$directora->last_name}}</b>  
			 quien es Directora Regional y ejerce la Dirección Regional y 
			 @if($contrato->user_id != null)<b>{{$user->first_name}} {{$user->last_name}}</b> @elseif($contrato->proveedor_id != null) <b>{{$contrato->proveedor->razon_social}}</b> @else <b>{{$contrato->proveedorUser->first_name}} {{$contrato->proveedorUser->last_name}}</b>@endif
			 @if($contrato->user_id != null)  
			 con documento 
			 <b>{{$user->tipo_documento->tipo_documento}} {{$user->documento}}</b>
			 , en su condición de {{$user->sexo ? 'consultora':'consultor'}} @elseif($contrato->proveedor_id) con <b>NIT {{$contrato->proveedor->nit}}</b>, en su condición de Proveedor @else con documento <b>{{$contrato->proveedorUser->tipo_documento->tipo_documento}} {{$contrato->proveedorUser->documento}}</b>, en su condición de Proveedor @endif, convienen en celebrar el presente contrato de servicios de Consultoría, sujeto a las siguientes cláusulas
		</p>
	</div>

	
	<p style="margin: 20px; text-align: center;"><b>CLAUSULAS</b></p>

	<b> 1. OBJETIVO DEL CONTRATO</b>
	<p class="sub_seccion">{!! $contrato->objetivo !!}</p>

	<p><b>2. ALCANCE DEL CONTRATO</b></p>
	<p class="sub_seccion">{!! $contrato->alcance !!}</p>

	<p class="sub_seccion">
								
		2.1 {{$user->consultor}} conviene en prestar sus servicios a We Effect, cuyas responsabilidades, productos y otras características específicas se detallan en los términos de referencia que se adjuntan y que forman parte integral de este contrato.
	</p>
	<p class="sub_seccion">

		2.2 We Effect, a través de su Dirección Regional para América Latina, tendrá el derecho de coordinar y dar seguimiento en todo tiempo a las actividades, servicios y productos objeto de este Contrato, y dar a  {{$user->consultor}} por escrito las instrucciones que estime pertinentes relacionadas con su ejecución, a fin de que se ajusten al programa y términos de referencia correspondientes, así como a las modificaciones que en su caso se dispongan. 
	</p>
	<p class="sub_seccion">
								
		2.3 We Effect dará por recibidos los productos o servicios objeto de este Contrato si los mismos hubieren sido realizados de acuerdo con los términos de referencia, programa de trabajo y demás estipulaciones convenidas.  
	</p>
	<p class="sub_seccion">

		2.4 {{$user->consultor}} será la única persona responsable por la ejecución de los servicios y actividades contratadas cuando no se ajusten a este Contrato y a las instrucciones dadas por escrito por We Effect. Cuando las actividades no se hubieren ejecutado de acuerdo a este Contrato y sus anexos y con las instrucciones por escrito de We Effect, éste dispondrá su corrección o reposición inmediata por parte de {{$user->consultor}}  que no tendrá derecho a ninguna retribución por los trabajos mal ejecutados
	</p> 

	<p><b>3. ACTIVIDADES A REALIZAR</b></p>
	<p class="sub_seccion">{!! $contrato->actividades !!}</p>

	<p><b>4. METODOLOGIA DE TRABAJO</b></p>
	<p class="sub_seccion">{!! $contrato->metodologia !!}</p>

	<p><b>5. PERIODO DE CONTRATACION</b></p>
	<p class="sub_seccion">{{$user->consultor}} se obliga a iniciar las actividades objeto de este Contrato a partir del <b class="input_fecha_inicio">{{ date("d", strtotime($contrato->fecha_inicio)) }} de {{ implode($mesInicio) }} del {{ date("Y", strtotime($contrato->fecha_inicio)) }}</b> hasta <b class="input_fecha_fin">{{ date("d", strtotime($contrato->fecha_fin)) }} de {{ implode($mesFin) }} del {{ date("Y", strtotime($contrato->fecha_inicio)) }}</b></p>

	<p><b>6. HONORARIOS, OTROS COSTOS Y FORMAS DE PAGO</b></p>

	<p class="sub_seccion">6.1 Los honorarios convenidos corresponden a 
		{{$user->oficina->pais->moneda_simbolo}}  {{$contrato->monto_total}} ({{$contrato->monto_total_l}}), pagaderos de la siguiente forma: </p>
							
	<p class="sub_seccion">
		@foreach($contrato->pagos as $key =>  $pago)
		<b>PAGO {{$key+1}} :</b> {{$user->oficina->pais->moneda_simbolo}}{{$pago->monto}} ({{$pago->monto_l}}) en concepto de {{$pago->monto_producto}}
		<br><br>
		@endforeach
							
	</p>
							

	<p class="sub_seccion"><b>6.2 Productos de la consultoría</b></p>

	  	<p class="sub_seccion">{!! $contrato->productos!!}</p>

	<p class="sub_seccion"><b>{{$user->consultor}}</b>  facturará a nombre de: @if($contrato->user_id != null) <b>{{$user->oficina->oficina}}  {{$user->oficina->nit}}</b> @elseif($contrato->proveedor_id != null) <b>{{$contrato->proveedor->razon_social}} NIT {{$contrato->proveedor->nit}}</b> @else <b>{{$user->oficina->oficina}} {{$user->oficina->nit}}</b> @endif. En ningún caso We Effect asumirá costos adicionales a los establecidos en este Contrato.</p>


	<p><b>7. RELACION CONTRACTUAL TEMPORAL</b></p>
	<p class="sub_seccion">
		7.1 El presente Contrato no implica ninguna relación obrero patronal entre We Effect y <b>{{$user->consultor}}</b>  , limitándose al período de actividades descritas. Las coberturas de cargas sociales y demás serán realizadas por   <b>{{$user->consultor}}</b>
	</p>
	<p class="sub_seccion"> 
		7.2 En virtud de que las causas que han dado origen a este Contrato de Prestación de Servicios son extraordinarias y transitorias, ambas partes convienen en que al término del plazo estipulado, este Contrato quedará terminado automáticamente, sin necesidad de previo aviso ni ningún otro requisito y que debido a su naturaleza, no implica ningún tipo de relación laboral con <b>{{$user->consultor}}</b>  , y por ende, exime a We Effect de cualquier responsabilidad derivada de las disposiciones legales y demás ordenamientos en materia de trabajo y de seguridad social. 
	</p>
	<p class="sub_seccion">

		7.3 <b>{{$user->consultor}}</b>  se compromete a seguir la política anti-corrupción que destaca la prohibición explícitamente a su personal y a consultores/as financiados bajo este contrato que, para sí mismos o para otros, acepte o le sea prometido, pida o dé, prometa u ofrezca soborno o recompensa, remuneración, compensación, ventajas indebidas o beneficios, de cualquier tipo, que puedan constituir un comportamiento ilegal o inapropiado 
	</p>
	<p>
		<b>8. SUSPENSION, PRORROGA Y TERMINACION DEL CONTRATO</b>
	</p>
	<p class="sub_seccion">

		8.1 We Effect tiene la facultad de suspender temporal o definitivamente los trabajos objeto de este Contrato por causas de fuerza mayor o circunstancias imprevistas, en cualquier estado en que éstos se encuentren, dando aviso por escrito a <b>{{$user->consultor}}</b>  con una anticipación de ocho días. Cuando la suspensión sea temporal, We Effect informará a <b>{{$user->consultor}}</b>   sobre su duración aproximada y se modificará el plazo estipulado en la misma proporción. Cuando la suspensión sea total y definitiva, se dará por terminado el Contrato. Cuando We Effect ordene la suspensión definitiva y total por causa no imputable a <b>{{$user->consultor}}</b>  , pagará a ésta por los servicios prestados hasta la fecha de suspensión. 
	</p>
	<p class="sub_seccion">
		8.2 Cuando no fuere posible a las partes cumplir las obligaciones contraídas en virtud de este Contrato debido a causas de fuerza mayor debidamente comprobadas, éste se podrá dar por terminado por cualquiera de ellas previa notificación inmediata y por escrita a la otra. <b>{{$user->consultor}}</b>  deberá comprometerse a entregar a We Effect todo el trabajo avanzado y percibirá únicamente la suma que corresponde al resultado, obra, tarea o servicio realizado. 
	</p>
	<p class="sub_seccion">

		8.3 Confidencialidad: <b>{{$user->consultor}} </b> , se compromete a cumplir en todo momento con la confidencialidad de los documentos, archivos y políticas, incluyendo todos los datos electrónicos de We Effect / SCC, los cuales no pueden ser divulgados a ninguna persona o personas ajenas a la organización. En caso de incumplimiento con la presente cláusula, se da por terminado inmediatamente dicho contrato. Todo producto del trabajo del encargado de <b>{{$user->consultor}}</b>  en/con We Effect /SCC es propiedad institucional; y We Effect/ SCC se reserva el derecho de uso del mismo. 
	</p>
	<p class="sub_seccion">

		8.4 La ejecución tardía del contrato acarreará una multa para <b>{{$user->consultor}}</b>  . We Effect tendrán el derecho a pronunciarse en forma escrita ante {{$user->consultor}} acerca del incumplimiento de la fecha de entrega del servicio/producto, de la tardía o pérdida de tiempo trascurrido. La sanción económica imputable al contratado será de un 1% del monto total del contrato por cada día hábil de acuerdo al plazo contractual. Dicha multa se hará efectiva del importe del saldo del pago pendiente. Esta cláusula sancionatoria no podrá exceder el 25% del monto total del contrato. Así mismo, superado éste monto, We Effect podrá resolver el contrato y exigir además el cumplimiento de la obligación contraída en los términos pactados, según corresponda. Si la demora se produjere por causas no imputables a <b>{{$user->consultor}}</b>  deberá hacerlo por escrito a We Effect justificando las causas del atraso. Una vez que We Effect analice la situación, y si ésta corresponda, We Effect autorizará la prórroga del plazo de entrega final. 
	</p>
	<p>

		8.5 We Effect podrá rescindir administrativamente el presente Contrato en los casos siguientes: <br>
		<ol>
			<li type="a">
				Cuando <b>{{$user->consultor}} </b> no inicie los trabajos de este Contrato en la fecha que le indique We Effect.
			</li>
			<li type="a">
				Cuando <b>{{$user->consultor}}</b>  no cumpla con cualquiera de las obligaciones derivadas del presente Contrato, el programa de trabajo o los términos de referencia, o sin motivo justificado no acata las instrucciones dadas por escrito por We Effect. 
			</li>							
		</ol>
	</p>

	<p style="text-transform: uppercase;margin-top: 40px;">								
		LEIDO ESTE DOCUMENTO LAS PARTES MANIFIESTAN SU CONFORMIDAD CON TODAS Y CADA UNA DE LAS CLAUSULAS Y PARA CONSTANCIA FIRMAN EN {{$contrato->oficina->pais->pais}}, <b>EL DIA {{$user->fecha_string}}.</b>
	</p>              
	<table style="width: 100%;">
		<tr>
			<td><b>p/ WE EFFECT</b></td>
			<td><b>p/ NOMBRE @if($contrato->user_id != null) {{$user->sexo ? 'CONSULTORA' : 'CONSULTOR'}} @else PROVEEDOR @endif</b></td>
		</tr>

		<tr>
			@if ($contrato->direct_id != null)
				<td>{{$contrato->direct->first_name}} {{$contrato->direct->last_name}}</td>
			@elseif ($contrato->representante_id != null)
				<td>{{$contrato->representante->first_name}} {{$contrato->representante->last_name}}</td>
			@else
				<td>{{$directora->first_name}} {{$directora->last_name}}</td>
			@endif
			@if($contrato->user_id != null)
				<td>{{$user->first_name}} {{$user->last_name}}</td>
			@elseif($contrato->proveedor_id != null)
				<td>{{$contrato->proveedor->razon_social}}</td>
			@else
				<td>{{$contrato->proveedorUser->first_name}} {{$contrato->proveedorUser->last_name}}</td>
			@endif						
		</tr>

		<tr>
			@if ($contrato->direct_id != null)
				<td>{{$contrato->direct->cargo->cargo}}</td>
			@elseif ($contrato->representante_id != null)
				<td>{{$contrato->representante->cargo->cargo}}</td>
			@else
			<td>{{$directora->cargo->cargo}}</td>
			@endif
			@if($contrato->user_id != null)
				<td>{{$user->tipo_documento->tipo_documento}}  {{$user->documento}} </td>
			@elseif($contrato->proveedor_id != null)
				<td>NIT {{$contrato->proveedor->nit}}</td>
			@else
				<td>{{$contrato->proveedorUser->tipo_documento->tipo_documento}} {{$contrato->proveedorUser->documento}}</td>
			@endif
		</tr>
		<tr>
			@if ($contrato->direct_id != null)
				<td><img src="upload/users/{{$contrato->direct->firma}}"></td>
			@elseif ($contrato->representante_id != null)
				<td><img src="upload/users/{{$contrato->representante->firma}}"></td>
			@else
				<td>{!!$directora->firma_directora !!}</td>
			@endif
		</tr>
	</table>
           
</div>
</body>

<style type="text/css">
	* {
		font-family: 'Georgia';
		font-size: 16px;
	}
	.sub_seccion{
		margin-left: 30px;
		text-align: justify;
	}
	body{
		padding: 50px 40px 73px 40px;
	}
	#header{
		position: fixed;
    	width: 100%;
    	text-align:right;
	}
	#header span{
		color: #888;
	}
	

</style>

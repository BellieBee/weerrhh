<body>
	<div align="right">{{$user->fecha_ingreso}}</div>
	<div style="text-align: center;margin: 30px; text-transform: uppercase;">
		<b>FINIQUITO LABORAL</b>
	</div>
	
	<div><b>1.	Hago constar expresamente que:</b></div><br>
	<table style="margin-left: 30px;">
		<tr>
			<td style="vertical-align: top;">1.1</td>
			<td style="padding-bottom: 20px;">
				Sostuve relación de servicios con la entidad <b>WE EFFECT</b> a partir del 
				{{$user->fecha_ingreso}}, 
				por virtud de la cual presté a aquella los servicios: (i) del 
				<b>{{$user->fecha_ingreso}}</b>,  al <b>{{ $user->fecha_finalizacion}}</b>
				 como <b>REPRESENTANTE PAÍS</b>, el cual me era pagado mensualmente mediante un recibo emitido a su nombre.
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">1.2</td>
			<td style="padding-bottom: 20px;">
				Dicha relación de servicios terminará el <b>{{ $user->fecha_finalizacion}}</b> tal y como fue pactado por ambas partes en la cláusula cuarta del Contrato Individual de Trabajo por Tiempo Definido No. CD 009-2017 en adelante el “Contrato”.
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">1.3</td>
			<td style="padding-bottom: 20px;">
				Que <b>WE EFFECT</b> me hará efectivo mediante cheque el día 
				<b>{{$user->fecha_finalizacion}}</b> el pago de <b>{{$datos->moneda_simbolo}}{{number_format($datos->pago_cheque,2)}}</b>,
				correspondiente a los siguientes rubros:
			</td>
		</tr>
	</table>

	<table class="pago" style="page-break-after:always;">
		<tr>
			<td style="width: 80%">Pago de salario correspondiente al mes de {{$datos->planilla_mes}}</td>
			<td>{{$datos->moneda_simbolo}}{{number_format($datos->liquido,2)}}</td>
		</tr>
		<tr>
			<td>Pago correspondiente al bono 13avo (del {{$user->fecha_ingreso}} al {{$user->fecha_finalizacion}})</td>
			<td>{{$datos->moneda_simbolo}}{{number_format($user->acumulado->sum('catorceavo'),2)}}</td>
		</tr>
		@if($datos->pago_pension=='retiro')
		<tr>
			<td>Pago de pensión beneficio que aplica en su política de personal de We Effect por un porcentaje de {{$user->porcentaje_pension}}% mensual sobre el salario (del {{$user->fecha_ingreso}} al {{$user->fecha_finalizacion}})</td>
			<td>{{$datos->moneda_simbolo}}{{number_format($user->acumulado->sum('pension'),2)}}</td>
		</tr>
		@endif

		@foreach($datos->extra_label as $key => $label)

		 @if($label!="")		 
		 	<tr>
		 		<td>{{$label}}</td>
		 		<td>{{$datos->moneda_simbolo}}{{number_format($datos->extra_valor[$key],2)}}</td>
		 	</tr>
		 @endif


		@endforeach
		<tr>
			<td colspan="2"> <br></td>
			
		</tr>
		<tr>
			<td align="right"><b>SUB-TOTAL</b></td>
			<td><b>{{$datos->moneda_simbolo}}{{number_format($datos->sub_total,2)}}</b></td>
		</tr>
		<tr>
			<td style="border:none"> <br></td>
			<td style="border:none"> <br></td>
		</tr>
		<tr>
			<td colspan="2"><b>(-) DEDUCCIONES</b></td>			
		</tr>
		<tr>
			<td>Cargas impositivas: Deduccion total</td>
			<td>{{$datos->moneda_simbolo}}{{number_format($datos->total_deducciones,2)}}</td>
		</tr>
		<tr>
			<td colspan="2"> <br></td>
		</tr>
		<tr>
			<td align="right"><b>SUB-TOTAL</b></td>
			<td><b>{{$datos->moneda_simbolo}}{{number_format($datos->total_deducciones,2)}}</b></td>
		</tr>
		<tr>
			<td style="border:none"> <br></td>
			<td style="border:none"> <br></td>
		</tr>
		<tr>
			<td><b>TOTAL A ENTREGAR EN ESTA LIQUIDACION</b></td>
			<td><b>{{$datos->moneda_simbolo}}{{number_format($datos->pago_liquidacion,2)}}</b></td>
		</tr>
		<tr>
			<td style="border:none"> <br></td>
			<td style="border:none"> <br></td>
		</tr>
		<tr>
			<td colspan="2" style="border:none"> 
				Una vez enterado el monto antes relacionado, quedará por bien saldado y por recibido a mi entera satisfacción el pago que me corresponde por la terminación del Contrato.
			</td>
			
		</tr>
	</table>	

	<div ><b>2.	Hago constar expresamente que:</b></div><br>
	<table style="margin-left: 30px;page-break-after:always;">
		<tr>
			<td style="vertical-align: top;">2.1</td>
			<td style="padding-bottom: 20px;">
				<b>WE EFFECT</b> no me adeuda suma alguna en concepto de honorarios, gastos, indemnización por daños y perjuicios, reembolsos por gastos incurridos y autorizados por la entidad, pago correspondiente al reembolso por montos de jubilación según el Contrato o cualquier otra obligación ni prestación que se derive directa o indirectamente del contrato antes indicado, pues todas las obligaciones a que fui acreedor me fueron pagadas.
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">2.2</td>
			<td style="padding-bottom: 20px;">
				<b>WE EFFECT</b> no me tiene ninguna obligación pendiente ni de carácter civil, ni mercantil, ni de ninguna otra índole, ni en concepto de indemnización por tiempo servido, compensación o cualquier otra prestación o definición que por su fondo sea equivalente, similar o análoga, ni por salarios ordinarios y/o extraordinarios, ni por aguinaldo, bonificaciones anuales, vacaciones, ventajas, ni por séptimos días, ni por incentivos o bonificaciones incentivos, ni por cualquier otro concepto, ni por reajustes por ningún concepto, ya que no se derivaron del contrato de servicios relacionado en el numeral anterior, obligaciones laborales de ningún tipo.
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">2.3</td>
			<td style="padding-bottom: 20px;">
				El precio pagado mensualmente por <b>WE EFFECT</b> en concepto de salario, retribuyeron los servicios prestados a dicha entidad.
			</td>
		</tr>
	</table>

	<div><b>3.	Por el presente acto me obligo a:</b></div><br>
	<table style="margin-left: 30px;">
		<tr>
			<td style="vertical-align: top;">3.1</td>
			<td style="padding-bottom: 20px;">
				<p>Mantener en estricta reserva y confidencialidad toda la información que me fue proporcionada o que tuve conocimiento por cualquier medio a través de la prestación de mis servicios profesionales, comprometiéndome a no darla a conocer, ni total ni parcialmente a ninguna persona, ni a utilizarla para beneficio personal o de intereses de terceros.</p>
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">3.2</td>
			<td style="padding-bottom: 20px;">
				<p>No divulgar a terceros, directa o indirectamente, el contenido del presente documento, ni ninguno de los términos y condiciones establecidos en el mismo, así como lo que fue conversado con los funcionarios de <b>WE EFFECT</b> al momento de suscribir dicho documento.</p>

				<p>Por el presente acto acepto expresamente que el incumplimiento de lo establecido en el numeral 3 del presente documento, podré ser responsable civil y penalmente de los daños y perjuicios que pudiera ocasionar.</p>
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">3.3</td>
			<td style="padding-bottom: 20px;">
				<p>Hacer entrega del equipo, información y cualquier otro bien que We Effectle haya proporcionado durante el desarrollo del trabajo.</p>
			</td>
		</tr>
	</table>
	
	<p>Y, en vista de la presente manifestación, apruebo y remato la totalidad de mis cuentas y otorgo la más completa y eficaz carta de pago y el finiquito más absoluto, obligándome por pacto expreso de no pedir, toda vez que no tengo reclamo de ningún género que hacer a <b>WE EFFECT</b>.</p><br>

	<b >{{$user->oficina->pais->pais}}, {{$user->fecha_finalizacion}}</b>.<br><br><br><br><br>

	<center>
		_____________________________________<br>
		{{$user->first_name}} {{$user->last_name}} <br>
		Documento de Identidad: {{$user->documento}}
	</center>
	
</body>

<style type="text/css">
	body{
		font-family: 'Helvetica';
	}
	ol{
	 	counter-reset: item;	  
	}
	li{
	 display: block
	 ;
	 margin-bottom: 10px;
	 margin-top: 10px;
	}
	li:before {
	 content: counters(item, ".") " "; 
	 counter-increment: item 
	}
	table{
		border-collapse: collapse;
		margin-bottom: 20px;
	}
	.pago td{
		border :1px solid black;
		padding: 4px;
	}
</style>

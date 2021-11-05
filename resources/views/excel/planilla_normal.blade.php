<table border="1" >
	
	<tr><td></td></tr>
	<tr><td></td></tr>
	<tr><td></td></tr>
	<tr>
		<td colspan="3">NIT: {{$oficina->nit}}</td>
	</tr>

	<tr>
		<td colspan="3">Nº PATRONAL: {{$oficina->num_patronal}}</td>
	</tr>
	<tr>
		<td colspan="3">{{$oficina->direccion}}</td>
	</tr>
	<tr>
		<td colspan="3">Telf:{{$oficina->telefono}}</td>
	</tr>
	<tr>
		<td colspan="3">{{$pais->pais}}</td>
	</tr>
</table>
<table>
	<tr>
		<td colspan="{{6+$planilla->cell_deducciones+$planilla->cell_aportes+1}}" height="30" align="center" style="text-transform: uppercase;">
			<b ">PLANILLA DE SUELDOS CORRESPONDIENTE AL MES DE {{strtoupper($planilla->m_a)}}</b>
		</td>
	</tr>
	<tr>
		<td colspan="13"  height="30" align="center">
			<b>(Expresado en {{$pais->moneda_nombre}})  </b>
		</td>
	</tr>
	@yield('tabla')

	
		 		

<style type="text/css">
	tr,td{
		font-size: 9px;
		font-family: Arial;
	}
	.titulo_tr td,.titulo_td{
		background: #e0e0e0 ;
		border: 1px solid #000;
		text-align: center;
		vertical-align: center;
		font-weight: bold;
		text-transform: uppercase;	
	}	
	.borde{
		border: 1px solid #000;
	}
	.din td{
		border: 1px solid #000;
		wrap-text: true;
		text-align: center;
		vertical-align: center;		
	}
	.bg_gris{
		background: #ccc;
		border: 1px solid #000;
	}	

	td{
		wrap-text: true;
	}
	.confirmaciones td{
		height: 20px;
	}
</style>


</table>



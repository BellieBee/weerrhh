<table border="1" >
	
	<tr><td></td></tr>
	<tr><td></td></tr>
	<tr><td></td></tr>
	<tr>
		<td colspan="6">NIT: {{$oficina->nit}}</td>
	</tr>

	<tr>
		<td colspan="6">Nº PATRONAL: {{$oficina->num_patronal}}</td>
	</tr>
	<tr>
		<td colspan="6">{{$oficina->direccion}}</td>
	</tr>
	<tr>
		<td colspan="6">Telf:{{$oficina->telefono}}</td>
	</tr>
	<tr>
		<td colspan="6">{{$pais->pais}}</td>
	</tr>
</table>
<table>
	<tr>
		<td colspan="{{6+$planilla->cell_deducciones+$planilla->cell_aportes+1}}" height="30" align="center" style="text-transform: uppercase;">
			<b>PLANILLA DE SUELDOS DE {{strtoupper($pais->pais)}}  CORRESPONDIENTE AL MES DE {{ strtoupper($planilla->m_a) }}</b>
		</td>
	</tr>

</table>
	@include('excel.seguro_social.totales')

	@if($pais->id == 7)
		@include('excel.seguro_social.apropiaciones')
	@else
		@include('excel.seguro_social.patronales')
	@endif

	@if($pais->id == 2)
		@include('excel.seguro_social.acumulados')
	@endif

	@if(str_contains($planilla->m_a,'Diciembre'))
		@include('excel.seguro_social.aguinaldo')
		
		@if($pais->pago_indemnizacion=="anual")

			@include('excel.seguro_social.indemnizacion')
			
		@endif

		@if($pais->pago_pension=="anual")

			@include('excel.seguro_social.pension')
			
		@endif
	@endif

<style type="text/css">
	tr,td{
		font-size: 9;
		font-family: Arial;
	}
	td{
		wrap-text: true;
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
</style>
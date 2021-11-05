<body>


@if($pais->id == 7)
	@include('pdf.pdf_colombia')
@else
	@include('pdf.pdf_totales')
@endif

@include('pdf.pdf_patronales')

@if($pais->id == 2 || $pais->id == 7)
	@include('pdf.pdf_acumulados')
@endif

@if(str_contains($planilla->m_a,$mes_14))
	@include('pdf.pdf_bono_catorceavo')
@endif

@if(str_contains($planilla->m_a,'Diciembre'))
	@include('pdf.pdf_aguinaldo')
	
	{{--@if($pais->pago_indemnizacion=="anual")

		@include('pdf.pdf_indemnizacion')
		
	@endif --}}

	@if($pais->pago_pension=="anual")

		@include('pdf.pdf_pension')
		
	@endif
@endif



@include('pdf.pdf_firmas')



</body>
<style type="text/css">	
	body{
		font-family: 'Helvetica';
	}
	table{
		border-collapse: collapse;
	}
	.titulo_td{
		border: 2px solid #000;
	}
	.titulo_tr td,.titulo_td{

		font-size: 9px;		
		background: #e0e0e0 ;
		text-align: center;
		font-weight: bold;
		text-transform: uppercase;	
		width: 40px;
	}
	.datos_em{
		font-size: 10px;
	}
	.empleados td,.titulo_tr td,.titulo_td{
		border: 1px solid #000;
		padding-bottom: 5px;
		padding-top: 5px;
		padding-left: 3px;
		padding-right: 3px;
	}
	.totales{
		font-weight: bold;
		font-size: 11px;
	}
	.firmas{
		width: 32%;
		display: inline-block;
		text-align:center;
		
	}
	.firmas label{
		font-weight: bold;
		display: inline-block;
		margin-left: 27%;
		width: 150px;
		height: 20px;
		padding: 3px;
		
		
	}

	/*
</style>
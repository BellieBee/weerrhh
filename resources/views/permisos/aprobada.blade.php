<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Permiso Aprobado</title>
		<!--<link href="css/styles.css" media="all" rel="stylesheet" type="text/css" /> -->
		{!! HTML::style('css/styles.css') !!}

	</head>

	<body itemscope itemtype="http://schema.org/EmailMessage">

		<table class="body-wrap">
			<tr>
				<td></td>
				<td class="container" width="600">
					<div class="content">
						<table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction">
							<tr>
								<td class="content-wrap">
									<meta itemprop="name" content="Confirm Email"/>
									<table width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td class="content-block">
												
											</td>
										</tr>
										<tr>
											<td class="content-block">
												<ul>
													<li><label><b>Nombre y Apellido:</b></label> {{ $nombre['first'] }} {{ $nombre['last'] }}</li>
													<li><label><b>N° Contrato:</b></label> {{ $contrato }}</li>
													<li><label><b>Cargo:</b></label> {{ $cargo }}</li>
													<li><label><b>Oficina:</b></label> {{ $oficina }}</li>
													<li><label><b>Tipo de Permiso:</b></label> {{ $tipo_permiso }}</li>
													<li><label><b>Motivo:</b></label> {{ $motivo }}</li>
													<li><label><b>Fecha Inicio de Permiso:</b></label> {{ $fecha_inicio }}</li>
													<li><label><b>Fecha de Fin de Permiso:</b></label> {{ $fecha_fin }}</li>
													<li><label><b>Tiempo de Permiso:</b></label> {{ $num_dh }} {{ $dh }}</li>
												</ul>
											</td>
										</tr>
										<tr>
											<td class="content-block">
												&mdash; WeEffect.
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<div class="footer">
							<table width="100%">
								<tr>
									<td class="aligncenter content-block">Follow <a href="http://twitter.com/mail_gun">@Mail_Gun</a> on Twitter.</td>
								</tr>
							</table>
						</div></div>
				</td>
				<td></td>
			</tr>
		</table>

	</body>
</html>

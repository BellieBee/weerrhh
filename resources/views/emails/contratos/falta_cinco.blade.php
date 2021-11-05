<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Faltan cinco días para vencer el contrato</title>
    <link href="email_templates/styles.css" media="all" rel="stylesheet" type="text/css" />
</head>

<body>

<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="alert alert-good">
                            Faltan 5 días para que expire el contrato {{$contrato->n_contrato}}
                        </td>
                    </tr>
                    <tr>
                        <td class="content-wrap">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="content-block">
                                        El contrato a cargo {{$user->sexo?' del consultor':' de la consultora'}} <b>{{$user->first_name}} {{$user->last_name}}</b> expira el <b>{{ date('d-m-Y', strtotime($contrato->fecha_fin)) }}</b> y su estado actual es 
                                        @if($contrato->status == 0)
                                            <label><b>Pendiente</b></label>
                                        @elseif($contrato->status == 1)
                                            <label><b>Aprobado</b></label>
                                        @elseif($contrato->status == 2)
                                            <label><b>Rechazado</b></label>
                                        @elseif($contrato->status == 3)
                                            <label><b>Terminado</b></label>
                                        @elseif($contrato->status == 4)
                                            <label><b>Anulado</b></label>
                                        @elseif($contrato->status == 5)
                                            <label><b>Vencido</b></label>
                                        @endif
                                        .

                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <ul>
                                            <li><label><b>Consultoria:</b></label> {{ $contrato->consultoria }}</li>

                                            <li><label><b>Consultor:</b></label> {{$user->first_name}} {{$user->last_name}}</li>

                                            <li><label><b>Fecha de contrato:</b></label> {{$contrato->fecha_contrato}}</li>

                                            <li><label><b>Periodo de Tiempo:</b></label> 
                                                {{$contrato->fecha_inicio}} hasta {{$contrato->fecha_fin}}
                                            </li>

                                            <li><label><b>Tiempo de contrato:</b></label> {{$contrato->tiempo_contrato}} dias</li>

                                            <li><label><b>Fecha de Cumplimiento:</b></label>

                                               {{$contrato->cumplimiento?$contrato->cumplimiento:'Aun si definir'}} 

                                           </li>


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
                            <td class="aligncenter content-block">&mdash; WeEffect.</td>
                        </tr>
                    </table>
                </div></div>
        </td>
        <td></td>
    </tr>
</table>

</body>

<style type="text/css">*{margin:0;font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px}img{max-width:100%}body{-webkit-font-smoothing:antialiased;-webkit-text-size-adjust:none;width:100%!important;height:100%;line-height:1.6em}table td{vertical-align:top}body{background-color:#f6f6f6}.body-wrap{background-color:#f6f6f6;width:100%}.container{display:block!important;max-width:600px!important;margin:0 auto!important;clear:both!important}.content{max-width:600px;margin:0 auto;display:block;padding:20px}.main{background-color:#fff;border:1px solid #e9e9e9;border-radius:3px}.alert{font-size:16px;color:#fff;font-weight:500;padding:20px;text-align:center;border-radius:3px 3px 0 0}.alert a{color:#fff;text-decoration:none;font-weight:500;font-size:16px}.alert.alert-warning{background-color:#ff9f00}.alert.alert-bad{background-color:#d0021b}.alert.alert-good{background-color:#68b90f}.content-wrap{padding:20px}.content-block{padding:0 0 20px}.btn-primary{text-decoration:none;color:#FFF;background-color:#348eda;border:solid #348eda;border-width:10px 20px;line-height:2em;font-weight:bold;text-align:center;cursor:pointer;display:inline-block;border-radius:5px;text-transform:capitalize}.footer{width:100%;clear:both;color:#999;padding:20px}.footer p,.footer a,.footer td{color:#999;font-size:12px}h1,h2,h3{font-family:"Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;color:#000;margin:40px 0 0;line-height:1.2em;font-weight:400}.aligncenter{text-align:center}</style>
</html>

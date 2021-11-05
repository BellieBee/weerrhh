@include('user.pdf_encabezado')

<style type="text/css">
    * {
        font-family: 'Georgia';
        font-size: 16px;
    }
    td{
        border :1px solid black;
        padding: 4px;
    }
    .sub_seccion{
        margin-left: 30px;
        text-align: justify;
    }
    .block{
        display: inline-block;
        margin-right: 25px;
    }
    .grandblock{
        display: block;
    }
</style>

<table>
    <tr>
        <td><b>Nombre:</b></td>
        <td>{{$user->first_name}} {{$user->last_name}}</td>
        <td><b>No contrato:</b></td>
        <td>#{{$user->n_contrato}}</td>
        <td><b>Edad:</b></td>
        <td>{{Carbon\Carbon::parse($user->birthday)->age}}</td>
    </tr>
    <tr>
        <td><b>Tipo de Empleado:</b></td>
        <td>{{$user->categoria->categoria}}</td>
        <td><b>Rol:</b></td>
        <td>{{$user->roles()->first()->name}}</td>
        <td><b>Correo Electrónico:</b></td>
        <td>{{$user->email}}</td>
    </tr>
    <tr>
        <td><b>Cargo:</b></td>
        <td>{{$user->cargo->cargo}}</td>
        <td><b>Profesión:</b></td>
        <td>{{$user->profesion->profesion}}</td>
        <td><b>Tipo de Sangre:</b></td>
        <td>{{$user->tipo_sangre}}</td>
    </tr>
    <tr>
        <td><b>Tipo de Documento:</b></td>
        <td>{{$user->tipo_documento->tipo_documento}}</td>
        <td><b>Documento:</b></td>
        <td>{{$user->documento}}</td>
        <td><b>No. Afiliación:</b></td>
        <td>{{$user->n_afiliacion}}</td>
    </tr>
    <tr>
        <td><b>Identificación Tributaria:</b></td>
        <td>{{$user->n_identificacion_tributaria}}</td>
        <td><b>Régimen Tributario:</b></td>
        <td>{{$user->regimen_tributario}}</td>
        <td><b>Fecha de inicio:</b></td>
        <td>{{$user->fecha_inicio}}</td>
    </tr>
    <tr>
        <td><b>Fecha de Finalización:</b></td>
        <td>{{$user->fecha_finalizacion}}</td>
        <td><b>Teléfono:</b></td>
        <td>{{$user->phone}}</td>
        <td><b>Skype:</b></td>
        <td>{{$user->skype}}</td>
    </tr>
    <tr>
        <td><b>Celular:</b></td>
        <td>{{$user->cellphone}}</td>
        <td><b>Fecha de Nacimiento:</b></td>
        <td>{{Carbon\Carbon::parse($user->birthday)->format('d-m-Y')}}</td>
        <td><b>Contacto de emergencia:</b></td>
        <td>{{$user->contacto_emergencia}}</td>
    </tr>
    <tr>
        <td><b>Tlf Contacto Emergencia:</b></td>
        <td>{{$user->tlf_contacto_emergencia}}</td>
        <td><b>Salario Base:</b></td>
        <td>{{$user->oficina->pais->moneda_simbolo}} {{$user->salario_base}}</td>
        <td></td>
        <td></td>
    </tr>
</table>


<br>          

<h4>Firma Digital</h4>
<div style="margin-top: 10px;">
    <div class="firmas" >

        <img src="{{url('/upload/users/'.$user->firma)}}" style="width:200px; height: 100px"><br>

        ________________________<br>
        <b>{{$user->first_name}} {{$user->last_name}}</b><br>
        {{$user->cargo->cargo}}<br>
    </div>
</div>     
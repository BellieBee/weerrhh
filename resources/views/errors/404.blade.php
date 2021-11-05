<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ url('/favico.ico') }}">
    <title>Page Not Found</title>

    {!! HTML::style('assets/css/bootstrap.min.css') !!}

    <style>
        body { padding-top: 100px; }
        h1 { font-size: 62px; }

        @media (max-width: 768px) {
            body { padding-top: 50px; }
            h1 { font-size: 50px; }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <div class="text-center">
                    <img src="{{ url('assets/img/vanguard-logo-no-text.png') }}" alt="Vanguard" class="logo">
                <h1>Oops, 404!</h1>
                <br />
                <p>
                    No se encontró la página que solicitó, comuníquese con su webmaster
                    o intenta de nuevo. Use el botón <strong> Atrás </strong> de su navegador para navegar a la
                    página donde estaba previamente
                </p>

                @if (Auth::check())
                    <p><strong>O simplemente puedes presionar este pequeño botón</strong></p>
                    <a href="{{  route('dashboard') }}" class="btn btn-large btn-info">
                        <i class="glyphicon glyphicon-home"></i> Go To Dashboard
                    </a>
                @endif
            </div>
        </div>
    </div>

</div>

</body>
</html>
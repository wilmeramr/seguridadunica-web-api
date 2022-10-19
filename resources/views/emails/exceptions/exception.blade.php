<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <title>@yield('title') | {{ config('app.name') }}</title>

        <!-- General CSS Files -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">

        <!-- Template CSS -->
        <link rel="stylesheet" href="{{ asset('web/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('web/css/components.css')}}">
        <link rel="stylesheet" href="{{ asset('assets/css/iziToast.min.css') }}">
        <link href="{{ asset('assets/css/sweetalert.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    </head>
<body>

    Ocurrio una Exception en {{$Ubicacion}} : {{$Exception}}


</body>
</html>

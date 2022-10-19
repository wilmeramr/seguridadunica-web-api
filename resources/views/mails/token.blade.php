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

    Su Nuevo Token es: <h1>{{$token}}</h1>
    <div class="container text-center" style="margin-top: 50px;">
        <h3 class="mb-5">Barcode Laravel</h3>
        <div>{!! DNS1D::getBarcodeHTML('4445645656', 'C39') !!}</div></br>
        <div>{!! DNS1D::getBarcodeHTML('4445645656', 'POSTNET') !!}</div></br>
        <div>{!! DNS1D::getBarcodeHTML('4445645656', 'PHARMA') !!}</div></br>
        <div>{!! DNS2D::getBarcodeHTML('4445645656', 'QRCODE') !!}</div></br>
        <img src="http://localhost:8080/img/barcode/2d5cdbe9-3b4b-4a45-87cd-6d7cbbb3ca69.png" alt="barcode"   />
    </div>

</body>
</html>

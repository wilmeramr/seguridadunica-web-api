<!DOCTYPE html>
<html lang="en">
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
<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="login-brand">
                        <img src="{{ asset('storage/img/generales/LogoSeguridadUnica.png') }}" alt="logo" width="100"
                             class="shadow-light">
                             <h2 class="card card-primary"  >{{$coname->co_name}}</h2>
                    </div>
                                    <div class="card card-primary">
                                        <div class="card-header"><h4>Registro</h4></div>

                                        <div class="card-body pt-1">
                                            <form method="POST" action="{{ route('guest_store') }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input id="firstName" type="text"
                                                            style="display: none;"
                                                            class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                            name="code"
                                                            tabindex="1" placeholder="Ingrese Nombre Completo" value="{{$code}}"
                                                            >
                                                            <label for="first_name">Nombre completo:</label><span
                                                                    class="text-danger">*</span>
                                                            <input id="firstName" type="text"
                                                                   class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                                   name="name"
                                                                   tabindex="1" placeholder="Ingrese Nombre Completo" value="{{ old('name') }}"
                                                                   autofocus required>
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('name') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email">Email:</label><span
                                                                    class="text-danger">*</span>
                                                            <input id="email" type="email"
                                                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                                   placeholder="Ingrese su email" name="email" tabindex="1"
                                                                   value="{{ old('email') }}"
                                                                   required autofocus>
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('email') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="dni">Numero de Documento:</label><span
                                                                    class="text-danger">*</span>
                                                            <input id="dni" type="text"
                                                                   class="form-control{{ $errors->has('dni') ? ' is-invalid' : '' }}"
                                                                   placeholder="Ingreso Numero de Documento" name="dni" tabindex="1"
                                                                   value="{{ old('dni') }}"
                                                                   required autofocus>
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('dni') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="tel" class="control-label">Telefono
                                                                :</label><span
                                                                    class="text-danger">*</span>
                                                            <input id="tel" type="number"
                                                                   class="form-control{{ $errors->has('tel') ? ' is-invalid': '' }}"
                                                                   placeholder="Ingrese su número teléfonico" name="tel" tabindex="2" required>
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('tel') }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 mt-4">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                                                Registrar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>


                    <div class="simple-footer">
{{--                        Copyright &copy; {{ getSettingValue('application_name') }}  {{ date('Y') }}--}}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- General JS Scripts -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>

<!-- JS Libraies -->

<!-- Template JS File -->
<script src="{{ asset('web/js/stisla.js') }}"></script>
<script src="{{ asset('web/js/scripts.js') }}"></script>
<!-- Page Specific JS File -->
</body>
</html>

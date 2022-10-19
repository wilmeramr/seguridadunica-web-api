@extends('layouts.auth_app')
@section('title')
   Acceso
@endsection
@section('content')
<div class="container-fluid px-1 px-md-5 px-lg-1 px-xl-5 py-5 mx-auto">
    <div class="card card0 border-0">
        <div class="row d-flex">

            <div class="col-lg-6">

                <div class="card1 pb-5">

                    <div class="row">
                        <div id='notification' style="color: #1A237E" class="alert mx-3  ">

                        </div>
                        <img src="{{ asset('img/segunica.jpg') }}" class="logo">
                    </div>
                    <div class="row px-3 justify-content-center mt-4 mb-5 border-line">
                        <img src="{{ asset('img/acceso.jpeg') }}" height="100" class="image">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card2 card border-0 px-4 py-5">

                    <div class="row px-3 mb-4">
                        <div class="line"></div>
                        <small class="or text-center">Login</small>
                        <div class="line"></div>
                    </div>
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger p-0">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    <div class="row px-3">
                        <label class="mb-1"><h6 class="mb-0 text-sm">Correo electrónico</h6></label>
                        <input class="mb-4 form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                         type="text" id="email" name="email"
                         value="{{ (Cookie::get('email') !== null) ? Cookie::get('email') : old('email') }}" autofocus
                           required
                         placeholder="Ingrese el E-mail">

                    </div>
                    <div class="row px-3">
                        <label class="mb-1"><h6 class="mb-0 text-sm">Contraseña</h6></label>
                        <input  class="mb-4 form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="password" name="password"
                        id="password"
                        value="{{ (Cookie::get('password') !== null) ? Cookie::get('password') : null }}"
                        placeholder="Ingrese contraseña."
                        tabindex="2" required>
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    </div>
                    <div class="row px-3 mb-4">

                        <a href="{{ route('password.request') }}" class="ml-auto mb-0 text-sm">Recuperar contraseña</a>
                    </div>
                    <div class="row mb-3 px-3">
                        <button type="submit" class="btn btn-primary text-center">Entrar</button>
                    </div>
                </form>

                </div>
            </div>
        </div>
        <div class="bg-blue py-4">
            <div class="row px-3">
                <small class="ml-4 ml-sm-5 mb-2">Copyright &copy; 2022. All rights reserved.</small>
                <div class="social-contact ml-4 ml-sm-auto">
                  <a href="https://www.facebook.com/seguridadunicasrl"  target="_blank">  <span class="fa fa-facebook mr-4 text-sm"></span></a>
                   <a href="https://www.instagram.com/seguridadunica1"  target="_blank"> <span class="fa fa-instagram mr-4 text-sm"></span></a>
                   <a href="https://wa.me/message/46WZZ5JHS5IFP1"  target="_blank"> <span class="fa fa-whatsapp mr-4 text-sm"></span></a>
                    <span class="fa fa-twitter mr-4 mr-sm-5 text-sm"></span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


<style>
    body {
        color: #000;
        overflow-x: hidden;
        height: 100%;
        background-color: #B0BEC5;
        background-repeat: no-repeat;
    }

    .card0 {
        box-shadow: 0px 4px 8px 0px #757575;
        border-radius: 0px;
    }

    .card2 {
        margin: 0px 40px;
    }

    .logo {
        width: 80;
        height: 80;
        margin-top: 20px;
        margin-left: 35px;
    }

    .image {
        width: 360px;
        height: 160;
    }

    .border-line {
        border-right: 1px solid #EEEEEE;
    }

    .facebook {
        background-color: #3b5998;
        color: #fff;
        font-size: 18px;
        padding-top: 5px;
        border-radius: 50%;
        width: 35px;
        height: 35px;

        cursor: pointer;
    }

    .instagram {
        background-color: #1DA1F2;
        color: #fff;
        font-size: 18px;
        padding-top: 5px;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        cursor: pointer;
    }

    .whatsapp {
        background-color: #2867B2;
        color: #fff;
        font-size: 18px;
        padding-top: 5px;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        cursor: pointer;
    }

    .line {
        height: 1px;
        width: 45%;
        background-color: #E0E0E0;
        margin-top: 10px;
    }

    .or {
        width: 10%;
        font-weight: bold;
    }

    .text-sm {
        font-size: 14px !important;
    }

    ::placeholder {
        color: #BDBDBD;
        opacity: 1;
        font-weight: 300
    }

    :-ms-input-placeholder {
        color: #BDBDBD;
        font-weight: 300
    }

    ::-ms-input-placeholder {
        color: #BDBDBD;
        font-weight: 300
    }

    input, textarea {
        padding: 10px 12px 10px 12px;
        border: 1px solid lightgrey;
        border-radius: 2px;
        margin-bottom: 5px;
        margin-top: 2px;
        width: 100%;
        box-sizing: border-box;
        color: #2C3E50;
        font-size: 14px;
        letter-spacing: 1px;
    }

    input:focus, textarea:focus {
        -moz-box-shadow: none !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        border: 1px solid #304FFE;
        outline-width: 0;
    }

    button:focus {
        -moz-box-shadow: none !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        outline-width: 0;
    }

    a {
        color: inherit;
        cursor: pointer;

    }

    .btn-blue {
        background-color: #1A237E;
        width: 150px;
        color: #fff;
        border-radius: 2px;
    }

    .btn-blue:hover {
        background-color: #000;
        cursor: pointer;
    }

    .bg-blue {
        color: #fff;
        background-color: #1A237E;
    }

    @media screen and (max-width: 991px) {
        .logo {
            margin-left: 0px;
        }

        .image {
            width: 300px;
            height: 120px;
        }

        .border-line {
            border-right: none;
        }

        .card2 {
            border-top: 1px solid #EEEEEE !important;
            margin: 0px 15px;
        }
    }
</style>

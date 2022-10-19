@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => ''])
            <!-- header here -->
           <img src="{{$country->co_logo}}" width="350" height="150">
        @endcomponent
    @endslot

    {{-- Body --}}
    <!-- Body here -->
   # Invitación

Muchas gracias por completar sus datos. Lo esperamos en {{$country->co_name}} el dia {{ date('d-m-y', strtotime($Autorizaciones->aut_desde))}}.

En caso de ser visita presentar el siguiente codigo:
<img src="{{$Autorizaciones->aut_barcode}}" width="250" height="150">

Nombre y Apellido: {{$Autorizaciones->aut_nombre}}.
<br>
Documento: {{$Autorizaciones->aut_documento}}.

@component('mail::button', [ 'url' => $country->co_como_llegar ])
    Como llegar
@endcomponent

Gracias, y quedamos a su espera !
    {{-- Subcopy --}}
    @slot('subcopy')
        @component('mail::subcopy')
            <!-- subcopy here -->
        @endcomponent
    @endslot


    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')

        @endcomponent
    @endslot
@endcomponent

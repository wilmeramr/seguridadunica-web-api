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
   # Haz generado un token para el ingreso a la APP de servicios.

Su nuevo token es:

@component('mail::panel')
{{$token}}
@endcomponent



Gracias, por su confianza!
    {{-- Subcopy --}}
    @slot('subcopy')
        @component('mail::subcopy')
            <!-- subcopy here -->
        @endcomponent
    @endslot


    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
        Si usted no reconoce esta acción, por favor comunicarse con la Administración.
        @endcomponent
    @endslot
@endcomponent

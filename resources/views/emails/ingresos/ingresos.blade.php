@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => ''])
            <!-- header here -->
           <img src="{{$ingr->co_logo}}" width="350" height="150">
        @endcomponent
    @endslot

    {{-- Body --}}
    <!-- Body here -->
   # NOTIFICIÓN DE {{$type==1? 'INGRESO':'EGRESO'}}


Nombre : {{$ingr->ingr_nombre}}

Documento: {{$ingr->ingr_documento}}

Fecha de Ingreso:  {{ date('d-m-y H:i:s', strtotime($ingr->ingr_entrada))}}

@if ($type==0)
Fecha de Egreso:  {{ date('d-m-y H:i:s', strtotime($ingr->ingr_salida))}}

@endif


Observacion:  {{$ingr->ingr_observacion}}


    {{-- Subcopy --}}
    @slot('subcopy')
        @component('mail::subcopy')
        Si usted no reconoce esta acción, por favor comunicarse con la Administración.
        @endcomponent
    @endslot


    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')

        @endcomponent
    @endslot
@endcomponent

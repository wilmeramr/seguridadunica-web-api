@include('common.modalHead')

<div class="row">

<div class="col-sm-12 col-md-8">
    <div class="form-group">
        <label >Nombre completo:</label>
        <input type="text" wire:model.lazy="aut_nombre"  class="form-control" placeholder="NOMBRE COMPLETO">
        @error('aut_nombre') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>

<div class="col-sm-12 col-md-4">
    <div class="form-group">
        <label >DNI:</label>
        <input type="number" wire:model.lazy="aut_dni"  class="form-control" placeholder="DNI" maxlength="15">
        @error('aut_dni') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>

<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <label >Email:</label>
        <input type="email" wire:model.lazy="aut_email"  class="form-control" placeholder="EMAIL" >
        @error('aut_email') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>


<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <label >Seleccione: </label>
        <input id="rangeCalendarFlatpickr"  wire:model.lazy="aut_rangedate" class="form-control flatpickr flatpickr-input" type="text" placeholder="RANGO DE FECHAS.." readonly="readonly">
        @error('aut_rangedate') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>
<div class="col-sm-12 col-md-12">
    <div class="form-group">
        <label >Comentarios: </label>
        <textarea class="form-control"  wire:model.lazy="aut_comentarios" aria-label="With textarea"></textarea>
        @error('aut_comentarios') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>

<div class="col-sm-12 col-md-12 mt-3">
    @error('aut_autoriza') <span class="text-danger er">{{$message}}</span>
    @enderror
    <div class="form-group">
        <label class="my-1 mr-2">Autoriza :</label>
        <select wire:model="aut_autoriza"  class="form-control" >
            <option  value="Elegir" selected>Elegir</option>
            @foreach ($users as $user )
            <option  value="{{$user->id }}" >{{$user->us_name }} - {{$user->lot_name}} </option>
            @endforeach
        </select>

</div>

</div>


</div>


@include('common.modalFooter')


@include('common.modalHead')

<div class="row">




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

 <div class="col-sm-12 col-md-4">
    <div class="form-group">
        <label >DNI:</label>
        <input type="text" id="dni"
         wire:model.lazy="aut_dni"
         wire:keydown.enter.prevent="$emit('scan-code',$('#dni').val(),'form')"
         class="form-control" placeholder="DNI" maxlength="80">
        @error('aut_dni') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>

<div class="col-sm-12 col-md-8">
    <div class="form-group">
        <label >Nombre completo:</label>
        <input type="text" wire:model.lazy="aut_nombre"  class="form-control" placeholder="NOMBRE COMPLETO">
        @error('aut_nombre') <span class="text-danger er">{{$message}}</span>
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

<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <label >Seleccionar tipo de servicio</label>
       <select wire:model.lazy="servicio_id"  class="form-control" >
           <option value="Elegir" selected>Elegir</option>
         @foreach ($servicios as $servicio )
           <option value="{{ $servicio->stp_id}}">{{ $servicio->stp_descripcion}}</option>
         @endforeach
       </select>
        @error('servicio_id') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>

<div class="col-sm-12 col-md-6">
    <div class="form-group">
        @error('chk') <span class="text-danger er">{{$message}}</span>
        @enderror
        <div class="n-chk">
            <label class="new-control new-checkbox new-checkbox-rounded checkbox-primary">
              <input type="checkbox"  wire:model="chk"
              value="lunes" class="new-control-input">
              <span class="new-control-indicator"></span> <label>Lunes: </label>
            </label>
        </div>
        <div class="row">

            <div class="form-group col-sm-6 col-md-6">

              Desde  <input id="timeFlatpickr" wire:model.lazy="aut_lunes_desde" {{in_array('lunes',$chk )? '':'disabled'}}   class="form-control flatpickr flatpickrTime flatpickr-input" type="text" placeholder="Seleccione hora..." readonly="readonly">
            </div>
            <div class="form-group col-sm-6 col-md-6">
                Hasta  <input id="timeFlatpickr2"  wire:model.lazy="aut_lunes_hasta" {{in_array('lunes',$chk )? '':'disabled'}}  class="form-control flatpickr flatpickrTime  flatpickr-input" type="text" placeholder="Seleccione hora..." readonly="readonly">
              </div>
        </div>
</div>
</div>
<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <div class="n-chk">
            <label class="new-control new-checkbox new-checkbox-rounded checkbox-primary">
              <input type="checkbox" wire:model="chk"
              value="martes" class="new-control-input">
              <span class="new-control-indicator"></span>   <label >Martes: </label>
            </label>
        </div>

        <div class="row">
            <div class="form-group col-sm-6 col-md-6">
              Desde  <input id="timeFlatpickr" wire:model.lazy="aut_martes_desde"  {{in_array('martes',$chk )? '':'disabled'}}   class="form-control flatpickr flatpickrTime flatpickr-input" type="text" placeholder="Seleccione hora..." readonly="readonly">
            </div>
            <div class="form-group col-sm-6 col-md-6">
                Hasta  <input id="timeFlatpickr2"  wire:model.lazy="aut_martes_hasta"  {{in_array('martes',$chk )? '':'disabled'}}  class="form-control flatpickr flatpickrTime  flatpickr-input" type="text" placeholder="Seleccione hora..." readonly="readonly">
              </div>
        </div>
</div>
</div>
<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <div class="n-chk">
            <label class="new-control new-checkbox new-checkbox-rounded checkbox-primary">
              <input type="checkbox" wire:model="chk"
              value="miercoles" class="new-control-input">
              <span class="new-control-indicator"></span>   <label >Miercoles: </label>
            </label>
        </div>
        <div class="row">
            <div class="form-group col-sm-6 col-md-6">
              Desde  <input id="timeFlatpickr" wire:model.lazy="aut_miercoles_desde"  {{in_array('miercoles',$chk )? '':'disabled'}}  class="form-control flatpickr flatpickrTime flatpickr-input" type="text" placeholder="Seleccione hora..." readonly="readonly">
            </div>
            <div class="form-group col-sm-6 col-md-6">
                Hasta  <input id="timeFlatpickr2"  wire:model.lazy="aut_miercoles_hasta" {{in_array('miercoles',$chk )? '':'disabled'}}  class="form-control flatpickr flatpickrTime  flatpickr-input" type="text" placeholder="Seleccione hora..." readonly="readonly">
              </div>
        </div>
</div>
</div>
<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <div class="n-chk">
            <label class="new-control new-checkbox new-checkbox-rounded checkbox-primary">
              <input type="checkbox" wire:model="chk"
              value="jueves" class="new-control-input">
              <span class="new-control-indicator"></span>   <label >Jueves: </label>
            </label>
        </div>
        <div class="row">
            <div class="form-group col-sm-6 col-md-6">
              Desde  <input id="timeFlatpickr" wire:model.lazy="aut_jueves_desde"  {{in_array('jueves',$chk )? '':'disabled'}}  class="form-control flatpickr flatpickrTime flatpickr-input" type="text" placeholder="Seleccione hora..." readonly="readonly">
            </div>
            <div class="form-group col-sm-6 col-md-6">
                Hasta  <input id="timeFlatpickr2"  wire:model.lazy="aut_jueves_hasta" {{in_array('jueves',$chk )? '':'disabled'}}  class="form-control flatpickr flatpickrTime  flatpickr-input" type="text" placeholder="Seleccione hora..." readonly="readonly">
              </div>
        </div>
</div>
</div>
<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <div class="n-chk">
            <label class="new-control new-checkbox new-checkbox-rounded checkbox-primary">
              <input type="checkbox" wire:model="chk"
              value="viernes" class="new-control-input">
              <span class="new-control-indicator"></span>   <label >Viernes: </label>
            </label>
        </div>
        <div class="row">
            <div class="form-group col-sm-6 col-md-6">
              Desde  <input id="timeFlatpickr" wire:model.lazy="aut_viernes_desde"  {{in_array('viernes',$chk )? '':'disabled'}}  class="form-control flatpickr flatpickrTime flatpickr-input" type="text" placeholder="Seleccione hora..." readonly="readonly">
            </div>
            <div class="form-group col-sm-6 col-md-6">
                Hasta  <input id="timeFlatpickr2"  wire:model.lazy="aut_viernes_hasta" {{in_array('viernes',$chk )? '':'disabled'}}  class="form-control flatpickr flatpickrTime  flatpickr-input" type="text" placeholder="Seleccione hora..." readonly="readonly">
              </div>
        </div>
</div>
</div>
<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <div class="n-chk">
            <label class="new-control new-checkbox new-checkbox-rounded checkbox-primary">
              <input type="checkbox" wire:model="chk"
              value="sabados" class="new-control-input">
              <span class="new-control-indicator"></span>   <label >Sabados: </label>
            </label>
        </div>
        <div class="row">
            <div class="form-group col-sm-6 col-md-6">
              Desde  <input id="timeFlatpickr" wire:model.lazy="aut_sabados_desde"  {{in_array('sabados',$chk )? '':'disabled'}}  class="form-control flatpickr flatpickrTime flatpickr-input" type="text" placeholder="Seleccione hora..." readonly="readonly">
            </div>
            <div class="form-group col-sm-6 col-md-6">
                Hasta  <input id="timeFlatpickr2"  wire:model.lazy="aut_sabados_hasta" {{in_array('sabados',$chk )? '':'disabled'}}  class="form-control flatpickr flatpickrTime  flatpickr-input" type="text" placeholder="Seleccione hora..." readonly="readonly">
              </div>
        </div>
</div>
</div>

<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <div class="n-chk">
            <label class="new-control new-checkbox new-checkbox-rounded checkbox-primary">
              <input type="checkbox" wire:model="chk"
              value="domingos" class="new-control-input">
              <span class="new-control-indicator"></span>   <label >Domingos: </label>
            </label>
        </div>
        <div class="row">
            <div class="form-group col-sm-6 col-md-6">
              Desde  <input id="timeFlatpickr" wire:model.lazy="aut_domingos_desde"  {{in_array('domingos',$chk )? '':'disabled'}}  class="form-control flatpickr flatpickrTime flatpickr-input" type="text" placeholder="Seleccione hora..." readonly="readonly">
            </div>
            <div class="form-group col-sm-6 col-md-6">
                Hasta  <input id="timeFlatpickr2"  wire:model.lazy="aut_domingos_hasta" {{in_array('domingos',$chk )? '':'disabled'}}  class="form-control flatpickr flatpickrTime  flatpickr-input" type="text" placeholder="Seleccione hora..." readonly="readonly">
              </div>
        </div>

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


</div>

@include('common.modalFooter')


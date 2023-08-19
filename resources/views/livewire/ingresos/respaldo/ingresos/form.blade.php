@include('common.modalHead')


<div class="row">

    <div id="my_camera" class="col-sm-12 text-center">
        <div class=" text-center">
            <div class=" text-center  ">

                @if ($ingr_foto)
                  <img id='img_foto' class="rounded mx-auto d-block" width="320px" height="240px" src="{{$ingr_foto->temporaryUrl()}}" alt="" \>

                  @elseif  ($ingr_foto_base64)
                  <img id='img_foto' class="rounded mx-auto d-block" width="320px" height="240px" src="{{$ingr_foto_base64}}" alt="" \>

                  @endif


            </div>
        </div>
    </div>





    <div class="col-sm-12 mt-3 text-center ">
    <div class="btn-group btn-group-toggle text-center">
        <div class="btn-group btn-group-toggle text-center ">
    <input  class="btn btn-primary text-center" type=button value="Activar Camara" onClick="active_camare()"\>

    <input  class="btn btn-primary" id="take"  type=button value="Tomar foto" onClick="take_snapshot()"\>
    <input  class="btn btn-primary text-center"  id="Desactivar" type=button value="Desactivar Camara" onClick="desactivar_camera()"\>

</div>
        </div>
    </div>

    <div class="col-sm-12 mt-3  text-center">
        <div class="btn-group btn-group-toggle">

            <div  >
                <label for="files" class="btn btn-secondary form-control-file">Seleccione Imagen</label>
                <input id="files" style="visibility:hidden;"  wire:model="ingr_foto" accept="image/x-png,image/gif, image/jpeg" type="file">
              </div>

        </div>
        @error('ingr_foto') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>


    <div class="col-sm-4 mt-1">

        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">
                            DNI
                    </span>
                </span>
            </div>
            <input type="text" id="dni"   wire:keydown.enter.prevent="$emit('scan-code',$('#dni').val(),'form')" wire:model="ingr_doc" class="form-control" placeholder="N° Documento">
        </div>
        @error('ingr_doc') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>

    <div class="col-sm-8 mt-1">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">
                        Nombre
                    </span>
                </span>
            </div>
            <input type="text" wire:model.lazy="ingr_nombre" class="form-control" placeholder="Nombre Completo">
        </div>
        @error('ingr_nombre') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>


    <div class="col-sm-6 mt-3">
        <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">
                        Tipo Visita
                    </span>
                </span>
              </div>


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

    </div>

    <div class="col-sm-6 mt-3">
        <div class="form-group">
            <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">
                        ART
                    </span>
                </span>
            </div>
            <input  wire:model.lazy="ingr_art_vto" class="form-control flatpickr basicFlatpickr flatpickr-input" type="text" placeholder="Fecha Vigencia" readonly="readonly">
            @error('ingr_art_vto') <span class="text-danger er">{{$message}}</span>
            @enderror
        </div>
    </div>
</div>

<div class="col-sm-6 mt-3">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <span class="fas fa-edit">
                    N° Licencia
                </span>
            </span>
        </div>
        <input type="number" wire:model.lazy="ingr_licencia_numero" class="form-control" placeholder="Numero">
    </div>
    @error('ingr_licencia_numero') <span class="text-danger er">{{$message}}</span>
    @enderror
</div>
<div class="col-sm-6 mt-3">
    <div class="form-group">

        <input  wire:model.lazy="ingr_licencia_vto" class="form-control flatpickr basicFlatpickr flatpickr-input" type="text" placeholder="Fecha Vencimiento" readonly="readonly">
        @error('ingr_licencia_vto') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>


<div class="col-sm-12 mt-3">
<div class="progress">
    <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">AUTO</div>
  </div>
</div>

<div class="col-sm-4 mt-3">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <span class="fas fa-edit">
                    Marca
                </span>
            </span>
        </div>
        <input type="text" wire:model.lazy="ingr_auto_marca" class="form-control" placeholder="Marca">
    </div>
    @error('ingr_auto_marca') <span class="text-danger er">{{$message}}</span>
    @enderror
</div>

<div class="col-sm-4 mt-3">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <span class="fas fa-edit">
                    Modelo
                </span>
            </span>
        </div>
        <input type="text" wire:model.lazy="ingr_auto_modelo" class="form-control" placeholder="Modelo">
    </div>
    @error('ingr_auto_modelo') <span class="text-danger er">{{$message}}</span>
    @enderror
</div>

<div class="col-sm-4 mt-3">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <span class="fas fa-edit">
                    Color
                </span>
            </span>
        </div>
        <input type="text" wire:model.lazy="ingr_auto_color" class="form-control" placeholder="Color">
    </div>
    @error('ingr_auto_color') <span class="text-danger er">{{$message}}</span>
    @enderror
</div>

<div class="col-sm-4 mt-3">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <span class="fas fa-edit">
                    Seguro
                </span>
            </span>
        </div>
        <input type="text" wire:model.lazy="ingr_seguro_nombre" class="form-control" placeholder="Nombre">
    </div>
    @error('ingr_seguro_nombre') <span class="text-danger er">{{$message}}</span>
    @enderror
</div>

<div class="col-sm-4 mt-3">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <span class="fas fa-edit">
                    N°
                </span>
            </span>
        </div>
        <input type="text" wire:model.lazy="ingr_seguro_numero" class="form-control" placeholder="Póliza">
    </div>
    @error('ingr_seguro_numero') <span class="text-danger er">{{$message}}</span>
    @enderror
</div>

<div class="col-sm-4 mt-3">
    <div class="form-group">
        <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <span class="fas fa-edit">
                    Fecha
                </span>
            </span>
        </div>
        <input  wire:model.lazy="ingr_seguro_vto" class="form-control flatpickr basicFlatpickr flatpickr-input" type="text" placeholder="Vigencia" readonly="readonly">
        @error('ingr_seguro_vto') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>
</div>

    <div class="col-sm-6 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">
                        Patente
                    </span>
                </span>
            </div>
            <input type="text" wire:model.lazy="ingr_patente" class="form-control" placeholder="Patente">
        </div>
        @error('ingr_patente') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
    <div class="col-sm-6 mt-3">
        <div class="form-group">

            <input  wire:model.lazy="ingr_vto" class="form-control flatpickr basicFlatpickr flatpickr-input" type="text" placeholder="Fecha Vencimiento" readonly="readonly">
            @error('ingr_vto') <span class="text-danger er">{{$message}}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">

                        Observación
                    </span>
                </span>
            </div>
            <textarea name="Text1" cols="40" rows="5"   wire:model.lazy="ingr_obser" class="form-control" placeholder="Escribe una observación"></textarea>

        </div>
        @error('ingr_obser') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>


    <div class="col-sm-12 col-md-12 mt-3">
        @error('ingr_autoriza') <span class="text-danger er">{{$message}}</span>
        @enderror
        <div class="form-group">
            <label class="my-1 mr-2">Visita a :</label>
            <select wire:model="ingr_autoriza"  class="form-control" >
                <option  value="Elegir" selected>Elegir</option>
                @foreach ($users as $user )
                <option  value="{{$user->id }}" >{{$user->us_name }} - {{$user->lot_name}} </option>
                @endforeach
            </select>

    </div>

</div>


</div>


@include('common.modalFooter')

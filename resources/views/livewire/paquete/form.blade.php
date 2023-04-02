@include('common.modalHead')


<div class="row">

    <div id="my_camera" class="col-sm-12 text-center">
        <div class=" text-center">
            <div class=" text-center  ">

                @if ($paq_foto)
                  <img id='img_foto' class="rounded mx-auto d-block" width="320px" height="240px" src="{{$paq_foto->temporaryUrl()}}" alt="" \>

                  @elseif  ($paq_foto_base64)
                  <img id='img_foto' class="rounded mx-auto d-block" width="320px" height="240px" src="{{$paq_foto_base64}}" alt="" \>

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
                <input id="files" style="visibility:hidden;"  wire:model="paq_foto" accept="image/x-png,image/gif, image/jpeg" type="file">
              </div>

        </div>
        @error('paq_foto') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>


    <div class="col-sm-12 mt-3">
        <div class="form-group">
            <label >Seleccionar el lote</label>
           <select wire:model.lazy="lote_id"  class="form-control" >
               <option value="Elegir" selected>Elegir</option>
             @foreach ($lotes as $lot )
               <option value="{{ $lot->lot_id}}">{{ $lot->lot_name}}</option>
             @endforeach
           </select>
            @error('lote_id') <span class="text-danger er">{{$message}}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 mt-3">
        <div class="form-group">
            <label >Seleccionar empresa de envíos</label>
           <select wire:model.lazy="empresa_id"  class="form-control" >
               <option value="Elegir" selected>Elegir</option>

               <option value="1">Mercado Libre</option>
               <option value="2">Correo Argentino</option>
               <option value="3">Andreani</option>
               <option value="4">Oca</option>
               <option value="5">Otros</option>
           </select>
            @error('empresa_id') <span class="text-danger er">{{$message}}</span>
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
            <textarea name="Text1" cols="40" rows="5"   wire:model.lazy="paq_obser" class="form-control" placeholder="Escribe una observación"></textarea>

        </div>
        @error('paq_obser') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>




</div>


@include('common.modalFooter')

@include('common.modalHead')





<div class="row">

    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            <label >Titulo</label>
            <input type="text" wire:model.lazy="info_titulo"  class="form-control" placeholder="TITULO">
            @error('info_titulo') <span class="text-danger er">{{$message}}</span>
            @enderror
        </div>
    </div>


    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            <label >Cuerpo:</label>
            <textarea name="Text1" cols="40" rows="5" maxlength="499"   wire:model.lazy="info_body" class="form-control" placeholder="Escribe el cuerpo"></textarea>

            @error('info_body') <span class="text-danger er">{{$message}}</span>
            @enderror
        </div>
    </div>


</div>



@include('common.modalFooter')

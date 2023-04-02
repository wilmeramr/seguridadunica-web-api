@include('common.modalHead')





<div class="row">

    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            <label >Titulo</label>
            <input type="text" wire:model.lazy="notic_titulo"  class="form-control" placeholder="TITULO">
            @error('notic_titulo') <span class="text-danger er">{{$message}}</span>
            @enderror
        </div>
    </div>


    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            <label >Cuerpo:</label>
            <textarea name="Text1" cols="40" rows="5" maxlength="1500"   wire:model.lazy="notic_body" class="form-control" placeholder="Escribe el cuerpo"></textarea>

            @error('notic_body') <span class="text-danger er">{{$message}}</span>
            @enderror
        </div>
    </div>


</div>



@include('common.modalFooter')

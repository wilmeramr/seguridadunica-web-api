@include('common.modalHead')


<div class="row">




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
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-location-arrow">

                    </span>
                </span>
            </div>
            <input type="text" wire:model.lazy="exp_link_pago" class="form-control" placeholder="LINK PAGO">
        </div>
        @error('exp_link_pago') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
    <div class="col-sm-12 mt-3  text-center">
        <div class="btn-group btn-group-toggle">

            <div  >
                <label for="files" class="btn btn-secondary form-control-file">Seleccione el PDF</label>
                <input id="files" style="visibility:hidden;"  wire:model="exp_pdf" accept="application/pdf" type="file">
              </div>

        </div>
        @if ($exp_pdf)
        PDF se cargo correctamente.

        @endif

        @error('exp_pdf') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>




</div>


@include('common.modalFooter')

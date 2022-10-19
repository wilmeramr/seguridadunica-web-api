@include('common.modalHead')





<div class="row">

    <div class="col-sm-12 col-md-8">
        <div class="form-group">
            <label >Nombre</label>
            <input type="text" wire:model.lazy="lot_name"  class="form-control" placeholder="NOMBRE DEL LOTE">
            @error('lot_name') <span class="text-danger er">{{$message}}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-8">
        <div class="form-group">
            <label>Country</label>
            <select wire:model="co_id"  class="form-control">
                <option value="Elegir">Elegir</option>
                @foreach ($countries as $country )
                <option value="{{$country->co_id}}" >{{$country->co_name}}</option>

                @endforeach
            </select>
            @error('co_id') <span class="text-danger er">{{$message}}</span>
            @enderror
        </div>
    </div>
</div>



















@include('common.modalFooter')

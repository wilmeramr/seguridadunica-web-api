@include('common.modalHead')


<div class="row">

    <div class="col-sm-12">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">

                    </span>
                </span>
            </div>
            <input type="number" wire:model.lazy="co_cuit" class="form-control" placeholder="CUIT">
        </div>
        @error('co_cuit') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
    <div class="col-sm-12 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-user">

                    </span>
                </span>
            </div>
            <input type="text" wire:model.lazy="co_name" class="form-control" placeholder="NOMBRE DEL COUNTRY">
        </div>
        @error('co_name') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>

    <div class="col-sm-12 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-envelope">

                    </span>
                </span>
            </div>
            <input type="email" wire:model.lazy="co_email" class="form-control" placeholder="EMAIL DEL COUNTRY">
        </div>
        @error('co_email') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>

    <div class="col-sm-12 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-location-arrow">

                    </span>
                </span>
            </div>
            <input type="text" wire:model.lazy="co_como_llegar" class="form-control" placeholder="COMO LLEGO AL COUNTRY">
        </div>
        @error('co_como_llegar') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
    <div class="col-sm-12 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-globe">

                    </span>
                </span>
            </div>
            <input type="text" wire:model.lazy="co_reg_url_propietario" class="form-control" placeholder="URL PARA EL REGISTRO DE PROPIETARIOS">
        </div>
        @error('co_reg_url_propietario') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>


    <div class="col-sm-12 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-globe">

                    </span>
                </span>
            </div>
            <input type="text" wire:model.lazy="co_url_gps" class="form-control" placeholder="URL DEL GPS">
        </div>
        @error('co_url_gps') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>

    <div class="col-sm-12 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-globe">

                    </span>
                </span>
            </div>
            <input type="text" wire:model.lazy="co_url_video" class="form-control" placeholder="URL DEL LOS VIDEOS">
        </div>
        @error('co_url_video') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>




    <div class="col-sm-12 mt-3">
        <div class="text-center" >
            <label >LOGO {{$co_logo}}</label><br>
            <input type="file"  wire:model="co_logo" accept="image/x-png,image/gif, image/jpeg">

        @error('co_logo') <span class="text-danger er">{{$message}}</span>
        @enderror

        </div>

    </div>

    @if ($co_logo)
    <div class="col-sm-12 mt-3">
        <div class="text-center">
        <img class="rounded mx-auto" width="450px" height="250px" src="{{$co_logo->temporaryUrl()}}" alt="">
    </div>
    </div>
    @endif

</div>


@include('common.modalFooter')

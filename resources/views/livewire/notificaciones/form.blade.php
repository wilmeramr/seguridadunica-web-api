@include('common.modalHead')


<div class="row">


    <div class="col-sm-12 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">

                        Enviar a:
                    </span>
                </span>
            </div>
            <select class="custom-select" wire:model="chk" style="background-color: transparent" size="2">
                <option   value="T" >Todos</option>
                <option value="L">Lote</option>

            </select>

        </div>

    </div>

    <div class="col-sm-12 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">
                        Titulo:
                    </span>
                </span>
            </div>
            <input type="text" wire:model.lazy="noti_titulo" class="form-control" placeholder="TITULO DE LA NOTIFICACIÓN">
        </div>
        @error('noti_titulo') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
    <div class="col-sm-12 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">

                        Mensaje
                    </span>
                </span>
            </div>
            <textarea name="Text1" cols="40" rows="5"   wire:model.lazy="noti_body" class="form-control" placeholder="Escribe la notificacion a enviar"></textarea>

        </div>
        @error('noti_body') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>

    @if (Str::contains($chk, 'L'))
                            <div class="col-sm-12 col-md-12 mt-3">
                            @error('noti_autoriza') <span class="text-danger er">{{$message}}</span>
                            @enderror
                            <div class="form-group" wire:ignore>

                            <label >Enviar a: </label>
                            <select  wire:model="noti_autoriza" class="form-control tagging form-small" >
                                <option  value="Elegir" selected>Elegir</option>
                                @foreach ($users as $user )
                                <option  value="{{$user->id }}" >C:.{{$user->lot_country_id}}-{{$user->lot_name}} - {{$user->us_name }} </option>
                                @endforeach
                            </select>

                        </div>

                    </div>



    @endif





</div>


@include('common.modalFooter')

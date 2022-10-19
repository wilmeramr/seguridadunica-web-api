@include('common.modalHead')


<div class="row">

    <div class="col-sm-12 col-md-12">
        @error('masc_autoriza') <span class="text-danger er">{{$message}}</span>
        @enderror
        <div class="form-group" wire:ignore>

         <label >Autorizado por: </label>
        <select  wire:model="masc_autoriza" class="form-control tagging form-small" >
            <option  value="Elegir" selected>Elegir</option>
            @foreach ($users as $user )
            <option  value="{{$user->id }}" >C:.{{$user->lot_country_id}}-{{$user->lot_name}} - {{$user->us_name }} </option>
            @endforeach
        </select>

     </div>

 </div>

 <div class="col-sm-12 col-md-6">
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-user">
                        Tipo:
                    </span>
                </span>
            </div>

        <select wire:model="masc_especie_id"  class="form-control">
            <option value="Elegir">Elegir</option>
            @foreach ($tipos as $tipo )
            <option value="{{$tipo->masc_esp_id}}" >{{$tipo->masc_esp_name}}</option>

            @endforeach
        </select>

    </div>
    @error('masc_especie_id') <span class="text-danger er">{{$message}}</span>
    @enderror
</div>
</div>

<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <span class="fas fa-user">
                    Genero:
                </span>
            </span>
        </div>

        <select wire:model="masc_genero_id"  class="form-control">
            <option value="Elegir">Elegir</option>
            @foreach ($generos as $genero )
            <option value="{{$genero->masc_gene_id}}" >{{$genero->masc_gene_name}}</option>

            @endforeach
        </select>

    </div>
    @error('masc_genero_id') <span class="text-danger er">{{$message}}</span>
    @enderror
</div>
</div>
<div class="col-sm-12">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <span class="fas fa-user">
                    Nombre:
                </span>
            </span>
        </div>
        <input type="text" wire:model.lazy="masc_name" class="form-control" placeholder="NOMBRE DE LA MASCOTA">
    </div>
    @error('masc_name') <span class="text-danger er">{{$message}}</span>
    @enderror
</div>
    <div class="col-sm-6 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">
                        Peso:
                    </span>
                </span>
            </div>
            <input type="number" wire:model.lazy="masc_peso" class="form-control" placeholder="PESO">
        </div>
        @error('masc_peso') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>

    <div class="col-sm-6 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">
                        Raza:
                    </span>
                </span>
            </div>
            <input type="text" wire:model.lazy="masc_raza" class="form-control" placeholder="RAZA">
        </div>
        @error('masc_raza') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>

    <div class="col-sm-12 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-calendar">
                        Fecha de Nacimiento:
                    </span>
                </span>
            </div>
            <input id="basicFlatpickr" wire:model.lazy="masc_fecha_nacimiento"    class="form-control basicFlatpickr flatpickr flatpickrTime flatpickr-input" type="text" placeholder="Seleccione fecha..." readonly="readonly">
        </div>
        @error('masc_fecha_nacimiento') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>

    <div class="col-sm-12 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-calendar">
                        Fecha de vacunación:
                    </span>
                </span>
            </div>
            <input id="basicFlatpickr" wire:model.lazy="masc_fecha_vacunacion"    class="form-control flatpickr basicFlatpickr flatpickrTime flatpickr-input" type="text" placeholder="Seleccione fecha..." readonly="readonly">
        </div>
        @error('masc_fecha_vacunacion') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>


    <div class="col-sm-12 mt-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">

                        Descripción
                    </span>
                </span>
            </div>
            <textarea name="Text1" cols="40" rows="5"   wire:model.lazy="masc_descripcion" class="form-control" placeholder="Escribe alguna description"></textarea>

        </div>
        @error('masc_descripcion') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>

    <div class="col-sm-12 mt-3">
        <div class="text-center" >
            <label >Foto:</label><br>
            <input type="file"  wire:model="masc_url_foto" accept="image/x-png,image/gif, image/jpeg">

        @error('masc_url_foto') <span class="text-danger er">{{$message}}</span>
        @enderror

        </div>

    </div>

    @if ($masc_url_foto)
    <div class="col-sm-12 mt-3">
        <div class="text-center">
        <img class="rounded mx-auto" width="450px" height="250px" src="{{$masc_url_foto->temporaryUrl()}}" alt="">
    </div>
    </div>
    @endif

</div>


@include('common.modalFooter')

@include('common.modalHead')





<div class="row">

    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            <label >Nombre</label>
            <input type="text" wire:model.lazy="tresr_description"  class="form-control" placeholder="NOMBRE">
            @error('tresr_description') <span class="text-danger er">{{$message}}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Tipo de Reservas</label>
            <select wire:model="tresr_tipo"  class="form-control">
                <option value="Elegir">Elegir</option>

                <option value="1" >Depostivas</option>
                <option value="2" >Gastronómicos</option>
                <option value="3" >Amenitis</option>

            </select>
            @error('tresr_tipo') <span class="text-danger er">{{$message}}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Rango en horas:</label>
            <select wire:model="tresr_tipo_horarios"  class="form-control">
                <option value="Elegir">Elegir</option>
                @foreach ($horas as $hora )
                <option value="{{ $hora->hor_tipo}}">{{ $hora->hor_tipo}}</option>
              @endforeach

            </select>
            @error('tresr_tipo_horarios') <span class="text-danger er">{{$message}}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label >Cantidad:</label>
            <input type="number" wire:model.lazy="tresr_cant_lugares"  class="form-control" placeholder="Cantidad">
            @error('tresr_cant_lugares') <span class="text-danger er">{{$message}}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label >Email (Opcional)</label>
            <input type="text" wire:model.lazy="tresr_email"  class="form-control" placeholder="Email">
            @error('tresr_email') <span class="text-danger er">{{$message}}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            <label >Url  (Opcional)</label>
            <input type="text" wire:model.lazy="tresr_url"  class="form-control" placeholder="url">
            @error('tresr_url') <span class="text-danger er">{{$message}}</span>
            @enderror
        </div>
    </div>

</div>



@include('common.modalFooter')

@include('common.modalHead')

<div class="row">

<div class="col-sm-12 col-md-8">
    <div class="form-group">
        <label >Nombre</label>
        <input type="text" wire:model.lazy="us_name"  class="form-control" placeholder="NOMBRE">
        @error('us_name') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>

<div class="col-sm-12 col-md-4">
    <div class="form-group">
        <label >Teléfono</label>
        <input type="text" wire:model.lazy="us_phone"  class="form-control" placeholder="Teléfono" maxlength="15">
        @error('us_phone') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>

<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <label >Email</label>
        <input type="email" wire:model.lazy="email"  class="form-control" placeholder="Email" >
        @error('email') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>

<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <label >Password</label>
        <input type="password" wire:model.lazy="password"  class="form-control">
        @error('password') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>

<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <label >Estatus</label>
       <select wire:model.lazy="status"  class="form-control" >
           <option value="Elegir" selected>Elegir</option>
           <option value="Active">Activo</option>
           <option value="Locked">Bloqueado</option>
       </select>
        @error('status') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>


<div class="col-sm-12 col-md-6">
    <div class="form-group">
        <label >Asignar Role</label>
       <select wire:model.lazy="role"  class="form-control" >
           <option value="Elegir" selected>Elegir</option>
         @foreach ($roles as $role )
           <option value="{{ $role->id}}" >{{ $role->name}}</option>
         @endforeach
       </select>
        @error('role') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>
 <div class="col-sm-12 col-md-6">
    <div class="form-group">
        <label >Asignar Lote</label>
       <select wire:model.lazy="us_lote_id"  class="form-control" >
           <option value="Elegir" selected>Elegir</option>
         @foreach ($lotes as $lote )
           <option value="{{ $lote->lot_id}}">{{ $lote->lot_name}}</option>
         @endforeach
       </select>
        @error('us_lote_id') <span class="text-danger er">{{$message}}</span>
        @enderror
    </div>
</div>
</div>


@include('common.modalFooter')


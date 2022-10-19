
<div wire:ignore.self class="modal fade" id="theModalDetalle" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-white">
              <b>{{$componentName}}</b> | {{$detalle_mascota}}
          </h5>
        <h6 class="text-center text-warning" wire:loading>Por favor espere </h6>
        </div>
        <div class="modal-body">



                    <div class="col-sm-12 col-md-12">
                        <div >
                            @if ($mascotaDetail != null)

                            <img src="{{$mascotaDetail->masc_url_foto}}" width="300" height="300">
                                <br>
                                <p class="font-weight-bold"> Lote:  {{$mascotaDetail->lot_name}}</p>
                                 <p class="font-weight-bold"> Nombre:  {{$mascotaDetail->masc_name}}</p>
                                 <p class="font-weight-bold"> Raza:  {{$mascotaDetail->masc_raza}}</p>
                                 <p class="font-weight-bold"> Tipo:  {{$mascotaDetail->masc_esp_name}}</p>
                                 <p class="font-weight-bold"> Ultima vacunacion:  {{$mascotaDetail->masc_fecha_vacunacion}}</p>
                                 <p class="font-weight-bold"> Registrado por :  {{$mascotaDetail->us_name.' '. $mascotaDetail->email}}</p>
                                 <p class="font-weight-bold"> Descripción :  {{$mascotaDetail->masc_descripcion}}</p>

                            @endif

                        </div>
                    </div>






        </div>
        <div class="modal-footer">
         <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info" data-dismiss="modal">Cerrar</button>

        </div>
        </div>
        </div>
        </div>

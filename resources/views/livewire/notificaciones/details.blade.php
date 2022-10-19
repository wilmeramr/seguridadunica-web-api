
<div wire:ignore.self class="modal fade" id="theModalDetalle" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-white">
              <b>{{$componentName}}</b> | {{$detalle_notificacion}}
          </h5>
        <h6 class="text-center text-warning" wire:loading>Por favor espere </h6>
        </div>
        <div class="modal-body">



                    <div class="col-sm-12 col-md-12">
                        <div >
                            @if ($notificacionDetail != null)


                                <p class="font-weight-bold"> Tipo:  {{$notificacionDetail->noti_event}}</p>
                                 <p class="font-weight-bold"> Titulo:  {{$notificacionDetail->noti_titulo}}</p>
                                 <p class="font-weight-bold"> Cuerpo:  {{$notificacionDetail->noti_body}}</p>



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

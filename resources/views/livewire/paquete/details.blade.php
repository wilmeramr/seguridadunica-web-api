
<div wire:ignore.self class="modal fade" id="theModalDetalle" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-white">
              <b>{{$componentName}}</b> | {{$detalle_paquete}}
          </h5>
        <h6 class="text-center text-warning" wire:loading>Por favor espere </h6>
        </div>
        <div class="modal-body">



                    <div class="col-sm-12 col-md-12">
                        <div >
                            @if ($paqueteDetail != null)
                            <table>
                            <tr>
                                <td>


                                <p class="font-weight-bold"> Lote:  {{$paqueteDetail->lot_name}}</p>
                                <p class="font-weight-bold"> Empresa:  {{$paqueteDetail->empresa_envio}}</p>
                                <p class="font-weight-bold"> Fecha de Entrada: {{\Carbon\Carbon::parse($paqueteDetail->created_at)->format('d-m-Y H:i')}} </p>
                                <p class="font-weight-bold"> Observación:  {{$paqueteDetail-> pad_observacion}}</p>

                        </td>
                            <td>
                                <img src="{{$paqueteDetail->paq_foto}}" width="250px" height="240px" alt="" >
                            </td>
                            </tr>
                            </table>

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

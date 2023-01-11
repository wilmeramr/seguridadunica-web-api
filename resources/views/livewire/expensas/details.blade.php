
<div wire:ignore.self class="modal fade" id="theModalDetalle" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-white">
              <b>{{$componentName}}</b> | {{$detalle_expensa}}
          </h5>
        <h6 class="text-center text-warning" wire:loading>Por favor espere </h6>
        </div>
        <div class="modal-body">



                    <div class="col-sm-12 col-md-12">
                        <div >
                            @if ($expensasDetail != null)
                            <table>
                            <tr>
                                <td>


                                <p class="font-weight-bold"> Lote:  {{$expensasDetail->lot_name}}</p>
                                <p class="font-weight-bold"> Nombre Archivo:  {{$expensasDetail->exp_name}}</p>
                                <p class="font-weight-bold"> Fecha de Entrada: {{\Carbon\Carbon::parse($expensasDetail->updated_at)->format('d-m-Y H:i')}} </p>
                                @if (\Str::contains(strtolower($expensasDetail->exp_doc_url),'http'))
                                <a target="_blank" href="{{$expensasDetail->exp_doc_url}}"> <h6>Ir al documento</h6></a>

                                @endif

                                <p class="font-weight-bold"> Link de pago:  {{$expensasDetail->exp_link_pago}}</p>

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

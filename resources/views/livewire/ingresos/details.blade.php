
<div wire:ignore.self class="modal fade" id="theModalDetalle" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-white">
              <b>{{$componentName}}</b> | {{$detalle_ingreso}}
          </h5>
        <h6 class="text-center text-warning" wire:loading>Por favor espere </h6>
        </div>
        <div class="modal-body">



                    <div class="col-sm-12 col-md-12">
                        <div >
                            @if ($ingresoDetail != null)


                                <p class="font-weight-bold badge {{$ingresoDetail->ingr_salida == null ? 'badge-success':'badge-danger'}} text-uppercase"> Estado:  {{$ingresoDetail->estado}}</p>
                                <p class="font-weight-bold"> Documento:  {{$ingresoDetail->ingr_documento}}</p>
                                <p class="font-weight-bold"> Nombre:  {{$ingresoDetail->ingr_nombre}}</p>
                                <p class="font-weight-bold"> Visito:  {{$ingresoDetail->us_name}}</p>
                                <p class="font-weight-bold"> Lote:  {{$ingresoDetail->lot_name}}</p>
                                <p class="font-weight-bold"> Fecha de Entrada:  {{$ingresoDetail->ingr_entrada}}</p>
                                <p class="font-weight-bold"> Fecha de Salida:  {{$ingresoDetail->ingr_salida}}</p>
                                <p class="font-weight-bold"> Patente:  {{$ingresoDetail->ingr_patente}}</p>
                                <p class="font-weight-bold"> Vencimiento Patente: {{\Carbon\Carbon::parse($ingresoDetail->ingr_patente_venc)->format('d-m-Y H:i')}} </p>


                                <p class="font-weight-bold"> Entrada creada por:  {{$ingresoDetail->use_creador}}</p>








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

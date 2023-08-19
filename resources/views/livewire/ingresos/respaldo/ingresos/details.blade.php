
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
                        <div class="table-responsive" >
                            @if ($ingresoDetail != null)
                            <table class="table table-bordered table striped mt-1">
                            <tr>
                                <td  colspan="2">
                                    <p class="font-weight-bold badge {{$ingresoDetail->ingr_salida == null ? 'badge-success':'badge-danger'}} text-uppercase"> Estado:  {{$ingresoDetail->estado}}</p>
                                    <p class="font-weight-bold"> Documento:  {{$ingresoDetail->ingr_documento}}</p>
                                    <p class="font-weight-bold"> Nombre:  {{$ingresoDetail->ingr_nombre}}</p>
                                    @if ($ingresoDetail->ingr_art_vto != null)
                                    <p class="font-weight-bold"> Art. Vto: {{\Carbon\Carbon::parse($ingresoDetail->ingr_art_vto)->format('d-m-Y')}}</p>
                                    @else
                                    <p class="font-weight-bold"> Art. Vto: No Registrado</p>

                                    @endif
                                    <p class="font-weight-bold"> N° Licencia:  {{$ingresoDetail->ingr_licencia_numero}}</p>
                                    @if ($ingresoDetail->ingr_licencia_vto != null)
                                    <p class="font-weight-bold"> Lic. Vto: {{\Carbon\Carbon::parse($ingresoDetail->ingr_licencia_vto)->format('d-m-Y')}}</p>
                                    @else
                                    <p class="font-weight-bold"> Art. Vto: No Registrado</p>

                                    @endif
                                    <p class="font-weight-bold"> Visito:  {{$ingresoDetail->us_name}}</p>
                                    <p class="font-weight-bold"> Lote:  {{$ingresoDetail->lot_name}}</p>
                                    <p class="font-weight-bold"> Fecha de Entrada:  {{$ingresoDetail->ingr_entrada}}</p>
                                    <p class="font-weight-bold"> Fecha de Salida:  {{$ingresoDetail->ingr_salida}}</p>

                                </td>
                                 <td>
                                    <img src="{{$ingresoDetail->ingr_foto}}" width="250px" height="240px" alt="" >
                                 </td>
                             </tr>

                            <tr>
                                <td>
                                <p>Informacion del Auto</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                     <p class="font-weight-bold"> Marca:  {{$ingresoDetail->ingr_auto_marca}}</p>
                                </td>
                                <td>
                                    <p class="font-weight-bold"> Modelo:  {{$ingresoDetail->ingr_auto_modelo}}</p>
                                </td>
                                <td>
                                    <p class="font-weight-bold"> Color:  {{$ingresoDetail->ingr_auto_color}}</p>
                                </td>
                            </tr>
                            <tr>
                            <td>
                                 <p class="font-weight-bold"> Seguro Nombre:  {{$ingresoDetail->ingr_seguro_nombre}}</p>
                            </td>
                            <td>
                                    <p class="font-weight-bold"> N° Poliza:  {{$ingresoDetail->ingr_seguro_numero}}</p>
                            </td>
                                <td>
                                    @if ($ingresoDetail->ingr_seguro_vto != null)
                                    <p class="font-weight-bold"> Seguro. Vto: {{\Carbon\Carbon::parse($ingresoDetail->ingr_seguro_vto)->format('d-m-Y')}}</p>
                                    @else
                                    <p class="font-weight-bold"> Art. Vto: No Registrado</p>

                                    @endif
                                </td>
                            </tr>
                            <tr>
                                    <td>
                                            <p class="font-weight-bold"> Patente:  {{$ingresoDetail->ingr_patente}}</p>
                                    </td>
                                    <td>
                                        @if ($ingresoDetail->ingr_patente_venc != null)
                                        <p class="font-weight-bold"> Vencimiento Patente: {{\Carbon\Carbon::parse($ingresoDetail->ingr_patente_venc)->format('d-m-Y')}} </p>
                                        @else
                                        <p class="font-weight-bold"> Art. Vto: No Registrado</p>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="font-weight-bold"> Entrada creada por:  {{$ingresoDetail->use_creador}}</p>
                                    </td>
                                </tr>
                            <tr>
                               <td>
                                <p class="font-weight-bold"> Observación:  {{$ingresoDetail->ingr_observacion}}</p>
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

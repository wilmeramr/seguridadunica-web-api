
<div wire:ignore.self class="modal fade" id="theModalDetalle" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-white">
              <b>{{$componentName}}</b> | {{$at_detalle_autorizacion}}
          </h5>
        <h6 class="text-center text-warning" wire:loading>Por favor espere </h6>
        </div>
        <div class="modal-body">



                    <div class="col-sm-12 col-md-12">
                        <div >
                            @if ($autorizacionDetail != null)
                            <span class="badge {{$autorizacionDetail->aut_activo == 1 ? 'badge-success':'badge-danger'}} text-uppercase">{{ $autorizacionDetail->aut_activo == 1? 'Activo':'Cancelado'}}</span>
                                <br>
                                 <p class="font-weight-bold"> Tipo:  {{$autorizacionDetail->tipo_autorizacion}}</p>

                            <p class="font-weight-bold">{{$autorizacionDetail->aut_tipo !=4? 'Se autoriza a:' : 'Nombre del evento:'}} {{$autorizacionDetail->aut_nombre}}</p>
                            <p class="font-weight-bold"> Autorizado por:  {{$autorizacionDetail->us_name}}</p>
                                @if ($autorizacionDetail->aut_tipo !=4)
                                        <p class="font-weight-bold"> Desde: {{\Carbon\Carbon::parse($autorizacionDetail->aut_desde)->format('d-m-Y')}} </p>
                                        <p class="font-weight-bold"> Hasta:   {{\Carbon\Carbon::parse($autorizacionDetail->aut_hasta)->format('d-m-Y')}}  </p>
                                @else
                                        <p class="font-weight-bold"> Desde: {{\Carbon\Carbon::parse($autorizacionDetail->aut_fecha_evento)->format('d-m-Y H:i')}} </p>
                                        <p class="font-weight-bold"> Hasta:   {{\Carbon\Carbon::parse($autorizacionDetail->aut_fecha_evento_hasta)->format('d-m-Y H:i')}}  </p>
                                @endif
                                @if ($autorizacionDetail->aut_tipo ==2)
                                <hr/>
                                <p class="font-weight-bold"> Horarios:</p>
                                <p class="font-weight-bold"> Lunes: {{$autorizacionDetail->aut_lunes}} </p>
                                <p class="font-weight-bold"> Martes: {{$autorizacionDetail->aut_martes}} </p>
                                <p class="font-weight-bold"> Miercoles: {{$autorizacionDetail->aut_miercoles}} </p>
                                <p class="font-weight-bold"> Jueves: {{$autorizacionDetail->aut_jueves}} </p>
                                <p class="font-weight-bold"> Viernes: {{$autorizacionDetail->aut_viernes}} </p>
                                <p class="font-weight-bold"> Sabado: {{$autorizacionDetail->aut_sabado}} </p>
                                <p class="font-weight-bold"> Domingo: {{$autorizacionDetail->aut_domingo}} </p>

                                @endif

                                @if ($autorizacionDetail->aut_tipo ==3)
                                <hr/>
                                <p class="font-weight-bold"> Vigencia por: {{$autorizacionDetail->aut_lunes}} </p>
                                @endif

                                @if ($autorizacionDetail->aut_tipo ==4)
                                <hr/>
                                <p class="font-weight-bold"> Cantidad de invitados: {{$autorizacionDetail->aut_cantidad_invitado}} </p>
                                @endif


                                <hr/>


                                <p class="font-weight-bold"> Comentario:  {{$autorizacionDetail->aut_comentario}}</p>
                            @endif




                        </div>
                    </div>






        </div>
        <div class="modal-footer">
         <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info" data-dismiss="modal">Cerrar</button>
        @if ($selected_id < 1)
         <button type="button" wire:click.prevent="CreatePermission()" class="btn btn-dark close-modal" >Entrada</button>
         @else
         <button type="button" wire:click.prevent="UpdatePermission()" class="btn btn-dark close-modal" >Salida</button>

        @endif
        </div>
        </div>
        </div>
        </div>

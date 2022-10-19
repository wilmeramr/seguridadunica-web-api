<div class="row sales layout-top-spacing">

    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{$componentName}} | {{$pageTitle}}</b>
                </h4>
                <ul class="tabs tab-pllis">
                    <li>
                        <a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal">
                            Agregar
                        </a>
                    </li>
                </ul>
            </div>

            @include('common.searchbox')


            <div class="widget-content">

            <div class="table-responsive">
                <table class="table table-bordered table striped mt-1">
                    <thead class="text-white" style="background: #3B3F5C;">
                <th class="table-th text-white text-center">
                    TIPO
                </th>
                <th class="table-th text-white text-center">
                    ESTATUS
                </th>
                <th class="table-th text-white text-center">
                    LOTE
                </th>
                <th class="table-th text-white text-center">
                    DNI
                </th>
                <th class="table-th text-white text-center">
                    AUTORIZADO
                </th>
                <th class="table-th text-white text-center">
                    AUTORIZADO POR
                </th>
                <th class="table-th text-white text-center">
                    DESDE
                </th>
                <th class="table-th text-white text-center">
                    HASTA
                </th>
                <th class="table-th text-white text-center">
                    ACTIONS
                </th>
                </thead>

                <tbody>

                    @foreach ( $data as $autorizacion )

                    <tr>
                        <td class="text-center">
                            <h6>{{$autorizacion->tipo_autorizacion}}</h6>
                        </td>
                        <td class="text-center">
                            <span class="badge {{$autorizacion->aut_activo == 1 ? 'badge-success':'badge-danger'}} text-uppercase">{{ $autorizacion->aut_activo == 1? 'Activo':'Cancelado'}}</span>
                         </td>
                        <td class="text-center">
                            <h6>{{$autorizacion->lot_name}}</h6>
                        </td>
                        <td class="text-center">
                            <h6>{{$autorizacion->aut_documento}}</h6>
                        </td>
                        <td class="text-center">
                            <h6>{{$autorizacion->aut_nombre}}</h6>
                        </td>

                        <td class="text-center">
                            <h6>{{$autorizacion->us_name}}</h6>
                        </td>

                        <td class="text-center">
                           <h6> {{\Carbon\Carbon::parse($autorizacion->aut_desde)->format('d-m-Y')}}</h6>
                        </td>
                        <td class="text-center">
                            <h6> {{\Carbon\Carbon::parse($autorizacion->aut_hasta)->format('d-m-Y')}}</h6>
                         </td>
                        <td>
                     <a href="javascript:void(0)"
                        wire:click.prevent="show({{$autorizacion->aut_id}})"
                            class="btn bnt-dark mtmobile" title="Editar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </a>
                    </td>
                          {{--    <a href="javascript:void(0)"
                        onClick="Confirm('{{$permiso->id}}')"
                        class="btn bnt-dark " title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </a>
                        </td>  --}}
                    </tr>
                    @endforeach

                </tbody>
                </table>


                {{$data->links()}}
            </div>
            </div>
        </div>
    </div>

   @include('livewire.autorizaciones.details')
   @include('livewire.emergencias')

    </div>


    <script>
        document.addEventListener('DOMContentLoaded',function(){


            window.livewire.on('show-modal',msg=>{
                $('#theModal').modal('show')

            });

            window.livewire.on('permiso-added',msg=>{
                $('#theModal').modal('hide');
                noty(msg);
            });

            window.livewire.on('permiso-updated',msg=>{
                $('#theModal').modal('hide');
               // noty(msg);
            });

            window.livewire.on('permiso-deleted',msg=>{
                noty(msg);
            });
            window.livewire.on('permiso-error',msg=>{
                noty(msg);
            });
            window.livewire.on('permiso-exists',msg=>{
                noty(msg);
            });
            window.livewire.on('hide-modal',msg=>{
                $('#theModal').modal('hide');

            });

        });

        function Confirm(id){
            swal({
                title: "CONFIRMAR",
                text: "¿COFIRMAS ELIMINAR EL REGISTRO?",
                type: 'warning',
                showCancelButton: "Cerrar",
                cancelButtonColor:'#ff',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#3B3F5C'
            }).then(function(result){
                if(result.value){
                    window.livewire.emit('deleteRow',id)
                    swal.close()
                }
            })
        }
    </script>

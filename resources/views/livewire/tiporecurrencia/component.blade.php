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
                            ESTATUS
                        </th>
                <th class="table-th text-white text-center">
                    DESCRIPCIÓN
                </th>
                <th class="table-th text-white text-center">
                    ACTIONS
                </th>
                </thead>

                <tbody>

                    @foreach ( $tipos as $tipo )

                    <tr>

                        <td class="text-center">
                            <span class="badge {{$tipo->stp_activo == 1 ? 'badge-success':'badge-danger'}} text-uppercase">{{$tipo->stp_activo  == 1? 'Activo':'Inactivo'}}</span>
                         </td>

                        <td class="text-center">
                           <h6> {{$tipo->stp_descripcion}}</h6>
                        </td>

                        <td class="text-center">
                        <a href="javascript:void(0)"
                        wire:click.prevent="Edit({{$tipo->stp_id}})"
                            class="btn bnt-dark mtmobile" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="javascript:void(0)"
                        onClick="Confirm('{{$tipo->stp_id}}')"
                        class="btn bnt-dark " title="Eliminar">

                        @if ($tipo->stp_activo == 1)
                        <i class="fas fa-trash"></i>
                        @else
                        <i class="fa fa-check"></i>
                        @endif

                        </a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
                </table>


                {{$tipos->links()}}
            </div>
            </div>
        </div>
    </div>

  @include('livewire.tiporecurrencia.form')
    </div>


    <script>
        document.addEventListener('DOMContentLoaded',function(){


            window.livewire.on('show-modal',msg=>{
                $('#theModal').modal('show')

            });

            window.livewire.on('tiporecurrente-added',msg=>{
                $('#theModal').modal('hide');
                noty(msg);
            });

            window.livewire.on('tiporecurrente-updated',msg=>{
                $('#theModal').modal('hide');
               // noty(msg);
            });

            window.livewire.on('tiporecurrente-deleted',msg=>{
                noty(msg);
            });
            window.livewire.on('tiporecurrente-error',msg=>{
                noty(msg);
            });
            window.livewire.on('tiporecurrente-exists',msg=>{
                noty(msg);
            });
            window.livewire.on('hide-modal',msg=>{
                $('#theModal').modal('hide');

            });

        });

        function Confirm(id){
            swal({
                title: "CONFIRMAR",
                text: "¿COFIRMAS DESACTIVAR/ACTIVAR EL REGISTRO?",
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

<div class="row sales layout-top-spacing">

    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{$componentName}} | {{$pageTitle}}</b>
                </h4>

            </div>

            @include('common.searchbox')


            <div class="widget-content">

            <div class="table-responsive">
                <table class="table table-bordered table striped mt-1">
                    <thead class="text-white" style="background: #3B3F5C;">

                <th class="table-th text-white text-center">
                    EMAIL
                </th>
                <th class="table-th text-white text-center">
                    COUNTRY
                </th>
                <th class="table-th text-white text-center">
                    LOTE
                </th>

                <th  class="table-th text-white text-center overflow-ellipsis">
                    TOKEN
                </th>
                <th class="table-th text-white text-center">
                    ACTIONS
                </th>
                </thead>

                <tbody>

                    @foreach ( $devices as $device )

                    <tr>


                        <td class="text-center">
                           <h6> {{$device->email}}</h6>
                        </td>
                        <td class="text-center">
                            <h6> {{$device->co_name}}</h6>
                         </td>
                         <td class="text-center">
                            <h6> {{$device->lot_name}}</h6>
                         </td>
                         <td class="text-center">
                            <h6> {{substr($device->dev_token,-10)}}</h6>
                         </td>
                        <td class="text-center">
                        <a href="javascript:void(0)"
                        wire:click.prevent="Edit({{$device->dev_id}})"
                            class="btn bnt-dark mtmobile" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>

                        </td>
                    </tr>
                    @endforeach

                </tbody>
                </table>


                {{$devices->links()}}
            </div>
            </div>
        </div>
    </div>

  @include('livewire.device.form')
    </div>


    <script>
        document.addEventListener('DOMContentLoaded',function(){


            window.livewire.on('show-modal',msg=>{
                $('#theModal').modal('show')

            });

            window.livewire.on('device-added',msg=>{
                $('#theModal').modal('hide');
                noty(msg);
            });

            window.livewire.on('device-updated',msg=>{
                $('#theModal').modal('hide');
               // noty(msg);
            });

            window.livewire.on('device-deleted',msg=>{
                noty(msg);
            });
            window.livewire.on('device-error',msg=>{
                noty(msg);
            });
            window.livewire.on('device-exists',msg=>{
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

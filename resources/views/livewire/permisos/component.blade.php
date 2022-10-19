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
                    ID
                </th>
                <th class="table-th text-white text-center">
                    DESCRIPCIÓN
                </th>
                <th class="table-th text-white text-center">
                    ACTIONS
                </th>
                </thead>

                <tbody>

                    @foreach ( $permisos as $permiso )

                    <tr>
                        <td class="text-center">
                            <h6>{{$permiso->id}}</h6>
                        </td>

                        <td class="text-center">
                           <h6> {{$permiso->name}}</h6>
                        </td>

                        <td class="text-center">
                        <a href="javascript:void(0)"
                        wire:click.prevent="Edit({{$permiso->id}})"
                            class="btn bnt-dark mtmobile" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="javascript:void(0)"
                        onClick="Confirm('{{$permiso->id}}')"
                        class="btn bnt-dark " title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
                </table>


                {{$permisos->links()}}
            </div>
            </div>
        </div>
    </div>

   @include('livewire.permisos.form')
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

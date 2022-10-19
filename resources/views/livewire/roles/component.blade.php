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
                    DESCRIPCION
                </th>
                <th class="table-th text-white text-center">
                    ACTIONS
                </th>
                </thead>

                <tbody>

                    @foreach ( $roles as $role )

                    <tr>
                        <td class="text-center">
                            <h6>{{$role->id}}</h6>
                        </td>

                        <td class="text-center">
                           <h6> {{$role->name}}</h6>
                        </td>

                        <td class="text-center">
                        <a href="javascript:void(0)"
                        wire:click.prevent="Edit({{$role->id}})"
                            class="btn bnt-dark mtmobile" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="javascript:void(0)"
                        onClick="Confirm('{{$role->id}}')"
                        class="btn bnt-dark " title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
                </table>


                {{$roles->links()}}
            </div>
            </div>
        </div>
    </div>

    @include('livewire.roles.form')
    </div>


    <script>
        document.addEventListener('DOMContentLoaded',function(){


            window.livewire.on('show-modal',msg=>{
                $('#theModal').modal('show')

            });

            window.livewire.on('role-added',msg=>{
                $('#theModal').modal('hide');
                noty(msg);
            });

            window.livewire.on('role-updated',msg=>{
                $('#theModal').modal('hide');
               // noty(msg);
            });

            window.livewire.on('role-deleted',msg=>{
                noty(msg);
            });
            window.livewire.on('role-error',msg=>{
                noty(msg);
            });
            window.livewire.on('role-exists',msg=>{
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

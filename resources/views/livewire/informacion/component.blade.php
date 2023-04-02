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
                    Titulo
                </th>
                <th class="table-th text-white text-center">
                    Cuerpo
                </th>


                <th class="table-th text-white text-center">
                    Country
                </th>


                <th class="table-th text-white text-center">
                    Actions
                </th>
                </thead>

                <tbody>
                    @foreach ($data as $info)
                              <tr>
                        <td>
                            <h6 class="text-center">{{$info->info_titulo}}</h6>
                        </td>
                        <td>
                            <h6 class="text-center">{{ \Str::limit($info->info_body, 40, ' ...')}}</h6>
                        </td>

                        <td>
                            <h6  class="text-center">{{$info->co_name}}</h6>

                        </td>


                        <td class="text-center">
                        <a href="javascript:void(0)"
                        wire:click.prevent="Edit({{$info->info_id}})" class="btn bnt-dark mtmobile" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="javascript:void(0)"
                        onclick="Confirm('{{$info->info_id}}')" class="btn bnt-dark " title="Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
                </table>


                {{$data->links()}}
            </div>
            </div>
        </div>
    </div>

   @include('livewire.informacion.form')
   @include('livewire.emergencias')

    </div>


    <script>
        document.addEventListener('DOMContentLoaded',function(){

            window.livewire.on('show-modal',msg=>{
                $('#theModal').modal('show')

            });

            window.livewire.on('info-added',msg=>{
                $('#theModal').modal('hide');
                noty(msg);
            });

            window.livewire.on('info-updated',msg=>{
                $('#theModal').modal('hide');
               // noty(msg);
            });

            window.livewire.on('info-deleted',msg=>{
                noty(msg);
            });
            window.livewire.on('hide-modal',msg=>{
                $('#theModal').modal('hide');

            });

            window.livewire.on('hidden.bs.modal',msg=>{
                $('.er').css('display','none');
                window.livewire.emit('resetUI',msg);


            });

            $('#theModal').on('hidden.bs.modal', function () {

                window.livewire.emit('resetUI');
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

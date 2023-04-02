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
                    Nombre
                </th>
                <th class="table-th text-white text-center">
                    Tipo
                </th>
                <th class="table-th text-white text-center">
                    Rango en Hrs
                </th>

                <th class="table-th text-white text-center">
                    Country
                </th>
                <th class="table-th text-white text-center">
                    Url
                </th>

                <th class="table-th text-white text-center">
                    Actions
                </th>
                </thead>

                <tbody>
                    @foreach ($data as $treserva)
                              <tr>
                        <td>
                            <h6 class="text-center">{{$treserva->tresr_description}}</h6>
                        </td>
                        <td>
                            <h6 class="text-center">{{$treserva->tipo}}</h6>
                        </td>
                        <td>
                            <h6 class="text-center">{{$treserva->tresr_tipo_horarios}}</h6>
                        </td>
                        <td>
                            <h6  class="text-center">{{$treserva->co_name}}</h6>

                        </td>
                        <td>
                            @if (\Str::contains(strtolower($treserva->tresr_url),'http'))
                            <a target="_blank" href="{{strtolower( $treserva->tresr_url)}}"> <h6>Ir</h6></a>

                            @endif

                        </td>

                        <td class="text-center">
                        <a href="javascript:void(0)"
                        wire:click.prevent="Edit({{$treserva->tresr_id}})" class="btn bnt-dark mtmobile" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="javascript:void(0)"
                        onclick="Confirm('{{$treserva->tresr_id}}')" class="btn bnt-dark " title="Delete">
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

   @include('livewire.tiporeservas.form')
   @include('livewire.emergencias')

    </div>


    <script>
        document.addEventListener('DOMContentLoaded',function(){

            window.livewire.on('show-modal',msg=>{
                $('#theModal').modal('show')

            });

            window.livewire.on('treservas-added',msg=>{
                $('#theModal').modal('hide');
                noty(msg);
            });

            window.livewire.on('treservas-updated',msg=>{
                $('#theModal').modal('hide');
               // noty(msg);
            });

            window.livewire.on('treservas-deleted',msg=>{
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

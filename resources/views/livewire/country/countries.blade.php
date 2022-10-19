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
                        <th class="table-th text-white">
                            LOGO
                        </th>
                <th class="table-th text-white">
                    NOMBRE
                </th>
                <th class="table-th text-white text-center">
                    ESTATUS
                </th>
                <th class="table-th text-white">
                    EMAIL
                </th>
                <th class="table-th text-white">
                    Lotes/Usuarios
                </th>
                <th class="table-th text-white">
                    COMO LLEGAR
                </th>
                <th class="table-th text-white">
                    URL PROPIETARIOS
                </th>
                <th class="table-th text-white">
                    URL GPS
                </th>
                <th class="table-th text-white">
                    URL VIDEOS
                </th>


                <th class="table-th text-white">
                    Actions
                </th>
                </thead>

                <tbody>
                    @foreach ($countries as $country )

                    <tr >

                        <td class="text-center">
                            <span>
                                <img src="{{$country->co_logo}}" alt="imagen de ejemplo" height="70" width="80" class="rounded">

                            </span>
                        </td>

                        <td>
                            <h6>{{$country->co_name}}</h6>
                        </td>
                        <td class="text-center">
                            <span class="badge {{$country->co_active == 1 ? 'badge-success':'badge-danger'}} text-uppercase">{{$country->co_active  == 1? 'Activo':'Inactivo'}}</span>
                         </td>
                        <td>
                            <h6>{{$country->co_email}}</h6>
                        </td>
                        <td>
                            <h6>{{$country->lotes()->count()}}/{{$country->users()}}</h6>
                        </td>
                        <td>
                            @if (\Str::contains(strtolower($country->co_como_llegar),'http'))
                            <a target="_blank" href="{{strtolower( $country->co_como_llegar)}}"> <h6>Ir</h6></a>

                            @endif

                        </td>
                        <td>
                            @if (\Str::contains(strtolower($country->co_reg_url_propietario),'http'))
                            <a target="_blank" href="{{ strtolower($country->co_reg_url_propietario)}}"> <h6>Ir</h6></a>

                            @endif

                        </td>
                        <td>
                            @if (\Str::contains(strtolower($country->co_url_gps),'http'))
                            <a target="_blank" href="{{ strtolower($country->co_url_gps)}}"> <h6>Ir</h6></a>

                            @endif

                        </td>
                        <td>
                            @if (\Str::contains(strtolower($country->co_url_video),'http'))
                            <a target="_blank" href="{{strtolower($country->co_url_video)}}"> <h6>Ir</h6></a>

                            @endif

                        </td>


                        <td class="text-center">
                        <a href="javascript:void(0)" wire:click="Edit({{$country->co_id}})" class="btn bnt-dark mtmobile" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="javascript:void(0)" onclick="Confirm({{$country->co_id}})" class="btn bnt-dark " title="Delete">

                           @if ($country->co_active == 1)
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
                    {{$countries->links()}}


            </div>
            </div>
        </div>
    </div>

    @include('livewire.country.form')
    </div>


    <script>
        document.addEventListener('DOMContentLoaded',function(){

            window.livewire.on('show-modal',msg=>{
                $('#theModal').modal('show')

            });

            window.livewire.on('country-added',msg=>{
                $('#theModal').modal('hide');
                noty(msg);
            });

            window.livewire.on('country-updated',msg=>{
                $('#theModal').modal('hide');
               // noty(msg);
            });

            window.livewire.on('country-deleted',msg=>{
                noty(msg);
            });
            window.livewire.on('hide-modal',msg=>{
                $('#theModal').modal('hide');

            });

            window.livewire.on('hidden.bs.modal',msg=>{
                $('.er').css('display','none');

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

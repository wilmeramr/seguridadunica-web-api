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
                            FOTO
                        </th>
                <th class="table-th text-white">
                    NOMBRE
                </th>
                <th class="table-th text-white text-center">
                    TIPO
                </th>
                <th class="table-th text-white">
                    ULTIMA VACUNACÍON
                </th>

                <th class="table-th text-white">
                    LOTE
                </th>


                <th class="table-th text-white">
                    Actions
                </th>
                </thead>

                <tbody>
                    @foreach ($mascotas as $mascota )

                    <tr >

                        <td class="text-center">
                            <span>
                                <img src="{{$mascota->masc_url_foto}}" alt="imagen de ejemplo" height="70" width="80" class="rounded">

                            </span>
                        </td>

                        <td>
                            <h6>{{$mascota->masc_name}}</h6>
                        </td>

                        <td>
                            <h6>{{$mascota->masc_esp_name}}</h6>
                        </td>
                        <td>
                            <h6>{{$mascota->masc_fecha_vacunacion}}</h6>
                        </td>
                        <td>
                            <h6>{{$mascota->lot_name}}</h6>
                        </td>

                        <td class="text-center">
                            <a href="javascript:void(0)"
                            wire:click.prevent="show({{$mascota->masc_id}})"
                                class="btn bnt-dark mtmobile" title="Ver">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </a>

                        <a href="javascript:void(0)" wire:click="Edit({{$mascota->masc_id}})" class="btn bnt-dark mtmobile" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="javascript:void(0)" onclick="Confirm({{$mascota->masc_id}})" class="btn bnt-dark " title="Eliminar">

                            <i class="fas fa-trash"></i>
                        </a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
                </table>
                    {{$mascotas->links()}}


            </div>
            </div>
        </div>
    </div>
    @include('livewire.mascotas.form')

    @include('livewire.mascotas.details')
    @include('livewire.emergencias')

    </div>


    <script>
        document.addEventListener('DOMContentLoaded',function(){


            $('.tagging').on('change',function(){


                @this.set('masc_autoriza',this.value);
            });

            window.livewire.on('reset-select2',msg=>{
                $('.tagging').val(msg).trigger('change');


            });


            window.livewire.on('show-modal',msg=>{
                $('#theModal').modal('show')

            });

            window.livewire.on('show-modal-detalle',msg=>{
                $('#theModalDetalle').modal('show')

            });

            window.livewire.on('mastoca-added',msg=>{
                $('#theModal').modal('hide');
                noty(msg);
            });

            window.livewire.on('mascota-updated',msg=>{
                $('#theModal').modal('hide');
                noty(msg);
            });

            window.livewire.on('mascota-deleted',msg=>{
                noty(msg);
            });
            window.livewire.on('hide-modal',msg=>{
                $('#theModal').modal('hide');

            });

            window.livewire.on('hidden.bs.modal',msg=>{
                $('.er').css('display','none');

            });
            window.livewire.on('hide-modal-detalle',msg=>{
                $('#theModalDetalle').modal('hide');

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
        function noty(msg, option = 1){
            $snackbar.show({
                text: msg.toUpperCase(),
                actionText:'CERRAR',
                actionTextColor: '#fff',
                backgroundColor: option==1 ? '#3b3f5c': '#e7515a',
                pos: 'top-right'
            });
        }
    </script>

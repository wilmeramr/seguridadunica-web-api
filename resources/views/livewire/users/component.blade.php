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
                <th class="table-th text- text-center ">
                    USUARIO
                </th>
                <th class="table-th text- text-center ">
                    LOTE
                </th>
                <th class="table-th text- text-center">
                    TELÉFONO
                </th>
                <th class="table-th text- text-center">
                    EMAIL
                </th>
                <th class="table-th text- text-center">
                    PERFIL
                </th>
                <th class="table-th text- text-center">
                    ESTATUS
                </th>
                <th class="table-th text- text-center">
                    ACTIONS
                </th>
                </thead>

                <tbody>
                    <tr>
                        @foreach ($data as $r )

                        <td class="text-center">
                            <h6>{{$r->us_name}}</h6>
                        </td>
                        <td class="text-center">
                            <h6>{{$r->lot_name}}</h6>
                        </td>
                        
                        <td class="text-center">
                            <h6>{{$r->us_phone}}</h6>
                        </td>
                        <td class="text-center">
                            <h6>{{$r->email}}</h6>
                        </td>
                        <td class="text-center">
                            <h6>{{$r->roles[0]->name }}</h6>
                        </td>

                        <td class="text-center">
                           <span class="badge {{$r->us_active == 1 ? 'badge-success':'badge-danger'}} text-uppercase">{{ $r->us_active == 1 ? 'Activo':'Bloqueado'}}</span>
                        </td>



                        <td class="text-center">
                        <a href="javascript:void(0)"
                        wire:click ="Edit({{ $r->id}})"
                        class="btn bnt-dark mtmobile" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="javascript:void(0)" class="btn bnt-dark "
                        onclick="Confirm('{{ $r->id }}')"
                        title="Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
                </table>


                {{$data->links() }}
            </div>
            </div>
        </div>
    </div>

   @include('livewire.users.form')
   @include('livewire.emergencias')

    </div>


    <script>
        document.addEventListener('DOMContentLoaded',function(){

            window.livewire.on('user-added',Msg=>{
                $('#theModal').modal('hide')
                noty(Msg);
            });

            window.livewire.on('user-updated',Msg=>{
                $('#theModal').modal('hide')
                noty(Msg);
            });

            window.livewire.on('user-deleted',Msg=>{
                noty(Msg);
            });

            window.livewire.on('hide-modal',Msg=>{
                $('#theModal').modal('hide')

            });
            window.livewire.on('show-modal',Msg=>{
                $('#theModal').modal('show')

            });

            window.livewire.on('user-withsales',Msg=>{
               noty(Msg);

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

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
                            Crear Entrada
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
                            LOTE
                        </th>
                        <th class="table-th text-white">
                            FOTO
                        </th>
                        <th class="table-th text-white">
                            EMPRESA
                        </th>
                        <th class="table-th text-white">
                            FECHA DE ENTRADA
                        </th>
                        <th class="table-th text-white">
                            OBSERVACIÓN
                        </th>

                <th class="table-th text-white">
                    Actions
                </th>
                </thead>

                <tbody>
                    @foreach ($paquetes as $paquete )

                    <tr >
                        <td class="text-center">
                            <h6>{{$paquete->lot_name}}</h6>
                        </td>

                         <td class="text-center">

                            <img src="{{$paquete->paq_foto}}" width="80" height="80" >

                        </td>

                         <td class="text-center">
                            <h6>{{$paquete->empresa_envio}}</h6>
                        </td>

                        <td class="text-center">
                            <h6>{{$paquete->created_at}}</h6>
                        </td>

                        <td>
                            <h6>{{ \Str::limit($paquete->pad_observacion, 40, ' ...')}}</h6>
                        </td>


                        <td class="text-center">
                            <a href="javascript:void(0)"
                            wire:click.prevent="show({{$paquete->paq_id}})"
                                class="btn bnt-dark mtmobile" title="Ver">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </a>


                        </td>

                    </tr>
                    @endforeach

                </tbody>
                </table>
                    {{$paquetes->links()}}

            </div>
            </div>
        </div>
    </div>
    @include('livewire.paquete.form')
    @include('livewire.paquete.details')
    @include('livewire.emergencias')

    </div>


    <script>


        function take_snapshot() {
            Webcam.snap( function(data_uri) {
                //desactivar_camera();
                $(".image-tag").val(data_uri);

               // document.getElementById('img_foto').src = data_uri;
                window.livewire.emit('webcam',data_uri);

                Webcam.reset();
            } );
        }

        function active_camare(){
           window.livewire.emit('clear_ingr_foto','.');
           // Webcam.attach( '#my_camera' );
            document.getElementById("take").style.visibility = "visible";
            document.getElementById("Desactivar").style.visibility = "visible";

        }
        function desactivar_camera(){
           // document.getElementById("take").style.visibility = "hidden";
            //document.getElementById("desactivar").style.visibility = "hidden";
            document.getElementById("my_camera").style.width = 0;
            document.getElementById("my_camera").style.height = 0;

  if( !!document.getElementById('img_foto')){
    document.getElementById('img_foto').style.height = 0;
    document.getElementById('img_foto').style.width = 0;
  }
            window.livewire.emit('desactivar_ingr_foto','.');
            Webcam.reset();


        }
        document.addEventListener('DOMContentLoaded',function(){
           // document.getElementById("take").style.visibility = "hidden";
           // document.getElementById("desactivar").style.visibility = "hidden";

           $('#code').focus();

           $('#theModal').on('shown.bs.modal',function(){

               $('#dni').focus();
           });

            Webcam.set({
                width: 320,
                height: 240,
                image_format: 'jpeg',
                jpeg_quality: 100,
                   constraints:{
                    facingMode: 'environment'
                }
            });

            $('.tagging').on('change',function(){


                @this.set('ingr_autoriza',this.value);
            });

            window.livewire.on('reset-select2',msg=>{
                $('.tagging').val(msg).trigger('change');


            });
            window.livewire.on('upload:finished',msg=>{
                Webcam.attach( '#my_camera' );


            });

            window.livewire.on('activar-camara',msg=>{
                Webcam.attach( '#my_camera' );
            });

            window.livewire.on('desactivar-camara',msg=>{
                Webcam.reset();
            });

            window.livewire.on('show-modal',msg=>{
                $('#theModal').modal('show')

            });

            window.livewire.on('show-modal-detalle',msg=>{
                $('#theModalDetalle').modal('show')

            });

            window.livewire.on('paq-added',msg=>{
                $('#theModal').modal('hide');
                noty(msg);
            });

            window.livewire.on('paq-updated',msg=>{
                $('#theModal').modal('hide');
                noty(msg);
            });

            window.livewire.on('paq-deleted',msg=>{
                noty(msg);
            });
            window.livewire.on('hide-modal',msg=>{
                $('#theModal').modal('hide');

            });

            window.livewire.on('hidden.bs.modal',msg=>{
                $('.er').css('display','none');
                window.livewire.emit('ingr-deleted',msg)


            });
            window.livewire.on('hide-modal-detalle',msg=>{
                $('#theModalDetalle').modal('hide');

            });
            $('#theModal').on('hidden.bs.modal', function () {

                window.livewire.emit('resetUI');

              });


        });

        function Confirm(id){
            swal({
                title: "CONFIRMAR",
                text: "¿COFIRMAS LA SALIDA DEL VISITANTE?",
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

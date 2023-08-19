    <div wire:ignore.self class="modal fade show" id="modalSearchProduct" tabindex="1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">

                    <div class="input-group">
                        <input type="text" wire:model="search" id="modal-search-input" placeholder="Puedes buscar por nombre de lote o nombre de usuario..." class="form-control">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-gp">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>

                </div>
                <div class="modal-body">
                    <div class="row p-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mt-1">
                                <thead class="text-white" style="background: #3B3F5C">
                                    <tr>

                                        <th class="table-th text-left text-white">LOTE</th>
                                        <th class="table-th text-center text-white">NOMBRE USUARIO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($Users as $user)
                                    <tr>

                                        <td>
                                            <div class="text-left">
                                                <h6><b>{{$user->lot_name}}</b></h6>

                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <h6>{{$user->us_name}}</h6>
                                        </td>
                                        <td class="text-center">
                                            <button wire:click.prevent="$emit('scan-code-byid',{{$user->id}})" class="btn btn-dark">
                                                <i class="fas fa-cart-arrow-down mr-1"></i>
                                                Añadir
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">SIN RESULTADOS</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">CERRAR VENTANA</button>
                </div>
            </div>
        </div>
    </div>

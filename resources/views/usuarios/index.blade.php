@extends('layouts.app')


@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">




@endsection

@section('content')

    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Usuarios
            </h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                        @if(session()->has('info'))

                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong> {{ session()->get('info') }}</strong> 
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                               
                            @endif
                        @can('crear-user')
                            <a class="btn btn-warning" href ="{{route('usuarios.create')}}" >Nuevo</a>
                        @endcan
                      
                            <table class ="table table-striped mt-2 responsive nowrap  " style="width:100%" id="users" >
                                <thead style="background-color: #6777ef">
                              
                                <th style="color:#fff;">Nombre</th>
                                <th style="color:#fff;">E-mail</th>
                                <th style="color:#fff;">Country</th>
                                <th style="color:#fff;">Rol</th>
                                <th style="color:#fff;">Acciones</th>
                                </thead>

                                <tbody>
                                    @foreach($usuarios as $usuario)
                                    <tr>
                                 
                                        <td>{{$usuario->name}}</td>
                                        <td>{{$usuario->email}}</td>
                                        <td>{{$usuario->countryname}}</td>
                                        <td>
                                        @if(!empty($usuario->getRoleNames()))
                                        @foreach($usuario->getRoleNames() as $rolNombre)                                       
                                          <h5><span class="badge badge-dark">{{ $rolNombre }}</span></h5>
                                        @endforeach
                                      @endif
                                        </td>
                                        <td>
                                        @can('editar-user')
                                            <a class="btn btn-info" href="{{route('usuarios.edit',$usuario->id)}}">Editar</a>
                                            @endcan
                                        @can('borrar-user')

                                            {!! Form::open(['method'=> 'DELETE','route'=>['usuarios.destroy',$usuario->id],'style'=>'display:inline']) !!}

                                            {!! Form::submit('borrar',['class'=>'btn btn-danger']) !!}

                                            {!! Form::close() !!}
                                            @endcan

                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>

             

                            </table>

                            <div class="pagination justify-content-end">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('dt')
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>


<script>


  $(document).ready(function() {

    $('#users').DataTable(
     
        {
        processing: true,
            
            responsive: true,
            "lengthMenu": [[5, 10, 15], [5, 10, 15]],
            "language": {
                "search":         "Buscar:",
                "loadingRecords": "Cargando...",
                "processing":     "Procesando...",
            "lengthMenu": "Mostrar _MENU_ Usuarios",
            "zeroRecords": "Nothing found - sorry",
            "info": "Mostrando la pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "paginate": {
        "first":      "Primera",
        "last":       "Ultima",
        "next":       "Siguiente",
        "previous":   "Anterior"
    }
        }
    } );
} );
  

});

</script>
@endsection

@extends('layouts.app')


@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Countrys</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                           
                        @can('crear-country')
                        <a class="btn btn-warning" href ="{{route('countrys.create')}}" >Nuevo</a>

                        @endcan

                        <table class ="table table-striped mt-2 responsive nowrap" style="width:100%"  id="country">
                                <thead style="background-color: #6777ef">
                                <th style="color:#fff;">Name</th>
                                <th style="color:#fff;">cuit</th>
                                <th style="color:#fff;">email</th>
                                <th style="color:#fff;">active</th>

                                <th style="color:#fff;">Acciones</th>
                                </thead>

                                <tbody>
                                    @foreach($countrys as $country)
                                    <tr>
                                        <td>{{$country->name}}</td>
                                        <td>{{$country->cuit}}</td>
                                        <td>{{$country->email}}</td>
                                        <td>{{$country->active}}</td>

                                        <td>
                                            @can('editar-Country');
                                            <a class="btn btn-primary" href="{{route('countrys.edit',$country->id)}}">Editar</a>
                                            @endcan

                                            @can('borrar-country')
                                            {!! Form::open(['method'=> 'DELETE','route'=>['countrys.destroy',$country->id],'style'=>'display:inline']) !!}

                                            {!! Form::submit('borrar',['class'=>'btn btn-danger']) !!}

                                            {!! Form::close() !!}

                                            @endcan
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>

                            <div class="pagination justify-content-end">
                                {!! $countrys->links() !!}

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
    $('#country').DataTable(
     
        {
            responsive: true,
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
  

</script>
@endsection

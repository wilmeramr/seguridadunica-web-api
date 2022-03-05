@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Alta de Usuario</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                           
                            @if($errors->any())
                            <div class="alert alert-dark alert-dismissible fade show" role="alert">
                                <strong>Revise los campos!</strong>
                                @foreach($errors->all() as $error)
                                <span class="badge badge-danger">{{$error}}}</span>
                                @endforeach
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"> &times;</span>
                                </button>

                            </div>
                            @endif

                            {!! Form::open(array('route'=>'usuarios.store','method'=>'POST')) !!}

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label For="name">Nombre</label>
                                        {!! Form::text('name',null,array('class'=>'form-control')) !!}
                                    </div>
                                </div>
                            
                           
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label For="email">E-mail</label>
                                        {!! Form::text('email',null,array('class'=>'form-control')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label For="country">Country</label>
                                        {!!  Form::select('country_id', $data, null, [ 'class' => 'form-control']) !!}
                                       
                                    </div>
                                </div>
                            
                          
                          
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label For="password">Password</label>
                                        {!! Form::text('password',null,array('class'=>'form-control')) !!}
                                    </div>
                                </div>
                          
                       
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label For="confirm-password">Confirmar Password</label>
                                        {!! Form::text('confirm-password',null,array('class'=>'form-control')) !!}
                                    </div>
                                </div>
                           
                         
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label For="">Roles</label>
                                        {!! Form::select('roles[]',$roles,[],array('class'=>'form-control')) !!}
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </div>

                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


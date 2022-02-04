@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Añadir usuario</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Información</div>
                    </div>
                    <div class="card-body">
                        <form action="#" method="post" role="form" class="form-horizontal">
                            @csrf
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Nombre</label>
                                <input value="{{ $user->name }}" name="name" type="text" placeholder="Nombre..." class="form-control col-md-10" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Email</label>
                                <input value="{{ $user->email }}" name="email" type="email" placeholder="Email..." class="form-control col-md-10" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Teléfono</label>
                                <input value="{{ $user->tlfno }}" name="tlfno" type="text" placeholder="Teléfono..." class="form-control col-md-10">
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Cambiar contraseña</label>
                                <input name="password" type="password" placeholder="Contraseña..." class="form-control col-md-10">
                            </div>
                            <input type="hidden" name="id_instalacion" value="{{ $instalacion->id }}">
                            <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">Editar</button>
                        </form>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>

        </div>
    </div>
@endsection

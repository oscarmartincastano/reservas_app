@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Editar usuario</h3>
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
                                <input value="{{ $user->name }}" name="name" type="text" placeholder="Nombre..."
                                    class="form-control col-md-10" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Email</label>
                                <input value="{{ $user->email }}" name="email" type="email" placeholder="Email..."
                                    class="form-control col-md-10" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Dirección</label>
                                <input value="{{ $user->direccion }}" name="direccion" type="text"
                                    placeholder="Dirección..." class="form-control col-md-10" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Cuota (opcional)</label>
                                <input value="{{ $user->cuota }}" name="cuota" type="text" placeholder="Cuota..."
                                    class="form-control col-md-10">
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Fecha de nacimiento (opcional)</label>
                                <input value="{{ $user->date_birth }}" name="date_birth" type="date"
                                    placeholder="Fecha de nacimiento..." class="form-control col-md-10">
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Teléfono (opcional)</label>
                                <input value="{{ $user->tlfno }}" name="tlfno" type="text" placeholder="Teléfono..."
                                    class="form-control col-md-10">
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Cambiar contraseña</label>
                                <input name="password" type="password" placeholder="Contraseña..."
                                    class="form-control col-md-10">
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 control-label">Código postal</label>
                                <input name="postal_code" type="text" placeholder="Código postal..."
                                    value="{{ $user->postal_code }}" class="form-control col-md-10">
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 control-label">Localidad</label>
                                <input name="city" type="text" placeholder="Ciudad..." value="{{ $user->city }}"
                                    class="form-control col-md-10">
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 control-label">Canal publicitario</label>
                                <select name="canal_publicitario" class="form-control col-md-10">
                                    @foreach (App\Models\User::$CANALES_PUBLICITARIOS as $canal_publicitario)
                                        <option value="{{ $canal_publicitario }}"
                                            {{ $user->canal_publicitario == $canal_publicitario ? 'selected' : '' }}>
                                            {{ $canal_publicitario }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 control-label">Datos deportivos</label>
                                <textarea name="datos_deportivos" class="form-control col-md-10" rows="3">{{ $user->datos_deportivos }}</textarea>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 control-label">Notas generales</label>
                                <textarea name="notas_generales" class="form-control col-md-10" rows="3">{{ $user->notas_generales }}</textarea>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 control-label">Estado</label>
                                <select name="estado" class="form-control col-md-10">
                                    @foreach (App\Models\User::$ESTADOS as $estado)
                                        <option value="{{ $estado }}"
                                            {{ $user->estado == $estado ? 'selected' : '' }}>{{ $estado }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 control-label">Reservas máximas para cada tipo de espacio</label>
                                <div class="col-md-10 border p-3">
                                    @foreach ($instalacion->deportes as $tipo_espacio)
                                        <label
                                            for="max_reservas_tipo_espacio[{{ $tipo_espacio }}]">{{ $tipo_espacio }}</label>
                                        <input type="number" class="form-control"
                                            name="max_reservas_tipo_espacio[{{ $tipo_espacio }}]"
                                            id="max_reservas_tipo_espacio[{{ $tipo_espacio }}]"
                                            value="{{ unserialize($user->max_reservas_tipo_espacio)[$tipo_espacio] ?? (unserialize($instalacion->configuracion->max_reservas_tipo_espacio)[$tipo_espacio] ?? '') }}">
                                    @endforeach
                                </div>
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

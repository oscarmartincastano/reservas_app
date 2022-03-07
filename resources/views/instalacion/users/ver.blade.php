@extends('layouts.admin')

@section('style')
    <style>
        .list-group-item:not(:first-child) {
            border-top: 1px solid rgba(0,0,0,.125);
        }   
        .card-title{
            font-size: 16px !important;
        }
    </style>    
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">{{ $user->name }}</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="card card-default">
                            <div class="card-header  separator">
                                <div class="card-title">Información</div>
                            </div>
                            <div class="card-header border-bottom text-center">
                                <div class="mb-3 mx-auto">
                                    <a href="/{{ request()->slug_instalacion }}/admin/users/{{ $user->id }}/cambiar-foto" style="opacity: 1">
                                        <img class="rounded-circle" src="{{ asset('img/assets/user-default.png') }}" alt="User Avatar" width="110">
                                    </a>
                                </div>
                                <h4 class="mb-0">{{ $user->name }}</a></h4>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-4"><strong>Email:</strong> {{ $user->email }}</li>
                                    @if ($user->date_birth) <li class="list-group-item px-4"><strong>Fecha de nacimiento:</strong> {{ $user->date_birth }}</li> @endif
                                    @if ($user->tlfno) <li class="list-group-item px-4"><strong>Teléfono:</strong> {{ $user->tlfno }}</li> @endif
                                    @if ($user->cuota) <li class="list-group-item px-4"><strong>Cuota:</strong> {{ $user->cuota }}</li> @endif
                                    <li class="list-group-item px-4"><strong>Fecha de alta:</strong> {{ date('d/m/Y', strtotime($user->aprobado)) }}</li>
                                    <li class="list-group-item px-4"><strong>Baja:</strong> {{ $user->deleted_at ?? 'Cliente activo' }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="card card-default">
                            <div class="card-header  separator">
                                <div class="card-title">Reservas realizadas</div>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover table-condensed">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Horas</th>
                                            <th>Espacio</th>
                                            <th>Estado de la reserva</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->reservas as $item)
                                            <tr>
                                                <td>{{ date('d/m/Y', $item->timestamp) }}</td>
                                                <td>{{ date('H:i', $item->timestamp) }} - {{ date('H:i', strtotime(date('H:i', $item->timestamp) . " +{$item->minutos_totales} minutes")) }}</td>
                                                <td>{{ count(auth()->user()->instalacion->deportes) > 1 ? $item->pista->tipo . '.' : '' }} {{ $item->pista->nombre }}</td>
                                                <td>{{ $item->estado > 'canceled' ? 'Cancelado' : ($item->estado == 'active' ? 'Pendiente' : 'Pasado') }}</td>
                                                <td>
                                                    @if ($item->estado  == 'active' && strtotime(strtotime(date('Y-m-d H:i', $item->timestamp)) . ' +' . $item->minutos_totales . ' minutes') < strtotime(date('Y-m-d H:i')))
                                                        <a class="cancel btn btn-primary text-white" title="Cancelar reserva">
                                                            Acción
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    {{-- <div class="col-lg-12">
                        <div class="card card-default">
                            <div class="card-header  separator">
                                <div class="card-title">Reservas máximas permitidas para este cliente</div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="border p-3">
                                        @foreach (auth()->user()->instalacion->deportes as $tipo_espacio)
                                            <label for="max_reservas_tipo_espacio[{{ $tipo_espacio }}]">{{ $tipo_espacio }}</label>
                                            <input type="number" class="form-control" name="max_reservas_tipo_espacio[{{ $tipo_espacio }}]" id="max_reservas_tipo_espacio[{{ $tipo_espacio }}]"
                                                value="{{ unserialize($user->max_reservas_tipo_espacio)[$tipo_espacio] ?? (unserialize(auth()->user()->instalacion->configuracion->max_reservas_tipo_espacio)[$tipo_espacio] ?? '') }}">
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-lg-12">
                        <div class="card card-default">
                            <div class="card-header  separator">
                                <div class="card-title">Cobros</div>
                            </div>
                            <div class="card-body">
                                <a href="/{{ request()->slug_instalacion }}/admin/users/{{ request()->id }}/cobro/add" class="btn btn-primary">Añadir cobro</a>
                                <table class="table table-hover table-condensed">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Concepto</th>
                                            <th>Forma de cobro</th>
                                            <th>Cantidad</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->cobros as $item)
                                            <tr>
                                                <td>{{ $item->fecha }}</td>
                                                <td>{{ $item->concepto }}</td>
                                                <td>{{ $item->forma }}</td>
                                                <td>{{ $item->cantidad }} €</td>
                                                <td><a href="/{{ request()->slug_instalacion }}/admin/cobro/{{ $item->id }}" class="btn btn-primary"><i class="fas fa-edit"></i></a> <a href="/{{ request()->slug_instalacion }}/admin/cobro/{{ $item->id }}/delete" class="btn btn-danger"><i class="fas fa-trash"></i></a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>

        </div>
    </div>
@endsection

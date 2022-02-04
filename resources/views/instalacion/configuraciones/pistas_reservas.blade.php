@extends('layouts.admin')

@section('style')
    <style>
        .col-form-label {
            font-weight: bold;
        }
        thead td{
            font-weight: bold;
        }
        td{
            padding: .75rem !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Configuraciones</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Pistas y Reservas</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('edit_config', ['slug_instalacion' => $instalacion->slug]) }}" method="post">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="num_reservas_por_user">Reservas activas permitidas por usuario</label>
                                <input type="number" class="form-control" name="num_reservas_por_user" id="num_reservas_por_user"
                                    value="{{ $instalacion->configuracion->num_reservas_por_user }}">
                            </div>
                            
                            {{-- <div class="form-group mb-4">
                                <label for="num_reservas_por_user">Quiénes pueden reservar</label>
                                <select class="form-control form-control-lg">
                                    <option>Usuario</option>
                                    <option>Invitado</option>
                                </select>
                            </div> --}}
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="1" id="observaciones" name="observaciones" @if ($instalacion->configuracion->observaciones) checked @endif>
                                <label class="form-check-label" for="observaciones">
                                    Añadir campo de observaciones en la reserva
                                </label>
                            </div>
                            {{-- <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="1" id="allow_cancel" name="allow_cancel" @if ($instalacion->configuracion->allow_cancel) checked @endif>
                                <label class="form-check-label" for="allow_cancel">
                                    Permitir a usuarios editar nombre y email
                                </label>
                            </div> --}}
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="1" id="allow_cancel" name="allow_cancel" @if ($instalacion->configuracion->allow_cancel) checked @endif>
                                <label class="form-check-label" for="allow_cancel">
                                    Permitir cancelar reservas
                                </label>
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="1" id="block_today" name="block_today" @if ($instalacion->configuracion->block_today) checked @endif>
                                <label class="form-check-label" for="block_today">
                                    Bloquear el día de hoy
                                </label>
                            </div>
                            <div class="form-group mb-4">
                                <label for="num_reservas_por_user">Campos personalizados en la reserva</label>
                                <div class="border p-3">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td>Tipo</td>
                                                <td>Nombre</td>
                                                <td style="width: 15%">#</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($instalacion->campos_personalizados as $campo)
                                                <tr>
                                                    <td style="text-transform: capitalize">{{ $campo->tipo }}</td>
                                                    <td>{{ $campo->label }}</td>
                                                    <td><a href="#" class="btn btn-primary"><i class="fas fa-edit"></i></a> <a href="#" class="btn btn-danger"><i class="fas fa-trash"></i></a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3"><a href="/{{ auth()->user()->instalacion->slug }}/admin/configuracion/pistas-reservas/campos-personalizados" class="btn btn-primary"><i class="fas fa-plus mr-2"></i> Añadir campo</a></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <input type="submit" value="Editar" class="btn btn-primary btn-lg m-b-10 mt-3 mt-2">
                        </form>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>
        </div>
    </div>
@endsection

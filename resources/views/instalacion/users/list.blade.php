@extends('layouts.admin')

@section('style')
    <style>
        .clickable {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">{{ auth()->user()->instalacion->nombre }}</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Listado de usuarios</div>
                    </div>
                    <div class="card-body">

                        @if (count(auth()->user()->instalacion->users_sin_validar))
                            <a href="/{{ request()->slug_instalacion }}/admin/users/novalid" class="btn btn-info"
                                style="padding-right: 40px">Usuarios no aprobados @if (count(auth()->user()->instalacion->users_sin_validar))
                                    <mark class="mark"
                                        style="border: 0; top:2px;left:172px;">{{ count(auth()->user()->instalacion->users_sin_validar) }}</mark>
                                @endif
                            </a>
                        @endif
                        <a href="/{{ request()->slug_instalacion }}/admin/users/add"
                            class="text-white btn btn-primary">Añadir nuevo</a>
                        <table class="table table-hover" id="table-users">
                            <thead>
                                <tr>1
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Direccion</th>
                                    <th>Rol</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($instalacion->users as $item)
                                    @if ($item->aprobado)
                                        <tr class="clickable"
                                            data-href="/{{ request()->slug_instalacion }}/admin/users/{{ $item->id }}/ver">
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->direccion }}</td>
                                            <td>
                                                @if ($item->rol == 'admin')
                                                    <span class="badge badge-primary">Admin</span>
                                                @elseif ($item->rol == 'user')
                                                    <span class="badge badge-success">Cliente</span>
                                                @elseif ($item->rol == 'worker')
                                                    <span class="badge badge-warning">Entrenador</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex" style="gap:5px">
                                                    <a href="/{{ $instalacion->slug }}/admin/users/{{ $item->id }}/ver"
                                                        class="btn btn-info"><i data-feather="eye"></i></a>

                                                    @if (auth()->user()->rol == 'admin' || $item->rol == 'user' || $item->id == auth()->user()->id)
                                                        <a href="/{{ $instalacion->slug }}/admin/users/{{ $item->id }}"
                                                            class="btn btn-primary"><i data-feather="edit"></i></a>
                                                    @endif

                                                    @if ((auth()->user()->rol == 'admin' && auth()->user()->id != $item->id) || $item->rol == 'user')
                                                        <a href="/{{ $instalacion->slug }}/admin/users/{{ $item->id }}/desactivar"
                                                            class="btn-activate btn {{ !$item->deleted_at ? 'btn-danger' : 'btn-success' }}"
                                                            onclick="return confirm('¿Estás seguro que quieres {{ !$item->deleted_at ? 'desactivar' : 'activar' }} este usuario?');"
                                                            title="{{ !$item->deleted_at ? 'Desactivar' : 'Activar' }} usuario"><i
                                                                data-feather="power"></i></a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#table-users').DataTable({
                "info": false,
                "paging": false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [
                    [3, "asc"]
                ]
            });

            $('.clickable').click(function(e) {
                if (e.target.tagName == 'TD') {
                    window.location = $(this).data("href");
                }
            });
        });
    </script>
@endsection

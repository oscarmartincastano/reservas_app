@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12 m-b-10">
        
            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Reservas</h3>
                </div>
            </div>
            
            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Reservas periódicas</div>
                    </div>
                    <div class="card-body">
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/periodicas/add" class="btn btn-primary text-white mb-2">Añadir reserva periódica</a> 
                        <table class="table table-hover" id="table-reservas">
                            <thead>
                                <tr>
                                    @if(auth()->user()->id_instalacion != 2)
                                        <th>Usuario</th>
                                    @else
                                        <th>Organización</th>
                                    @endif
                                    <th>Espacio</th>
                                    <th>Fecha inicio</th>
                                    <th>Fecha fin</th>
                                    <th>Día de la semana</th>
                                    <th>Horas</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservas_periodicas as $item)
                                    @if (strtotime(date('Y-m-d')) < strtotime($item->fecha_fin))
                                        <tr>
                                            @if(auth()->user()->id_instalacion != 2)
                                            <td><a href="/{{ request()->slug_instalacion }}/admin/users/{{ $item->user->id }}/ver">{{ $item->user->name }}</a></td>
                                            @else
                                            <td>{{ \App\Models\Reserva::where('reserva_periodica', $item->id)->first()->valor_nombre_reunion }}</td>
                                            @endif
                                            <td>{{ count(auth()->user()->instalacion->deportes) > 1 ? $item->pista->tipo . '.' : '' }} {{ $item->pista->nombre }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->fecha_inicio)) }}</td>
                                            <td>{{ date('d/m/Y', strtotime($item->fecha_fin)) }}</td>
                                            <td>
                                                @foreach (unserialize($item->dias) as $index => $dia)
                                                    @switch($dia)
                                                        @case(0)
                                                            Domingo
                                                            @break
                                                        @case(1)
                                                            Lunes
                                                            @break
                                                        @case(2)
                                                            Martes
                                                            @break
                                                        @case(3)
                                                            Miércoles
                                                            @break
                                                        @case(4)
                                                            Jueves
                                                            @break
                                                        @case(5)
                                                            Viernes
                                                            @break
                                                        @case(6)
                                                            Sábado
                                                            @break
                                                        @default
                                                    @endswitch
                                                    {{ $index != count(unserialize($item->dias))-1 ? '|' : '' }} 
                                                @endforeach
                                            </td>
                                            <td>{{ $item->hora_inicio }} - {{ $item->hora_fin }}</td>
                                            <td>
                                                <a class="btn btn-primary" href="/{{request()->slug_instalacion}}/admin/reservas/periodicas/{{ $item->id }}/editar"><i class="fas fa-edit"></i></a>
                                                <a href="/{{ request()->slug_instalacion }}/admin/reservas/periodicas/{{ $item->id }}/borrar" onclick="return confirm('¿Estás seguro que quieres borrar todas estas reservas periódicas?');" class="btn btn-danger text-white" title="Borrar estas reservas periódicas">
                                                    <i class="fas fa-trash"></i>
                                                </a>
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
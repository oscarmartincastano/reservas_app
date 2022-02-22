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
                        <div class="card-title">Desactivaciones periódicas</div>
                    </div>
                    <div class="card-body">
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/desactivaciones/add" class="btn btn-primary text-white mb-2">Añadir desactivación periódica</a> 
                        <table class="table table-condensed table-hover" id="table-reservas">
                            <thead>
                                <tr>
                                    <td>Espacio</td>
                                    <td>Fecha inicio</td>
                                    <td>Fecha fin</td>
                                    <td>Día de la semana</td>
                                    <td>Horas</td>
                                    <td>#</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($desactivaciones as $item)
                                    <tr>
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
                                            <a class="btn btn-primary text-white" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
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
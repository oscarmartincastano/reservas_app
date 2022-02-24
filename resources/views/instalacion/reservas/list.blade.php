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
                        <div class="card-title">Listado de reservas</div>
                    </div>
                    <div class="card-body">
                        {{-- <a href="/{{ request()->slug_instalacion }}/admin/reservas/add" class="btn btn-outline-primary mr-2">Añadir desactivación periódica</a> 
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/add" class="text-white btn btn-primary">Añadir reserva periódica</a> --}}
                        <table class="table table-condensed table-hover" id="table-reservas">
                            <thead>
                                <tr>
                                    <th>Fecha de alquiler</th>
                                    <th>Horas</th>
                                    <th>Día de la semana</th>
                                    <th>Espacio</th>
                                    <th>Estado reserva</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservas as $item)
                                    <tr>
                                        <td>{{ date('d/m/Y', $item->timestamp) }}</td>
                                        <td>{{ date('H:i', $item->timestamp) }} - {{ date('H:i', strtotime(date('H:i', $item->timestamp) . " +{$item->minutos_totales} minutes")) }}</td>
                                        <td style="text-transform:capitalize">{{ \Carbon\Carbon::parse($item->timestamp)->formatLocalized('%A') }}</td>
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
                <p class="small no-margin">
                </p>
            </div>
        
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#table-reservas').DataTable({
                "info": false,
                "paging": false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [[ 5, "desc" ], [ 0, "desc"], [ 2, "desc"]]
            });
        });
    </script>
@endsection
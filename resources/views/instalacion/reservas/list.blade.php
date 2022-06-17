@extends('layouts.admin')

@section('content')
    <div class="modal fade slide-up disable-scroll" id="modalSlideUp" tabindex="-1" role="dialog" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content-wrapper">
                <div class="modal-content">
                    <div class="modal-header clearfix text-left">
                        <button aria-label="" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                class="pg-icon">close</i>
                        </button>
                        <h5>Reserva <span class="text-capitalize"></span></h5>
                        <p class="p-b-10 user"><strong>Usuario: </strong><span class="text-capitalize"></span></p>
                    </div>
                    <div class="modal-body">
                        <form role="form" method="POST" action="#">
                            @csrf

                            <div class="row">
                                <div class="col-md-7">
                                </div>
                                <div class="col-md-5 m-t-10 sm-m-t-10 text-right">
                                    <input type="hidden" name="accion">
                                    <button type="submit" data-accion="canceled"
                                        class="submit-form-validar btn btn-danger m-t-5 mr-2">Cancelarla</button>
                                    <button type="submit" data-accion="pasado"
                                        class="submit-form-validar btn btn-success m-t-5">Validarla</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    </div>
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">
                        @if (request()->fecha)
                            @switch(request()->fecha)
                                @case('all')
                                    Todas las reservas
                                    @break
                                @case('today')
                                    Reservas del día de hoy: {{ date('d/m/Y', strtotime(date('Y-m-d'))) }}
                                    @break
                                @case('week')
                                    Reservas de esta semana: {{ date('d/m/Y', strtotime("monday -1 week")) }} - {{ date('d/m/Y', strtotime("sunday 0 week")) }}
                                    @break
                                @case('month')
                                    Reservas de este mes: {{ date('d/m/Y', strtotime(date("Y-m", strtotime(date('Y-m-d'))) . '-01')) }} - {{ date('d/m/Y', strtotime(date("Y-m-t", strtotime(date('Y-m-d'))))) }}
                                    @break
                                @default
                                    @if(auth()->user()->instalacion->id == 2)
                                        Todas las reservas
                                    @else
                                        Reservas del día de hoy
                                    @endif
                            @endswitch
                        @else
                            @if(auth()->user()->instalacion->id == 2)
                                Todas las reservas
                            @else
                                Reservas del día de hoy
                            @endif
                        @endif
                    </h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Listado de reservas</div>
                    </div>
                    <div class="card-body table-responsive">
                        @if(request()->fecha != 'all')<a href="?fecha=all">Todas</a> -@endif @if(request()->fecha != 'today')<a href="?fecha=today">Hoy</a> -@endif @if(request()->fecha != 'week')<a href="?fecha=week">Semana</a> -@endif @if(request()->fecha != 'month')<a href="?fecha=month">Mes</a>@endif
                        {{-- <a href="/{{ request()->slug_instalacion }}/admin/reservas/add" class="btn btn-outline-primary mr-2">Añadir desactivación periódica</a> 
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/add" class="text-white btn btn-primary">Añadir reserva periódica</a> --}}
                        <table class="table table-hover" id="table-reservas">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Cliente</th>
                                    <th>Fecha de alquiler</th>
                                    <th>Horas</th>
                                    {{-- <th>Día de la semana</th> --}}
                                    {{-- @if(count(auth()->user()->instalacion->deportes) > 1) --}}<th>Espacio</th>{{-- @endif --}}
                                    <th>Estado reserva</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservas as $item)
                                    <tr>
                                        <td>#{{ $item->id }}</td>
                                        <td><a @if ($item->estado == 'active' && strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i'))) data-intervalo="{{ \Carbon\Carbon::parse($item->timestamp)->formatLocalized('%A') }}, {{ date('d/m/Y', $item->timestamp) }} <br> {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i') }} - {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}"
                                            data-reserva="{{ $item->id }}"
                                            data-user="{{ $item->user->name ?? '' }}" @endif href="#" @if ($item->estado == 'active' && strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i'))) class="btn-accion-reserva" @endif>{{ $item->user->name ?? '' }}</a>
                                        </td>
                                        <td data-order="{{ $item->timestamp }}">{{ date('d/m/Y', $item->timestamp) }}</td>
                                        <td>{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i') }} -
                                            {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}
                                        </td>
                                        {{-- <td style="text-transform:capitalize">
                                            {{ \Carbon\Carbon::parse($item->timestamp)->formatLocalized('%A') }}</td> --}}
                                        {{-- @if(count(auth()->user()->instalacion->deportes) > 1) --}}<td>{{ count(auth()->user()->instalacion->deportes) > 1 ? $item->pista->tipo . '.' : '' }}
                                            {{ $item->pista->nombre }}</td> {{-- @endif --}}
                                        <td class="text-uppercase">
                                            @if ($item->estado == 'active')
                                                @if (strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i')))
                                                    Pendiente
                                                @else
                                                    Pasado
                                                @endif
                                            @endif
                                            @if ($item->estado == 'canceled')
                                                <span class="text-danger">Cancelada</span>
                                            @endif
                                            @if ($item->estado == 'pasado')
                                                <span style="color: green">Validada</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex" style="gap:5px">
                                                @if ($item->estado == 'active' && strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i')))
                                                    <a class="cancel btn btn-primary text-white btn-accion-reserva"
                                                        data-intervalo="{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i') }} - {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}"
                                                        data-reserva="{{ $item->id }}"
                                                        data-user="{{ $item->user->name ?? '' }}">
                                                        Acción
                                                    </a>
                                                @endif
                                                @if(auth()->user()->instalacion->id == 2)
                                                    <a href="{{ route('reserva.edit', ['slug_instalacion' => request()->slug_instalacion, 'id' => $item->id]) }}" class="btn btn-success">
                                                        <i class="fa-solid fa-edit"></i>
                                                    </a>
                                                @endif
                                            </div>
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
        $(document).ready(function() {
            $('#table-reservas').DataTable({
                "info": false,
                "paging": false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [
                    [2, "desc"]
                ]
            });

            $('.btn-accion-reserva').click(function(e) {
                e.preventDefault();
                let modal = $('#modalSlideUp');
                modal.modal('show').find('h5 span').html(
                    `#${$(this).data('reserva')}: ${$(this).data('intervalo')}`);
                modal.find('.user span').html($(this).data('user'));
                modal.find('form').attr('action',
                    `/{{ request()->slug_instalacion }}/admin/reservas/validar/${$(this).data('reserva')}`
                );
            });

            $('#modalSlideUp').on('click', '.submit-form-validar', function(e) {
                e.preventDefault();
                $(this).parent().find('input').val($(this).data('accion'));
                $('#modalSlideUp').find('form').submit();
            });
        });
    </script>
@endsection

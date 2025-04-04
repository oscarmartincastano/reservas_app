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
                        @if (request()->fecha_inicio && request()->fecha_fin)
                            Reservas del {{ date('d/m/Y', strtotime(request()->fecha_inicio)) }} al {{ date('d/m/Y', strtotime(request()->fecha_fin)) }}
                        @elseif (request()->fecha)
                            @switch(request()->fecha)
                                @case('all')
                                    Todas las reservas @if(request()->periodicas) periódicas @endif
                                    @break
                                @case('today')
                                    Reservas @if(request()->periodicas) periódicas @endif del día de hoy: {{ date('d/m/Y', strtotime(date('Y-m-d'))) }}
                                    @break
                                @case('week')
                                    Reservas @if(request()->periodicas) periódicas @endif de esta semana: {{ date('d/m/Y', strtotime("monday -1 week")) }} - {{ date('d/m/Y', strtotime("sunday 0 week")) }}
                                    @break
                                @case('month')
                                    Reservas @if(request()->periodicas) periódicas @endif de este mes: {{ date('d/m/Y', strtotime(date("Y-m", strtotime(date('Y-m-d'))) . '-01')) }} - {{ date('d/m/Y', strtotime(date("Y-m-t", strtotime(date('Y-m-d'))))) }}
                                    @break
                                @default
                                    @if(auth()->user()->instalacion->id == 2)
                                        Próximas reservas @if(request()->periodicas) periódicas @endif
                                    @else
                                        Reservas @if(request()->periodicas) periódicas @endif del día de hoy
                                    @endif
                            @endswitch
                        @else
                            @if(auth()->user()->instalacion->id == 2)
                                Próximas reservas @if(request()->periodicas) periódicas @endif
                            @else
                                Reservas @if(request()->periodicas) periódicas @endif del día de hoy
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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                @if(request()->fecha != 'all')<a href="?fecha=all">Todas</a> -@endif @if(request()->fecha != 'today')<a href="?fecha=today">Hoy</a> -@endif @if(request()->fecha != 'week')<a href="?fecha=week">Semana</a> -@endif @if(request()->fecha != 'month')<a href="?fecha=month">Mes</a> @endif
                            </div>
                            <br>
                            {{-- boton abre moda con form fecha inicio y fecha fin --}}
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalFiltrarFecha">
                                Filtrar por fecha
                            </button>
                            <div class="modal fade" id="modalFiltrarFecha" tabindex="-1" role="dialog" aria-labelledby="modalFiltrarFechaLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalFiltrarFechaLabel">Filtrar por fecha</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="">
                                                <div class="form-group mt-2">
                                                    <label for="fecha_inicio">Fecha inicio</label>
                                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ request()->fecha_inicio ?? date('Y-m-d') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="fecha_fin">Fecha fin</label>
                                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ request()->fecha_fin ?? date('Y-m-d') }}">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- <a href="/{{ request()->slug_instalacion }}/admin/reservas/add" class="btn btn-outline-primary mr-2">Añadir desactivación periódica</a> 
                        <a href="/{{ request()->slug_instalacion }}/admin/reservas/add" class="text-white btn btn-primary">Añadir reserva periódica</a> --}}
                        <table class="table table-hover" id="table-reservas">
                   
                            <thead>
                                <tr>
                                    @if(auth()->user()->id_instalacion != 2)
                                    <th>id</th>
                                    <th>Cliente</th>
                                    @else
                                    <th>Nombre reunión</th>
                                    @endif
                                    <th>Fecha de alquiler</th>
                                    <th>Horas</th>
                                    {{-- <th>Día de la semana</th> --}}
                                    {{-- @if(count(auth()->user()->instalacion->deportes) > 1) --}}<th>Espacio</th>{{-- @endif --}}
                                    @if(auth()->user()->id_instalacion != 2)
                                    <th>Estado reserva</th>
                                    @else
                                    <th>Organización</th>
                                    <th>Presupuesto</th>
                                    @endif
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservas as $item)
                                    <tr>
                                        @if(auth()->user()->id_instalacion != 2)
                                        <td data-order="{{ $item->timestamp }}">#{{ $item->id }}</td>
                                        <td><a @if ($item->estado == 'active' && strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i'))) data-intervalo="{{ \Carbon\Carbon::parse($item->timestamp)->formatLocalized('%A') }}, {{ date('d/m/Y', $item->timestamp) }} <br> {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i') }} - {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}"
                                            data-reserva="{{ $item->id }}"
                                            data-user="{{ $item->user->name ?? '' }}" @endif href="#" @if ($item->estado == 'active' && strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i'))) class="btn-accion-reserva" @endif>{{ $item->user->name ?? '' }}</a>
                                        </td>
                                        @else
                                        <td data-order="{{ $item->timestamp }}">@if($item->reserva_periodica)<i class="fa-solid fa-repeat mr-2"></i> @endif {{ $item->valor_nombre_reunion }}</td>
                                        @endif
                                        <td data-order="{{ $item->timestamp }}">{{ date('d/m/Y', $item->timestamp) }}</td>
                                        <td>{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i') }} -
                                            {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}
                                        </td>
                                        {{-- <td style="text-transform:capitalize">
                                            {{ \Carbon\Carbon::parse($item->timestamp)->formatLocalized('%A') }}</td> --}}
                                        {{-- @if(count(auth()->user()->instalacion->deportes) > 1) --}}<td>{{ count(auth()->user()->instalacion->deportes) > 1 ? $item->pista->tipo . '.' : '' }}
                                            {{ $item->pista->nombre }}</td> {{-- @endif --}}
                                        @if(auth()->user()->id_instalacion != 2)
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
                                        @else
                                        <td>{{ $item->valor_organizacion }}</td>
                                        <td>{{ $item->valor_presupuesto }}</td>
                                        @endif
                                        <td>
                                            <div class="d-flex" style="gap:5px">
                                                @if ($item->estado == 'active' && strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i')))
                                                    @if(auth()->user()->id_instalacion != 2)<a class="cancel btn btn-primary text-white btn-accion-reserva"
                                                        data-intervalo="{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i') }} - {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}"
                                                        data-reserva="{{ $item->id }}"
                                                        data-user="{{ $item->user->name ?? '' }}">
                                                        Acción
                                                    </a>@endif
                                                @endif
                                                @if(auth()->user()->instalacion->id == 2)
                                                    <a href="{{ route('reserva.edit', ['slug_instalacion' => request()->slug_instalacion, 'id' => $item->id]) }}" class="btn btn-success">
                                                        <i class="fa-solid fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('reserva.print', ['slug_instalacion' => request()->slug_instalacion, 'id' => $item->id]) }}" class="btn btn-primary">
                                                        <i class="fa-solid fa-print"></i>
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
                    [0, "asc"]
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

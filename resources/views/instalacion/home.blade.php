@extends('layouts.admin')

@section('style')
    <style>
        .btn-dia {
            border: 1px solid #e5e5e5;
        }

        .btn-dia .numero {
            padding: 10px;
            line-height: 30px;
            font-size: 14px;
            border-radius: 40px;
        }

        .btn-dia .numero.hoy {
            border: 2px solid #6dc3ee !important;
        }

        .btn-dia:hover .numero.hoy {
            background: #6dc3ee !important;
            color: white;
        }

        .btn-dia .numero.fecha-anterior {
            border: none !important;
            color: rgb(92, 92, 92);
        }

        .btn-dia:not(.active) .numero.reservas-activas {
            border: 2px solid #f15934;
        }

        .btn-dia:not(.active):hover .numero.reservas-activas {
            background: #f15934;
            color: white;
        }


        .col.text-center {
            padding: 0;
        }

        .dia {
            border: 1px solid #015e8c;
            font-size: 14px;
            background: #015e8c;
            color: white;
        }

        .mes {
            border: 1px solid #0073AA;
            font-size: 14px;
            background: #0073AA;
            color: white;
        }

        .btn-dia:hover {
            background: #d9f2ff;
        }

        .btn-dia:hover span.numero {
            color: rgb(122, 122, 122);
        }

        .btn-dia.active {
            background-color: #ddd;
            border-color: #ddd;
        }

        .btn-dia.active span {
            background-color: #fff;
            border-color: #fff;
            color: black;
        }

        .selector-pistas {
            display: flex;
            align-content: center;
            align-items: center;
            gap: 12px;
        }

        .selector-pistas i {
            font-size: 18px;
        }

        .next-prev-week a {
            padding: 12px 18px !important;
        }

        .reservas-dia {
            padding: 2%;
            background: #ddd;
            min-height: 180px;
        }

        .reservas-dia>div {
            padding: 30px 35px 10px;
            background: white;
        }

        .nav-tabs.nav-tabs-fillup {
            border-bottom: 1px solid #007be8;
        }

        .tab-content h4 {
            font-weight: 300;
        }

        .tab-content h4 span,
        strong {
            font-weight: bold;
        }

        .far.fa-clock {
            padding: 1px;
        }

        table.table-timeslots {
            height: 1rem;
        }

        .reservas-dia {
            display: none;
        }

        .table-timeslots td {
            border-bottom: 1px solid rgba(184, 184, 184, 0.7);
            vertical-align: top !important;
        }

        .reserva-card {
            border: 1px solid rgba(184, 184, 184, 0.692);
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            position: relative;
        }

        .reserva-card h4 {
            font-size: 17px;
            line-height: 17px;
            margin-top: 0;
            margin-bottom: 0;
        }

        .reserva-card i {
            margin-right: 0.5rem;
        }

        .text-active {
            color: #008000;
        }

        .text-canceled {
            color: #ea2c54;
        }

        .text-pasado {
            color: #47525e;
        }

        .timeslot-time {
            width: 25%;
            border-right: 2px solid rgba(220, 222, 224, 0.7);
            font-weight: bold;
        }

        .nav-tabs li a span {
            display: inline !important;
        }

        .nav-tabs li a.active span.num-reservas {
            margin-left: 11px;
            padding: 5px 9px;
            background: white;
            border-radius: 50%;
            color: #353f4d;
        }

        .nav-tabs li a span.num-reservas {
            margin-left: 11px;
            padding: 5px 9px;
            background: #007be8;
            border-radius: 50%;
            color: white;
        }

        .help {
            font-size: 12px;
            color: rgba(6, 18, 35, 0.67);
            letter-spacing: normal;
            line-height: 18px;
            display: block;
            margin-top: 6px;
            margin-left: 3px;
        }

        body>div.page-container>div.page-content-wrapper>div.content.sm-gutter>div {
            padding: 0 !important;
        }

        .inline-block {
            display: inline-block;
        }

        tr.desactivado td {
            background: #ddd !important;
        }

        .mostrar-fecha {
            display: inline-flex;
            padding: 11px 18px !important;
            border: 1px solid rgba(6, 18, 35, 0.17);
        }

        .exmp-wrp {
            background: #0073AA;
            position: relative;
            display: inline-block;
        }

        #week {
            background: #0073AA url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='22' viewBox='0 0 20 22'><g fill='none' fill-rule='evenodd' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' transform='translate(1 1)'><rect width='18' height='18' y='2' rx='2'/><path d='M13 0L13 4M5 0L5 4M0 8L18 8'/></g></svg>") right 1rem center no-repeat;
            cursor: pointer;
        }

        .btn-wrp {
            width: 181px;
            border: 1px solid #dadada;
            display: inline-block;
            position: relative;
            z-index: 10;
        }

        .btn-clck {
            border: none;
            background: transparent;
            width: 100%;
            padding: 10px;
        }

        .btn-clck::-webkit-calendar-picker-indicator {
            right: -10px;
            position: absolute;
            width: 78px;
            height: 40px;
            color: rgba(204, 204, 204, 0);
            opacity: 0
        }

        .btn-clck::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        .btn-clck::-webkit-clear-button {
            margin-right: 75px;
        }

        .btn-week {
            height: 96%;
            background: #0073AA;
            border: none;
            color: #fff;
            padding: 13px;
            position: absolute;
            left: 3px;
            top: 1px;
            z-index: 11;
            width: 140px;
            cursor: auto !important;
        }

        .loader-bg,
        .loader-bg-pista {
            width: 100%;
            height: 100%;
            background: #ffffff;
            position: unset;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999999999;
            display: none;
        }

        .loader-bg svg,
        .loader-bg-pista svg {
            width: 10vw;
            opacity: .7;
        }

        @media (max-width: 700px) {
            .dias .col.text-center {
                padding: 0;
                font-size: 13px;
            }

            .btn-dia {
                padding: 1rem !important;
            }

            .reservas-dia>div {
                padding: 0;
            }

            .table-timeslots tr {
                display: block;
            }

            .table-timeslots tr>td:first-child {
                border-top: 2px solid rgba(220, 222, 224, 0.7);
                border-right: 2px solid rgba(220, 222, 224, 0.7);
                border-left: 2px solid rgba(220, 222, 224, 0.7);
                border-bottom: 0 !important;
            }

            .table-timeslots tr>td:not(:first-child) {
                border-right: 2px solid rgba(220, 222, 224, 0.7);
                border-left: 2px solid rgba(220, 222, 224, 0.7);
            }

            .table-timeslots td {
                display: block;
                width: 100% !important;
                border-right: 0;
            }

            .table-timeslots td.timeslot-time {
                text-align: center;
            }

            .reserva-card>div {
                flex-direction: column;
            }

            .reserva-card>div>h4 {
                margin-bottom: 12px;
            }
        }
    </style>
@endsection

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
                        <h5>Reserva <span></span></h5>
                        <p class="p-b-10 user"><strong>Usuario: </strong><span></span></p>
                    </div>
                    <div class="modal-body">
                        <form role="form" method="POST" action="#">
                            @csrf
                            {{-- <div class="form-group-attached">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-default">
                                <label>Observaciones <span></span></label>
                                <textarea style="height: 50px" name="observaciones" rows="4" class="form-control"></textarea>
                            </div>
                            <span class="help">Puede quedarse vacío si no tiene.</span>
                        </div>
                    </div>
                </div> --}}
                            <div class="row text-right">
                                <input type="hidden" name="accion">
                                <button type="submit" data-accion="canceled"
                                    class="submit-form-validar btn btn-danger m-t-5 mr-2">Cancelarla</button>
                                @if (auth()->user()->id_instalacion != 2)
                                    <button type="submit" data-accion="pasado"
                                        class="submit-form-validar btn btn-success m-t-5 mr-2">Validarla</button>
                                @endif
                                {{-- <button type="submit" data-accion="desierta" class="submit-form-validar btn btn-warning m-t-5">Desierta</button> --}}
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    </div>
    <div class="row m-0">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 data-slug="{{ auth()->user()->instalacion->slug }}" class="text-primary no-margin inst-name">
                        {{ auth()->user()->instalacion->nombre }}</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-body">
                        <div class="row d-flex justify-content-between mb-2">
                            <div class="next-prev-week">
                                <div>{{-- {{ dd(request()->semana) }} --}}
                                    <a href="/{{ request()->slug_instalacion }}/admin/reservas?{{ 'week=' . \Carbon\Carbon::parse(request()->week)->subWeek()->format('Y-\WW') }}"
                                        class="btn btn-prev">
                                        < </a>
                                            <div class="exmp-wrp">
                                                <button class="btn-week">{!! date('W') == \Carbon\Carbon::parse(iterator_to_array($period)[0])->format('W')
                                                    ? 'Semana actual'
                                                    : \Carbon\Carbon::parse(iterator_to_array($period)[0])->formatLocalized('%d %b') .
                                                        ' - ' .
                                                        \Carbon\Carbon::parse(iterator_to_array($period)[count(iterator_to_array($period)) - 1])->formatLocalized(
                                                            '%d %b',
                                                        ) !!}</button>
                                                <div class="btn-wrp">
                                                    <form action="#" method="get">
                                                        <input type="week" name="week" id="week"
                                                            value="{{ \Carbon\Carbon::parse(iterator_to_array($period)[0])->format('Y') . '-W' . \Carbon\Carbon::parse(iterator_to_array($period)[0])->format('W') }}"
                                                            class="btn-clck">
                                                    </form>
                                                </div>
                                            </div>
                                            {{-- <a href="#" class="btn btn-select-week">{!! request()->semana == null || request()->semana == 0 ? 'Semana actual' :  \Carbon\Carbon::parse(iterator_to_array($period)[0])->formatLocalized('%d %b') . ' - ' . \Carbon\Carbon::parse(iterator_to_array($period)[count(iterator_to_array($period))-1])->formatLocalized('%d %b') !!} </a> --}}


                                            <a href="/{{ request()->slug_instalacion }}/admin/reservas?{{ 'week=' . \Carbon\Carbon::parse(request()->week)->addWeek()->format('Y-\WW') }}"
                                                class="btn btn-next">></a>
                                </div>
                            </div>
                            @if (request()->slug_instalacion == "ceco")
                                <button type="button" class="btn btn-outline-success mt-3" data-toggle="modal" data-target="#modalEspecifica">Imprimir reservas</button>
                            @endif
                                               
                            {{-- modal --}}
                            <div class="modal fade" id="modalEspecifica" tabindex="-1" role="dialog" aria-labelledby="modalEspecificaLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{route('imprimir_reservas', request()->slug_instalacion)}}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalEspecificaLabel">Selecciona las fechas</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="fecha">Fecha inicio</label>
                                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="fecha">Fecha fin</label>
                                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="formato">Selecciona el formato</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="formato" id="pdf" value="pdf" checked>
                                                        <label class="form-check
                                                        -label" for="pdf">
                                                            PDF
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="formato" id="excel" value="excel">
                                                        <label class="form-check-label" for="excel">
                                                            EXCEL
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                                        
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary">Buscar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center text-uppercase p-3 mes">
                                {{ \Carbon\Carbon::parse($period->start)->formatLocalized('%B') }}
                                {{ \Carbon\Carbon::parse($period->start)->formatLocalized('%Y') }}</div>
                        </div>
                        <div class="row dias">
                            @foreach ($period as $fecha)
                                <div class="col text-center">
                                    <div class="text-uppercase p-3 dia">
                                        {{-- {{ \Carbon\Carbon::parse($fecha)->formatLocalized('%A') }}  si hago esto en la tilde de miércoles me sale un caracter raro creo que por el utf-8 y quiero que salga en español --}}
                                        @php
                                            $dias = [
                                                'Monday' => 'Lunes',
                                                'Tuesday' => 'Martes',
                                                'Wednesday' => 'Miércoles',
                                                'Thursday' => 'Jueves',
                                                'Friday' => 'Viernes',
                                                'Saturday' => 'Sábado',
                                                'Sunday' => 'Domingo',
                                            ];
                                        @endphp
                                        {{ $dias[$fecha->format('l')] }}

                                    </div>
                                    <div><a data-fecha="{{ $fecha->format('Y-m-d') }}"
                                            data-fecha_long="{{ $fecha->format('d/m/Y') }}"
                                            @if (auth()->user()->instalacion->check_reservas_dia($fecha->format('Y-m-d'))) data-toggle="tooltip" data-placement="top" title="{{ auth()->user()->instalacion->check_reservas_dia($fecha->format('Y-m-d')) }} Reservas pendientes" @endif
                                            href="#"
                                            class="btn-dia w-100 h-100 d-block p-5 @if (Session::get('dia_reserva_hecha')) {{ $fecha->format('Y-m-d') == Session::get('dia_reserva_hecha') ? 'active' : '' }} @else {{ $fecha->format('d/m/Y') == date('d/m/Y') ? 'active' : '' }} @endif"><span
                                                class="numero {{ auth()->user()->instalacion->check_reservas_dia($fecha->format('Y-m-d'))? 'reservas-activas': '' }} {{ $fecha->format('Y-m-d') < date('Y-m-d') ? 'fecha-anterior' : ($fecha->format('Y-m-d') == date('Y-m-d') ? 'hoy' : '') }}">{{ $fecha->format('d') }}</span></a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row reservas-dia" style="display: block">
                            <div class="loader-bg" style="display: none">
                                @include('instalacion.loader.loader')
                            </div>
                            <div class="col">
                                <div class="pb-3">
                                    <div class="input-group">
                                        <div class="input-group-append ">
                                            <span class="input-group-text"
                                                style="border-left: 1px solid #06122324 !important;"><i
                                                    class="fas fa-search"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="searcher-reservas"
                                            placeholder="Buscar reservas...">
                                    </div>
                                </div>
                                <ul class="nav nav-tabs nav-tabs-fillup">
                                    @foreach ($pistas as $i => $item)
                                        <li class="nav-item">
                                            <a href="#" data-fecha="{{ date('Y-m-d') }}"
                                                data-pista="{{ $item->id }}" id="tab-espacio-{{ $item->id }}"
                                                class="{{ $i == 0 ? 'active' : '' }} tab-pista" data-toggle="tab"
                                                data-target="#espacio-{{ $item->id }}"><span>{{ $item->nombre_corto ?? $item->nombre }}</span><span
                                                    class=""></span></a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="loader-bg-pista" style="display: none">
                                    @include('instalacion.loader.loader')
                                </div>
                                <div class="tab-content reservas-dia">
                                    @foreach ($pistas as $i => $pista)
                                        <div class="tab-pane {{ $i == 0 ? 'active' : '' }}"
                                            id="espacio-{{ $pista->id }}">
                                            <div>
                                                <h4 class="d-inline-block"><strong>{{ $pista->nombre }}</strong> Reservas
                                                    para <span class="fecha"></span></h4> <a
                                                    href="/{{ request()->slug_instalacion }}/admin/reservas/{{ $pista->id }}/desactivar-dia/{{ date('Y-m-d') }}"
                                                    class="btn btn-outline-primary ml-3 btn-off-dia">DESACTIVAR DÍA
                                                    COMPLETO</a> <a
                                                    href="/{{ request()->slug_instalacion }}/admin/reservas/{{ $pista->id }}/activar-dia/{{ date('Y-m-d') }}"
                                                    class="btn btn-outline-primary ml-3 btn-on-dia">ACTIVAR DÍA
                                                    COMPLETO</a>
                                            </div>
                                            <div id="content-espacio-{{ $pista->id }}">

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
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

@section('script')
    <script>
        $(document).ready(function() {

            $('.dias').on('click', '.btn-dia', function(e) {
                e.preventDefault();

                $('.loader-bg').show().next().hide();
                $('.reservas-dia').show();

                $('.btn-dia').removeClass('active');
                $(this).addClass('active');
                $('span.fecha').html($(this).data('fecha_long'));
                $('.btn-off-dia').attr('href',
                    `/{{ request()->slug_instalacion }}/admin/reservas/{{ $pista->id }}/desactivar-dia/${$(this).data('fecha')}`
                );
                $('.btn-on-dia').attr('href',
                    `/{{ request()->slug_instalacion }}/admin/reservas/{{ $pista->id }}/activar-dia/${$(this).data('fecha')}`
                );
                $('.tab-pista').data('fecha', $(this).data('fecha'));
                $(`.nav-item a span:last-child`).removeClass('num-reservas').html(``);
                $('.nav-item:first-child a').click();
                $.ajax({
                    url: `/{{ request()->slug_instalacion }}/admin/reservas/numero/${$(this).data('fecha')}/`,
                    success: data => {
                        /* console.log(data); */
                        for (const index in data) {

                            $(`#tab-espacio-${index} span:last-child`).addClass('num-reservas')
                                .html(`${data[index]}`);
                            console.log(`#tab-espacio-${index} span:last-child`);
                        }

                        $('.loader-bg').hide().next().show();
                    },
                    error: data => {
                        console.log(data)
                    }
                });
                /* $.ajax({
                    url: `/${$('.inst-name').data('slug')}/admin/reservas/${$(this).data('fecha')}`,
                    success: data => {
                        data.forEach(pista => {
                            let string = '';
                            string += `<table class="table table-timeslots table-hover">
                            <tbody>`;
                            pista.res_dia.forEach(item => {
                                item.forEach(intervalo => {
                                    string += `<tr ${intervalo.desactivado ? 'class="desactivado"' : ''}>
                            <td class="timeslot-time"><div style="margin-bottom:20px;"><i class="far fa-clock"></i> ${intervalo.string}</div>`;

                                    if (intervalo.num_res < pista.reservas_por_tramo) {
                                        if (intervalo.desactivado) {
                                            string += `<div><form class="inline-block" method="POST" action="/{{ auth()->user()->instalacion->slug }}/admin/reservas/${pista.id}/activar/${intervalo.timestamp}${intervalo.desactivado == 2 ? '?periodic=1' : ''}">@csrf <button type="submit" class="btn btn-outline-success">Activar</button> </form> <a href="/{{ auth()->user()->instalacion->slug }}/admin/reservas/${pista.id}/reservar/${intervalo.timestamp}" class="btn btn-primary">Reservar</a></div></td><td class="timeslot-reserve">`;
                                        }else{
                                            string += `<div><form class="inline-block" method="POST" action="/{{ auth()->user()->instalacion->slug }}/admin/reservas/${pista.id}/desactivar/${intervalo.timestamp}">@csrf <button type="submit" class="btn btn-outline-primary">Desactivar</button> </form> <a href="/{{ auth()->user()->instalacion->slug }}/admin/reservas/${pista.id}/reservar/${intervalo.timestamp}" class="btn btn-primary">Reservar</a></div></td><td class="timeslot-reserve">`;
                                        }
                                    } else {
                                        string += `</td><td class="timeslot-reserve">`;
                                    }

                                    if (intervalo.reservas.length > 0) {
                                        intervalo.reservas.forEach(reserva => {
                                            console.log(reserva);
                                            string += `<div class="reserva-card"><div class="d-flex justify-content-between align-items-center">
                                        <h4><a href="/{{ request()->slug_instalacion }}/admin/users/${reserva.user.id}/ver">#${reserva.id} ${reserva.user.name}</a> <span class="capitalize text-${reserva.estado}">(${reserva.estado == 'active' ? 'Activo' : (reserva.estado == 'pasado' ? 'Pasado' : 'Cancelado')})</span></h4>`;
                                            if (reserva.estado == 'active') {
                                                string += `<div><a href="#" class="btn btn-primary btn-acciones-reserva" data-intervalo="${reserva.string_intervalo}" data-reserva="${reserva.id}" data-user="${reserva.user.name}">Acciones</a></div></div>`;
                                            }else if (reserva.estado == 'canceled') {
                                                string += `<div> <a href="#" class="btn btn-info">(${reserva.formated_updated_at}) Cancelado</a></div></div>`;
                                            }else{
                                                string += `<div> <a href="#" class="btn btn-white">(${reserva.formated_updated_at}) Pasada</a></div></div>`;
                                            }
                                            $(reserva.valores_campos_pers).each(function (index, element) {
                                                string += `<div class="mt-2"><strong><i class="fas fa-plus mr-1"></i> ${element.campo.label}: </strong>${element.valor}</div>`;
                                            });
                                            if (reserva.observaciones) {
                                                string += `<div class="mt-2"><strong><i class="far fa-comment-dots mr-1"></i> Observaciones reserva: </strong>${reserva.observaciones}</div>`;
                                            }
                                            if (reserva.reserva_periodica) {
                                                string += `<div class="mt-2"><strong><i class="fas fa-user-shield mr-1"></i> Reserva periódica</strong></div>`;
                                            }
                                            if (reserva.creado_por == 'admin') {
                                                string += `<div class="mt-2"><strong><i class="fas fa-user-shield mr-1"></i> Observaciones admin: </strong>creada por admin`;
                                            }
                                            if (reserva.observaciones_admin) {
                                                string += `, ${reserva.observaciones_admin}`;
                                            }
                                            string += `</div></div>`;
                                        });
                                    }else{
                                        string += `No Reservado`;
                                    }
                                    string += `</td></tr>`;
                                });
                            });
                            string += `</tbody></table></div>`;
                            $(`#content-espacio-${pista.id}`).html(`${string}`);
                            if (pista.num_reservas_dia > 0) {
                                $(`#tab-espacio-${pista.id} span:last-child`).addClass('num-reservas').html(`${pista.num_reservas_dia}`);
                            } else{
                                $(`#tab-espacio-${pista.id} span:last-child`).removeClass('num-reservas').html(``);
                            }
                        });

                        $('.loader-bg').hide().next().show();
                    },
                    error: data => {
                        console.log(data)
                    }
                }); */
            });
            $('.nav-tabs').on('click', '.tab-pista', function(e) {
                e.preventDefault();
                $('.loader-bg-pista').show().next().hide();
                $.ajax({
                    url: `/{{ request()->slug_instalacion }}/admin/reservas/${$(this).data('fecha')}/${$(this).data('pista')}`,
                    success: data => {
                        let string = '';
                        string += `<table class="table table-timeslots table-hover">
                                        <tbody>`;
                        data.res_dia.forEach(item => {
                            item.forEach(intervalo => {
                                string +=
                                    `<tr ${intervalo.desactivado ? 'class="desactivado"' : ''}>
                                        <td class="timeslot-time"><div style="margin-bottom:20px;"><i class="far fa-clock"></i> ${intervalo.string}<br>${intervalo.num_res ? "(Total: " + intervalo.num_res + ' res.)' : ''}</div>`;

                                if (intervalo.num_res < data
                                    .reservas_por_tramo) {
                                    if (intervalo.desactivado) {
                                        string +=
                                            `<div><form style="margin-bottom:7px" class="inline-block" method="POST" action="/{{ request()->slug_instalacion }}/admin/reservas/${data.id}/activar/${intervalo.timestamp}${intervalo.desactivado == 2 ? '?periodic=1' : ''}">@csrf <button type="submit" class="btn btn-outline-success" style="padding: 0 20px;">Activar</button> </form> <a href="/{{ request()->slug_instalacion }}/admin/reservas/${data.id}/reservar/${intervalo.timestamp}" class="btn btn-primary" style="padding: 0 14px;">Reservar</a></div></td><td class="timeslot-reserve">`;
                                    } else {
                                        string +=
                                            `<div><form style="margin-bottom:7px" class="inline-block" method="POST" action="/{{ request()->slug_instalacion }}/admin/reservas/${data.id}/desactivar/${intervalo.timestamp}">@csrf <button type="submit" class="btn btn-outline-primary">Desactivar</button> </form> <a href="/{{ request()->slug_instalacion }}/admin/reservas/${data.id}/reservar/${intervalo.timestamp}" class="btn btn-primary" style="padding: 0 14px;">Reservar</a></div></td><td class="timeslot-reserve">`;
                                    }
                                } else {
                                    string +=
                                        `</td><td class="timeslot-reserve">`;
                                }

                                if (intervalo.reservas.length > 0) {
                                    intervalo.reservas.forEach(reserva => {
                                        console.log(reserva);
                                        console.log(reserva.user);
                                        string +=
                                            `<div class="reserva-card"><div class="d-flex justify-content-between align-items-center">`;
                                        if (reserva.estado ==
                                            'active') {
                                            string +=
                                                `<h4><a href="#" class="btn-acciones-reserva" data-intervalo="${reserva.string_intervalo}" data-reserva="${reserva.id}" data-reserva_string="${reserva.reserva_multiple ? reserva.id + ' - #' + (+reserva.id + +reserva.numero_reservas) : reserva.id}" data-user="${reserva.user?.name}">#${reserva.id} ${reserva.reserva_multiple ? ' - #' + (+reserva.id + +reserva.numero_reservas-1) : ''} ${reserva.user?.name} ${reserva.reserva_multiple ? '(' + reserva.numero_reservas + ' reservas)' : ''}</a></h4>`;
                                        } else {
                                            string +=
                                                `<h4><a href="/{{ request()->slug_instalacion }}/admin/users/${reserva.user?.id}/ver">#${reserva.id} ${reserva.reserva_multiple ? ' - #' + (+reserva.id + +reserva.numero_reservas-1) : ''} ${reserva.user?.name} ${reserva.reserva_multiple ? '(' + reserva.numero_reservas + ' reservas)' : ''}</a></h4>`;
                                        }
                                        if (reserva.estado ==
                                            'active') {
                                            if (reserva.user?.id_instalacion != 2) {
                                                string +=
                                                    `<div><a href="#" class="btn btn-primary btn-acciones-reserva" data-intervalo="${reserva.string_intervalo}" data-reserva="${reserva.id}" data-reserva_string="${reserva.reserva_multiple ? reserva.id + ' - #' + (+reserva.id + +reserva.numero_reservas) : reserva.id}" data-user="${reserva.user?.name}">Acciones</a></div></div>`;
                                            } else {
                                                string +=
                                                    `<div><a href="#" class="btn btn-danger btn-acciones-reserva" data-intervalo="${reserva.string_intervalo}" data-reserva="${reserva.id}" data-reserva_string="${reserva.reserva_multiple ? reserva.id + ' - #' + (+reserva.id + +reserva.numero_reservas) : reserva.id}" data-user="${reserva.user?.name}">Cancelar la reserva</a></div></div>`;
                                            }
                                        } else if (reserva.estado ==
                                            'canceled') {
                                            if (reserva.salida !=
                                                null) {
                                                string +=
                                                    `<h4 class="text-danger" style="font-weight:bold">Salida (${reserva.salida})</h4></div>`;
                                            } else {
                                                string +=
                                                    `<h4 class="text-danger" style="font-weight:bold">Cancelado
                                                        <a href="" data-url="/{{ request()->slug_instalacion }}/admin/reservas/${reserva.id}/eliminar" class="btn btn-danger btn-eliminar-reserva" data-reserva="${reserva.id}" onclick="return confirm('¿Estás seguro de que quieres eliminar la reserva?')"><i class="fa-solid fa-trash mr-0"></i></a></h4>
                                                        </h4>
    
                                                    </div>`;
                                            }
                                        } else if (reserva.estado ==
                                            'desierta') {
                                            string +=
                                                `<h4 class="text-warning" style="font-weight:bold">Desierta</h4></div>`;
                                        } else if (reserva.estado ==
                                            'espera') {
                                            string +=
                                                `<h4 style="color:#ff9800;font-weight:bold">En lista de espera</h4></div>`;
                                        } else {
                                            string +=
                                                `<h4 style="color:#28a745;font-weight:bold">Validada</h4></div>`;
                                        }
                                        if (reserva.estado !=
                                            'canceled') {
                                            string +=
                                                `<a href="/{{ request()->slug_instalacion }}/admin/reservas/${reserva.id}/edit" style="position:absolute;right:0;bottom:0;padding-right:3px" href="#" data-toggle="tooltip" data-placement="top" title="Editar la reserva" class="btn btn-primary" data-piscina="1" data-fecha="${reserva.fecha}" data-hora="${reserva.string_intervalo}" data-intervalo="${reserva.string_intervalo}" data-reserva="${reserva.id}" data-reserva_string="${reserva.reserva_multiple ? reserva.id + ' - #' + (+reserva.id + +reserva.numero_reservas-1) : reserva.id}" data-user="${reserva.user?.name}"><i class="fa-solid fa-edit"></i></a>`;
                                        }
                                        $(reserva.valores_campos_pers)
                                            .each(function(index,
                                                element) {
                                                string +=
                                                    `<div class="mt-2"><strong><i class="fas fa-plus mr-1"></i> ${element.campo.label}: </strong>${element.valor ?? '---'}</div>`;
                                            });
                                        if (reserva.observaciones) {
                                            string +=
                                                `<div class="mt-2"><strong><i class="far fa-comment-dots mr-1"></i> Observaciones reserva: </strong>${reserva.observaciones}</div>`;
                                        }
                                        if (reserva.reserva_periodica) {
                                            string +=
                                                `<div class="mt-2"><strong><i class="fas fa-user-shield mr-1"></i> Reserva periódica</strong></div>`;
                                        }
                                        if (reserva.reserva_multiple) {
                                            string +=
                                                `<div class="mt-2"><strong><i class="fa-solid fa-calendar-plus"></i> Reserva múltiple: </strong>${reserva.numero_reservas} reservas</div>`;
                                        }
                                        if (reserva.creado_por ==
                                            'admin') {
                                            string +=
                                                `<div class="mt-2"><strong><i class="fas fa-user-shield mr-1"></i> Observaciones admin: </strong>creada por admin`;
                                        }
                                        if (reserva
                                            .observaciones_admin) {
                                            string +=
                                                `, ${reserva.observaciones_admin}`;
                                        }
                                        string += `</div></div>`;
                                    });
                                } else {
                                    string += `No Reservado`;
                                }
                                string += `</td></tr>`;
                            });
                        });
                        string += `</tbody></table></div>`;
                        $(`#content-espacio-${data.id}`).html(`${string}`);
                        /* if (data.num_reservas_dia > 0) {
                            $(`#tab-espacio-${data.id} span:last-child`).addClass('num-reservas').html(`${data.num_reservas_dia}`);
                        } else{
                            $(`#tab-espacio-${data.id} span:last-child`).removeClass('num-reservas').html(``);
                        } */


                        $('.loader-bg-pista').hide().next().show();
                        $("[data-toggle=tooltip").tooltip();
                    },
                    error: data => {
                        console.log(data)
                    }
                });
            });
            $('.btn-dia.active').click();

            $('.reservas-dia').on('click', '.btn-eliminar-reserva' , function(e) {
                e.preventDefault();
                let containerPadre = $(this).parent().parent().parent();
                $.ajax({
                    type: "GET",
                    url: $(this).data('url'),
                    success: function (response) {
                        console.log(response);
                        if (response != 1) {
                            alert('Ha ocurrido un error al eliminar la reserva');
                        }else{
                            containerPadre.remove();
                        }
                    }
                });
            });

            $('.reservas-dia').on('click', '.btn-acciones-reserva', function(e) {
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
                // $('#modalSlideUp').find('form').submit();
                // to ajax
                let form = $('#modalSlideUp').find('form');

                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function(response) {
                        console.log(response);
                        if (response == 1) {
                            $('#modalSlideUp').modal('hide');
                            // $('.btn-dia.active').click();
                            $('.tab-pista.active').click();

                        } else {
                            alert('Ha ocurrido un error al validar la reserva');
                        }
                    }
                });
            });

            $('input#week').change(function(e) {
                /* console.log(value); */
                $(this).parent().submit();
            });

            $('.reservas-dia').on('keyup', '#searcher-reservas', function(e) {
                let filter = $(this).val().toUpperCase();
                $('table.table-timeslots .timeslot-reserve .reserva-card').filter(function() {
                    $(this).toggle($(this).text().toUpperCase().indexOf(filter) > -1)
                });
            });
        });
    </script>
    @if (!Session::get('dia_reserva_hecha'))
        @if (
            (isset(request()->semana) && request()->semana != 0) ||
                (isset(request()->week) && date('W') != \Carbon\Carbon::parse(iterator_to_array($period)[0])->format('W')))
            <script>
                $(document).ready(function() {
                    $('.btn-dia')[0].click();
                });
            </script>
        @endif
    @endif
@endsection

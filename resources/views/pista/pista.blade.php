@extends('layouts.userview')

@section('pagename', $pista_selected->tipo)

@section('style')
    <style>
        #select2-pista-select2-container {
            text-align: center;
            color: white;
            font-size: 20px;
        }

        .select2-container--default .select2-selection--single {
            background-color: #373d43;
        }

        #select2-pista-select2-results {
            color: white;
            background-color: #373d43;
        }

        body>span>span>span.select2-search.select2-search--dropdown {
            background-color: #373d43;
        }

        body>main>div.container.is-max-desktop>div>div>div>div.pistas>span>span.selection>span {
            height: auto;
            padding: 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px;
            position: absolute;
            top: 13px;
            right: 11px;
            width: 20px;
        }

        .select2-container--default .select2-results__option--selected {
            color: #373d43;
        }

        .datepicker.date-input {
            color: white;
            background: #6c757d;
        }

        #form-dia>div>a {
            border-right: 1px solid white;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #fff transparent transparent transparent !important;
            color: white !important;
        }

        .picker__box {
            padding-top: 18px;
        }

        .span-num-res {
            background: #0d6efd;
            width: 31px;
            display: inline-flex;
            justify-content: center;
            border-radius: 17px;
            color: white;
        }

        .active .span-num-res {
            background: white;
            color: #0d6efd;
        }
    </style>
    @if (count($pistas) > 1)
        <style>
            .pistas {
                padding-top: 50px;
            }
        </style>
    @endif
    @if (count($pistas) > 4)
        <style>
            .seleccionar-pista-label {
                top: 60px !important;
            }
        </style>
    @endif
    @foreach ($valid_period as $fecha)
        <style>
            div[aria-label="{!! $fecha->format('d/m/Y') !!}"] {
                border: 1px solid #0089ec;
            }
        </style>
    @endforeach
@endsection

@section('content')

    <div id="url_instalacion" style="display: none">/{{ request()->slug_instalacion }}/{{ request()->deporte }}/</div>
    <section class="hero is-medium">
        @if(request()->slug_instalacion != 'bancordoba')
        <div class="has-text-centered title-div title-pista-section"
            style="background:linear-gradient(0deg, rgba(36, 36, 36, 0.5), rgba(36, 36, 36, 0.5))@if (file_exists(public_path() . '/img/deportes/banner-' . lcfirst($pista_selected->tipo) . '.jpg')) , url(/img/deportes/banner-{{ lcfirst($pista_selected->tipo) }}.jpg) @endif center;
            background-size:cover;">

            <h1 class="title">{{ $pista_selected->tipo }}<br>{{ $pista_selected->subtipo ?? '' }}</h1>
            @else
                <div class="has-text-centered title-div title-pista-section"
                     style="background:linear-gradient(0deg, rgba(36, 36, 36, 0.5), rgba(36, 36, 36, 0.5)) , url(https://www.jeseblogs.es/u/bancordoba/images/cordobanmayo.jpg)  center;
                         background-size:cover;">
                <h1 class="title">Recogida de alimentos</h1>
            @endif
        </div>
    </section>

    <div class="container is-max-desktop">
        <div class="columns">
            <div class="column is-full">
                <div class="div-reservas">
                    <div class="pistas" @if ($pista_selected->id_instalacion == 2) style="padding-top:10px" @endif>
                        @if (count($pistas) > 1)
                            <div class="seleccionar-pista-label"
                                style="position: absolute; color: white;top:11px;font-size: 18px;@if ($pista_selected->id_instalacion == 2) display:none; @endif">
                                Selecciona espacio: </div>
                        @endif
                        @if (count($pistas) > 4)
                            @if ($pista_selected->id_instalacion != 2)
                                <select name="pista" class="form-select" id="pista-select2">
                                    @foreach ($pistas as $pista)
                                        <option value="{{ $pista->id }}"
                                            @if ($pista_selected->id == $pista->id) selected @endif>{{ $pista->nombre }}</option>
                                    @endforeach
                                </select>
                            @else
                                <ul class="nav nav-pills justify-content-center">
                                    @foreach ($pistas as $pista)
                                        <li class="nav-item">
                                            <a class="nav-link @if ($pista_selected->id == $pista->id) active @endif"
                                                href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista->id }}"><span>{{ $pista->nombre }}</span>
                                                @if (
                                                    $pista->reservas_given_two_dates(date('Y-m-d'),
                                                            iterator_to_array($period)[count(iterator_to_array($period)) - 1]->format('Y-m-d'))->count())
                                                    <span
                                                        class="span-num-res ml-2">{{ $pista->reservas_given_two_dates(date('Y-m-d'), iterator_to_array($period)[count(iterator_to_array($period)) - 1]->format('Y-m-d'))->count() }}</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @else
                            @foreach ($pistas as $index => $pista)
                                
                          
                                @if ($pista->id == 67 && \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::createFromFormat('d/m/Y', '20/01/2025')))
                                    <div class="@if ($pista->id == $pista_selected->id) active @endif " @if ($pistas->count() == 1)
                                        style="width:100%"
                                    @endif><a class=" select-pista"
                                            data-id_pista="{{ $pista->id }}"
                                            href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista->id }}">{{ $pista->nombre }}</a>
                                    </div>
                                @endif
                                @if ($pista->id != 67)
                                    <div class="@if ($pista->id == $pista_selected->id) active @endif " @if ($pistas->count() == 1)
                                        style="width:100%"
                                    @endif><a class=" select-pista"
                                            data-id_pista="{{ $pista->id }}"
                                            href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista->id }}">{{ $pista->nombre }}</a>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <div class="calendario-horarios">
                        <div class="navigator">
                            <div class="semanas">
                                <a class="button"
                                    href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista_selected->id }}?semana={{ request()->semana == null || request()->semana == 0 ? '-1' : request()->semana - 1 }}">
                                    < </a>
                                        <a class="button {{ request()->semana == null || request()->semana == 0 ? 'active' : '' }}"
                                            href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista_selected->id }}">
                                            Hoy
                                        </a>
                                        <a class="button"
                                            href="?semana={{ request()->semana == null || request()->semana == 0 ? '1' : request()->semana + 1 }}">
                                            >
                                        </a>
                            </div>

                            <div class="calendario">
                                <div class="text-center mb-2" style="font-size: 18px">Selecciona día:</div>
                                <form id="form-dia" method="get"
                                    action="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista_selected->id }}">
                                    <div class="input-group diapicker">
                                        <a href="#" class="btn btn-secondary">
                                            <i class="fas fa-calendar"></i>
                                        </a>
                                        <input type="hidden" id="dia" class="datepicker date-input form-control"
                                            name="dia"
                                            value="{{ request()->dia == null ? date('d/m/Y') : request()->dia }}">
                                    </div>
                                </form>
                            </div>

                            @php
                                $dias_periodo = iterator_to_array($period);
                            @endphp

                            <div style="text-transform: capitalize" class="mes">
                                {{ \Carbon\Carbon::parse($dias_periodo[0])->translatedFormat('d M') .
                                    ' - ' .
                                    \Carbon\Carbon::parse($dias_periodo[count($dias_periodo) - 1])->translatedFormat('d M') }}
                            </div>
                        </div>
                    </div>

                    <div class="thead">

                        @foreach ($horarios_final as $horario)
                            <div class="th" style="text-transform: capitalize">
                                @foreach ($horario as $mini_horario)
                                    <div style="height:4rem">
                                        {{ \Carbon\Carbon::parse($mini_horario[0]['timestamp'])->translatedFormat('l') }}

                                    </div>
                                    @foreach ($mini_horario as $intervalo)
                                        <div
                                            @if ($intervalo['height'] < 17) style="height: @if( request()->slug_instalacion != "bancordoba"){{ $intervalo['height'] / 2 }}rem @else 2.5rem @endif "
                                            @else
                                                style="height:{{ $intervalo['height'] / 4 }}rem" @endif>
                                            <a @if (!$intervalo['valida']) @if ($intervalo['siguiente_reserva_lista_espera'])
                                                        href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ $pista_selected->id }}/{{ $intervalo['timestamp'] }}"
                                                        class="btn-reservar btn-reservar-suplente" style="background: #ff9800"
                                                    @else
                                                        href="#" class="btn-no-disponible" @endif
                                            @else
                                                href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ $pista_selected->id }}/{{ $intervalo['timestamp'] }}"
                                                class="btn-reservar" @endif
                                                >
                                                @if (!$intervalo['reunion'])
                                                    {{ $intervalo['string'] }}
                                                @else
                                                    <span style="max-width:150px;">{!! strlen($intervalo['reunion']->valor_nombre_reunion) > 17
                                                        ? strrev(implode(strrev('<br>'), explode(strrev(' '), strrev($intervalo['reunion']->valor_nombre_reunion), 2)))
                                                        : $intervalo['reunion']->valor_nombre_reunion !!}</span>
                                                @endif
                                                @if ($pista_selected->instalacion->id == 5 && $intervalo['valida'])
                                                    <br>
                                                    (Libres:
                                                    {{ $pista_selected->reservas_por_tramo - $intervalo['num_res'] }})
                                                @endif
                                            </a>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-reserva-suplente" tabindex="-1" role="dialog" style="padding-right: 0">
        <div class="modal-dialog" role="document">
            <div class="modal-content m-0" style="top:25vh">
                <div class="modal-header">
                    <h4 class="h4 mb-0">Lista de espera</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-4 text-center" style="font-size: 17px">El cupo de reservas está <strong>COMPLETO</strong>
                        en este intervalo. Si alguna reserva se cancela, se te avisará y se asignará una plaza de forma
                        automática. </p>
                    <p class="text-center pt-2"><a href="#" class="btn btn-success">Realizar reserva</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.btn-no-disponible').click(function(e) {
                e.preventDefault();
            });

            var input_date = $('#dia').pickadate({
                editable: true,
                selectYears: 100,
                selectMonths: true,
                format: 'dd/mm/yyyy',
                min: false,
                max: false
            });

            var picker = input_date.pickadate('picker');

            $("#dia").focus(function() {
                document.activeElement.blur();
            });

            $(".diapicker").on("click", function(e) {
                e.preventDefault();
                e.stopPropagation();

                picker.open();

                picker.on('set', function(event) {
                    if (event.select) {
                        $('#form-dia').submit();
                    }
                });
            });

            $('#pista-select2').select2();

            $('#pista-select2').change(function(e) {
                window.location.href = $('#url_instalacion').html() + $(this).val() +
                    "?dia={{ request()->dia }}&dia_submit={{ request()->dia_submit }}";
            });

            $('.btn-reservar-suplente').click(function(e) {
                e.preventDefault();
                $('#modal-reserva-suplente').modal('show').find('.btn-success').attr('href', $(this).attr(
                    'href'));
            });
        });
    </script>
@endsection

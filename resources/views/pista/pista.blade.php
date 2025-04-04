@extends('layouts.userview')
@extends('pista.estilo-calendario2')

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
        @if (request()->slug_instalacion != 'bancordoba')
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

    @if ($instalacion['tipo_calendario'] == 1)
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
                                                @if ($pista_selected->id == $pista->id) selected @endif>{{ $pista->nombre }}
                                            </option>
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
                                    @if ($pista->id == 67)
                                        <div class="@if ($pista->id == $pista_selected->id) active @endif "
                                            @if ($pistas->count() == 1) style="width:100%" @endif><a
                                                class=" select-pista" data-id_pista="{{ $pista->id }}"
                                                href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ isset(request()->subtipo) ? request()->subtipo . '/' : '' }}{{ $pista->id }}">{{ $pista->nombre }}</a>
                                        </div>
                                    @endif
                                    @if ($pista->id != 67)
                                        <div class="@if ($pista->id == $pista_selected->id) active @endif "
                                            @if ($pistas->count() == 1) style="width:100%" @endif><a
                                                class=" select-pista" data-id_pista="{{ $pista->id }}"
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
                                            <div @if ($intervalo['height'] < 17) style="height: @if (request()->slug_instalacion != 'bancordoba'){{ $intervalo['height'] / 2 }}rem @else 2.5rem @endif "
@else
    style="height:{{ $intervalo['height'] / 4 }}rem"     @endif>
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
    @else
        <div class="row">
            <div class="col-md-9">
                <div class="card shadow-box card-reserva mb-4" id="contenedor-reservas" style="margin: auto"
                    data-tipo-reserva="1">
                    <div class="filtros p-0 d-flex">
                        <div>
                            @php
                            $aTipos=[];
                            foreach ($instalacion->pistas as $key => $value) {
                                if(!in_array($value->tipo, $aTipos)) {
                                    $aTipos[] = $value->tipo;
                                }
                            }
                            @endphp
                            <select class="w-100 form-control select2 select-pista">
                                @foreach ($aTipos as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="position-relative">
                            <div class="div-hoy">Hoy</div>
                            <div class="div-alt-select-fecha">
                                <input type="date" class="form-control" id="alt-select-fecha" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
                            </div>
                            <input type="text" class="form-control select-fecha" id="fecha-seleccionada">
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="tabla-horario">
                            <div class="loader-horario text-center" style="display: none">
                                <div>
                                    <div class="spinner-border text-primary mb-3" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div>Buscando disponibilidad</div>
                                </div>
                            </div>
                            <div class="pistas-horario">
                                <div class="celda" style="height: 40px; line-height: 40px;"></div>
                                @foreach ($pistas as $pista)
                                    <div class="celda" style="height: 40px; line-height: 40px;">
                                        {{ $pista->nombre }}
                                    </div>
                                @endforeach
                                <div id="ver-zonas-anteriores">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-layout-sidebar-right-expand" width="22"
                                        height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FFFFFF"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                        <path d="M15 4v16" />
                                        <path d="M10 10l-2 2l2 2" />
                                    </svg>
                                </div>
                                <div id="ver-mas-zonas">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-layout-sidebar-left-expand" width="22"
                                        height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FFFFFF"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24V24H0z" fill="none" />
                                        <path
                                            d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                                        <path d="M9 4v16" />
                                        <path d="M14 10l2 2l-2 2" />
                                    </svg>
                                </div>
                            </div>
                            <div class="tramos">
                                <div class="horas-tramos">
                                    @for ($i = 7; $i <= 23; $i++)
                                        <div class="celda" style="width: 40px; height: 40px; line-height: 40px;">
                                            {{ $i }}
                                        </div>
                                    @endfor
                                </div>
                                <div class="horas-tramos-pistas">
                                    @foreach ($pistas as $index => $pista)
                                        <div class="slots-pista">
                                            <div class="slots-horas">
                                                @for ($i = 7; $i <= 23; $i++)
                                                    <div class="celda" style="width: 40px; height: 40px; line-height: 40px;">
                                                    </div>
                                                @endfor
                                                @php
                                                $fechaSeleccionada = request()->input('dia', date('Y-m-d'));
                                                @endphp
                                                @foreach ($pista->horario_con_reservas_por_dia(date($fechaSelecconada ?? date('Y-m-d'))) as $item)
                                                    @foreach ($item as $intervalo)

                                                        <div data-left="{{ $intervalo['hora'] }}"
                                                            data-width="{{ $intervalo['width'] }}"
                                                            class="slot celda slot-reserva"
                                                            style="left:{{ $intervalo['hora'] }}px;width: {{ $intervalo['width'] }}px;height:40px;position:absolute;z-index:20">
                                                            <div

                                                                @if (!$intervalo['valida'])

                                                                    @switch($intervalo['estado'])

                                                                    @case('reservado')
                                                                    class="btn-reservado"
                                                                    @break
                                                                    @case('desactivado')
                                                                    class="btn-no-disponible"
                                                                    @break

                                                                    @endswitch
                                                                @endif>
                                                                <a
                                                                    @if (!$intervalo['valida']) href="#"  class="d-block h-100"  @else data-toggle="tooltip" data-html="true" data-placement="top"
                                                                    title="{{ $pista->nombre }}
                                                                    {{ $intervalo['string'] }}" class="d-block h-100" href="/{{ request()->slug_instalacion }}/{{ $pista->tipo }}/{{ $pista->id }}/{{ $intervalo['timestamp'] }}" @endif><span class="show-responsive">  @if (!$intervalo['valida'])

                                                                            @switch($intervalo['estado'])

                                                                                @case('reservado')
                                                                                RESERVADA
                                                                                @break
                                                                                @case('desactivado')
                                                                                NO DISPONIBLE
                                                                                @break


                                                                            @endswitch
                                                                            @else
                                                                            {{ $intervalo['string'] }}


                                                                        @endif




                                                                    </span></a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="leyenda text-right">
                                <div class="reservada">Reservada</div>
                                <div class="disponible">Libre</div>
                                <div class="no-disponible">No disponible</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endif

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
    // Evento para actualizar los nombres de los días cuando cambia el ancho de la pantalla

    // Llamada inicial para configurar los nombres de los días
    window.addEventListener("DOMContentLoaded", function() {
        function actualizarNombresDias() {
            const anchoPantalla = window.innerWidth;
            const celdas = document.querySelectorAll('.nombre-dia-abreviado');

            const diasMovil = ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"];
            const diasEscritorio = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];

            celdas.forEach(function(celda) {
                const numeroDia = celda.dataset.dia;

                celda.textContent = anchoPantalla < 550 ? diasMovil[numeroDia] : diasEscritorio[
                    numeroDia];
            });

        }
        actualizarNombresDias();
        window.addEventListener('resize', actualizarNombresDias);

        const fechaInput = document.getElementById('alt-select-fecha');
    if (!fechaInput) {
        console.error('El campo con ID "fecha-seleccionada" no existe en el DOM.');
        return;
    }
    fechaInput.addEventListener('click', function () {
    console.log('Evento "click" detectado.');
});
    fechaInput.addEventListener('change', function () {
        console.log('Evento "change" detectado.');
        const fechaSeleccionada = this.value; // Obtener el valor seleccionado
        const fechaActual = new Date().toISOString().split('T')[0]; // Obtener la fecha actual en formato YYYY-MM-DD

        // Si la fecha seleccionada coincide con la fecha actual, mostrar "Hoy"
        const divHoy = document.querySelector('.div-hoy');
        if (fechaSeleccionada === fechaActual) {
            divHoy.textContent = 'Hoy';
        } else {
            divHoy.textContent = fechaSeleccionada; // Mostrar la fecha seleccionada
        }

        // Enviar la fecha seleccionada a la URL mediante AJAX
        enviarFechaAJAX(fechaSeleccionada);
    });
    // Función para enviar la fecha mediante AJAX
    function enviarFechaAJAX(fecha) {
        const baseUrl = document.getElementById('url_instalacion').textContent; // Obtén la URL base
    const url = `${baseUrl}${fecha}`; // Construye la URL completa

    // Muestra el spinner
    document.querySelector('.loader-horario').style.display = 'block';

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest', // Indicar que es una solicitud AJAX
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error en la solicitud: ${response.statusText}`);
                }
                return response.json(); // Parsear la respuesta como JSON
            })
            .then(data => {
                console.log('Respuesta del servidor:', data); // Manejar la respuesta del servidor
                // Aquí puedes actualizar dinámicamente la página con los datos recibidos
                 // Oculta el spinner
            document.querySelector('.loader-horario').style.display = 'none';
                // Llama a la función para actualizar la interfaz
            actualizarHorario(data.horario, data.pistas);
            })
            .catch(error => {
                console.error('Error al enviar la fecha:', error);
                 // Oculta el spinner
            document.querySelector('.loader-horario').style.display = 'none';
            });
    }

    function actualizarHorario(horario, pistas) {
    const horarioContainer = document.querySelector('.horas-tramos-pistas');

    // Limpia el contenido actual del contenedor
    horarioContainer.innerHTML = '';

    console.log('Horario recibido:', horario);
    console.log('Pistas recibidas:', pistas);

    // Verifica si el array `horario` tiene datos
    if (!Array.isArray(horario) || horario.length === 0 || !Array.isArray(pistas) || pistas.length === 0) {
        console.warn('El array horario o pistas está vacío o no tiene la estructura esperada.');
        horarioContainer.innerHTML = '<div class="alert alert-warning">No hay horarios disponibles.</div>';
        return;
    }

    // Itera sobre las pistas y sus horarios
    horario.forEach((pistaHorario, pistaIndex) => {
        if (!Array.isArray(pistaHorario) || pistaHorario.length === 0) {
            console.warn(`El elemento pistaHorario no es un array válido:`, pistaHorario);
            return; // Salta esta iteración si no es un array válido
        }

        const slotsPista = document.createElement('div');
        slotsPista.classList.add('slots-pista');

        const slotsHoras = document.createElement('div');
        slotsHoras.classList.add('slots-horas');

        // Itera sobre los intervalos de tiempo en `pistaHorario`
        pistaHorario.forEach(intervalos => {
            intervalos.forEach(intervalo => {
                const slot = document.createElement('div');
            slot.classList.add('slot', 'celda', 'slot-reserva');
            slot.setAttribute('data-left', intervalo.hora);
            slot.setAttribute('data-width', intervalo.width);
            slot.style.left = `${intervalo.hora}px`;
            slot.style.width = `${intervalo.width}px`;
            slot.style.height = '40px';
            slot.style.position = 'absolute';
            slot.style.zIndex = '20';

            const innerDiv = document.createElement('div');

            // Agregar clases según el estado del intervalo
            if (!intervalo.valida) {
                switch (intervalo.estado) {
                    case 'reservado':
                        innerDiv.classList.add('btn-reservado');
                        break;
                    case 'desactivado':
                        innerDiv.classList.add('btn-no-disponible');
                        break;
                }
            }

            const link = document.createElement('a');
            if (!intervalo.valida) {
                link.href = '#';
                link.classList.add('d-block', 'h-100');
            } else {
                link.href = `/${pistas[pistaIndex].slug_instalacion}/${pistas[pistaIndex].tipo}/${pistas[pistaIndex].id}/${intervalo.timestamp}`;
                link.setAttribute('data-toggle', 'tooltip');
                link.setAttribute('data-html', 'true');
                link.setAttribute('data-placement', 'top');
                link.title = `${pistas[pistaIndex].nombre} ${intervalo.string}`;
                link.classList.add('d-block', 'h-100');
            }
            const span = document.createElement('span');
            span.classList.add('show-responsive');
            if (!intervalo.valida) {
                switch (intervalo.estado) {
                    case 'reservado':
                        span.textContent = 'RESERVADA';
                        break;
                    case 'desactivado':
                        span.textContent = 'NO DISPONIBLE';
                        break;
                }
            } else {
                span.textContent = intervalo.string;
            }

            link.appendChild(span);
            innerDiv.appendChild(link);
            slot.appendChild(innerDiv);
            slotsHoras.appendChild(slot);
        });

        slotsPista.appendChild(slotsHoras);
        horarioContainer.appendChild(slotsPista);
    });
            });

    // Inicializa los tooltips después de agregar los elementos dinámicamente
    $('[data-toggle="tooltip"]').tooltip();
}

    function ajustarPosicion() {
        const anchoPantalla = window.innerWidth;

        // Seleccionar todos los elementos con la clase .slot.celda.slot-reserva
        const slots = document.querySelectorAll(".slot.celda.slot-reserva");

        slots.forEach(function (slot) {
            const hora = slot.getAttribute("data-left"); // Obtener el valor de data-left
            const width = slot.getAttribute("data-width"); // Obtener el valor de data-width

            if (anchoPantalla >= 992) {
                // Pantallas grandes: usar 'left'
                slot.style.left = `${hora}px`;
                slot.style.top = ""; // Limpiar el valor de 'top'
            } else {
                // Pantallas pequeñas: usar 'top'
                slot.style.top = `${hora}px`;
                slot.style.left = ""; // Limpiar el valor de 'left'
            }

            // Aplicar el ancho y altura
            slot.style.width = `${width}px`;
            slot.style.height = "40px";
        });
    }

    // Llamar a la función al cargar la página
    ajustarPosicion();

    // Llamar a la función al redimensionar la ventana
    window.addEventListener("resize", ajustarPosicion);
});
</script>
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

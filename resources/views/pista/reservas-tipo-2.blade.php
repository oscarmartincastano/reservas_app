@php
    /**
     * Función para obtener los días del mes en formato timestamp.
     *
     * @param int $timestamp Timestamp de la fecha actual.
     * @return array Lista de timestamps correspondientes a los días del mes.
     */
    function obtener_dias_horario_mes($timestamp)
    {
        $carbonDate = \Carbon\Carbon::createFromTimestamp($timestamp);
        $startDate = $carbonDate->startOfMonth(); // Primer día del mes
        $endDate = $carbonDate->endOfMonth(); // Último día del mes

        $fechas = [];
        while ($startDate->lte($endDate)) {
            $fechas[] = $startDate->timestamp; // Agregar el timestamp del día
            $startDate->addDay(); // Avanzar al siguiente día
        }

        return $fechas;
    }
@endphp

<style>
    .ui-datepicker-calendar {
        display: none;
    }

    .ui-datepicker-current {
        display: none;
    }
</style>
<div class="filtros p-0 d-flex">
    <div>
        <select class="w-100 form-control select2 select-pista">
            @foreach ($instalacion->pistas as $pista)
                <option value="{{ $pista->id }}">{{ $pista->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="position-relative">
        <div class="div-hoy"
            data-hoy="{{ Carbon\Carbon::createFromFormat('m', date('m'))->locale('es')->monthName }} {{ date('Y') }}">
            {{ Carbon\Carbon::createFromFormat('m', date('m'))->locale('es')->monthName }} {{ date('Y') }}
        </div>

        <div class="div-alt-select-fecha">
            <input type="text" class="form-control" id="alt-select-fecha" readonly="readonly">
        </div>
        <input type="text" class="form-control select-fecha" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}"
            readonly="readonly">
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
        <div class="pistas-horario" style="width: 20%;">
            <div class="celda" style="height: 40px; line-height: 40px;"></div>
            @foreach ($instalacion->pistas as $pista)
                <div class="celda" style="height: 40px; line-height: 40px;">
                    {{ $pista->nombre }}
                </div>
            @endforeach
            <div id="ver-zonas-anteriores">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-sidebar-right-expand"
                    width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FFFFFF" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                    <path d="M15 4v16" />
                    <path d="M10 10l-2 2l2 2" />
                </svg>
            </div>
            <div id="ver-mas-zonas">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-sidebar-left-expand"
                    width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FFFFFF" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                    <path d="M9 4v16" />
                    <path d="M14 10l2 2l-2 2" />
                </svg>
            </div>
        </div>
        @php
    // Obtener la fecha actual y calcular los días del mes
    $carbonDate = \Carbon\Carbon::now();
    $startDate = $carbonDate->copy()->startOfMonth(); // Primer día del mes
    $endDate = $carbonDate->copy()->endOfMonth(); // Último día del mes

    $fechas = [];
    while ($startDate->lte($endDate)) {
        $fechas[] = $startDate->copy(); // Agregar una copia de la fecha actual
        $startDate->addDay(); // Avanzar al siguiente día
    }
@endphp
        <div class="tramos" style="width: 80%;">
            <div class="horas-tramos">
                @foreach ($fechas as $fecha)
                    <div class="celda" style="flex:1; height: 40px; line-height: 40px;">
                        @php
                            // Obtener información del día
                            $nombreDia = $fecha->format('l'); // Nombre del día en inglés
                            $numeroDiaMes = $fecha->day; // Número del día en el mes
                            $numeroDia = $fecha->dayOfWeek; // Número del día en la semana
                            $nombreDiaEspanol = [
                                'domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'
                            ][$numeroDia];
                        @endphp
                        <span class="nombre-dia-abreviado" data-dia="{{ $numeroDia }}">
                            {{ $nombreDiaEspanol }} - {{ $numeroDiaMes }}
                        </span>
                    </div>
                @endforeach
            </div>
            <div class="horas-tramos-pistas">
                @foreach ($pistas as $index => $pista)
                    <div class="slots-pista">
                        <div class="slots-horas">
                            @php
                                // Deserializar el campo "horario"
                                $horario = unserialize($pista->horario);
            
                                // Verificar si el horario tiene datos válidos
                                $dias = $horario[0]['dias'] ?? [];
                                $intervalos = $horario[0]['intervalo'] ?? [];
                            @endphp
            
                            {{-- Mostrar los días --}}
                            @foreach ($dias as $dia)
                                <div class="celda" style="flex:1; height: 40px; line-height: 40px;">
                                    Día: {{ $dia }}
                                </div>
                            @endforeach
            
                            {{-- Mostrar los intervalos --}}
                            @foreach ($intervalos as $intervalo)
                                <div class="celda" style="flex:1; height: 40px; line-height: 40px;">
                                    Horario: {{ $intervalo['hinicio'] }} - {{ $intervalo['hfin'] }} (Secuencia: {{ $intervalo['secuencia'] }} minutos)
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- <div class="block-before"
                    style="top: {{ $pistas->count() * (40/$pistas->count()) }}px;width: {{ $block_pista }}px"></div> --}}
        </div>
    </div>

    <div class="leyenda text-right">
        <div class="reservada">Reservada</div>
        <div class="disponible">Libre</div>
        <div class="no-disponible">No disponible</div>
    </div>
</div>

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
    });
</script>

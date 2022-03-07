@extends('layouts.userview')

@section('pagename', $pista_selected->tipo)

@section('style')
    <style>
        #select2-pista-select2-container{
            text-align: center;
            color: white;
            font-size: 20px;
        }
        .select2-container--default .select2-selection--single{
            background-color: #373d43;
        }
        #select2-pista-select2-results{
            color: white;
            background-color: #373d43;
        }
        body > span > span > span.select2-search.select2-search--dropdown{
            background-color: #373d43;
        }
        body > main > div.container.is-max-desktop > div > div > div > div.pistas > span > span.selection > span {
            height: auto;
            padding: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow{
            height: 26px;
            position: absolute;
            top: 13px;
            right: 11px;
            width: 20px;
        }
        .select2-container--default .select2-results__option--selected{
            color: #373d43;
        }
    </style>
@endsection

@section('content')
@if (!auth()->user())
<div class="modal" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="top:25vh">
        <div class="modal-header">
            <h4 class="h4 mb-0">Aviso</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">
          <p class="mb-4">Si ya tenías perfil en la aplicación anterior establece una nueva contraseña desde este enlace para poder acceder y realizar reservas.</p>
          <p class="text-center"><a href="/{{ request()->slug_instalacion }}/forgot-password" class="btn btn-primary">Recuperar contraseña</a></p>
        </div>
      </div>
    </div>
  </div>
@endif

    <div id="url_instalacion" style="display: none">/{{ request()->slug_instalacion }}/{{ request()->deporte }}/</div>
    <section class="hero is-medium">
        <div class="has-text-centered title-div title-pista-section" style="background:linear-gradient(0deg, rgba(36, 36, 36, 0.5), rgba(36, 36, 36, 0.5)), url(/img/deportes/banner-{{ strtolower($pista_selected->tipo) }}.jpg) center;
            background-size:cover;">
            <h1 class="title">{{ $pista_selected->tipo }}</h1>
        </div>
    </section>

    <div class="container is-max-desktop">
        <div class="columns">
            <div class="column is-full">
                <div class="div-reservas">
                    <div class="pistas">
                        @if (count($pistas) > 4)
                            <select name="pista" class="form-select" id="pista-select2">
                                @foreach ($pistas as $pista)
                                    <option value="{{ $pista->id }}" @if (request()->id_pista == $pista->id) selected @endif>{{ $pista->nombre }}</option>
                                @endforeach
                            </select>
                        @else
                            @foreach ($pistas as $index => $pista)
                                <div class="@if ($pista->id == $pista_selected->id) active @endif"><a class=" select-pista"
                                        data-id_pista="{{ $pista->id }}"
                                        href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ $pista->id }}">{{ $pista->nombre }}</a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="calendario-horarios">
                        <div class="navigator">
                            <div class="semanas">
                                <a class="button" href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ $pista_selected->id }}?semana={{ request()->semana == null || request()->semana == 0 ? '-1' : request()->semana-1 }}">
                                    <
                                </a>
                                <a class="button {{ request()->semana == null || request()->semana == 0 ? 'active' : '' }}" href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ $pista_selected->id }}">
                                    Hoy
                                </a> 
                                <a class="button" href="?semana={{ request()->semana == null || request()->semana == 0 ? '1' : request()->semana+1 }}">
                                    >
                                </a> 
                            </div>
                            <div class="calendario">
                                <form id="form-dia" method="get" action="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ $pista_selected->id }}">
                                    <div class="input-group diapicker">
                                        <a href="#" class="btn btn-secondary"><i class="fas fa-calendar"></i></a>
                                        <input type="text" id="dia" class="datepicker date-input form-control" name="dia" value="{{ request()->dia == null ? date('d/m/Y') : request()->dia }}">
                                    </div>
                                </form>
                            </div>
                            <div style="text-transform: capitalize" class="mes">
                                {{ \Carbon\Carbon::parse(iterator_to_array($period)[0])->formatLocalized('%d %b') . ' - ' . \Carbon\Carbon::parse(iterator_to_array($period)[count(iterator_to_array($period))-1])->formatLocalized('%d %b')}}
                            </div>
                        </div>
                    </div>
                    <div class="thead">
                        {{-- <div>
                            <div class="th">Hora</div>
                            <div class="th">6:00 - 7:00</div>
                            <div class="th">7:00 - 8:00</div>
                            <div class="th">8:00 - 9:00</div>
                            <div class="th">9:00 - 10:00</div>
                            <div class="th">10:00 - 11:00</div>
                            <div class="th">11:00 - 12:00</div>
                            <div class="th">12:00 - 13:00</div>
                            <div class="th">13:00 - 14:00</div>
                            <div class="th">14:00 - 15:00</div>
                            <div class="th">15:00 - 16:00</div>
                            <div class="th">16:00 - 17:00</div>
                            <div class="th">17:00 - 18:00</div>
                            <div class="th">18:00 - 19:00</div>
                            <div class="th">19:00 - 20:00</div>
                            <div class="th">20:00 - 21:00</div>
                            <div class="th">21:00 - 22:00</div>
                            <div class="th">22:00 - 23:00</div>
                        </div> --}}
                        @foreach ($period as $fecha)
                            <div class="th" style="text-transform: capitalize">
                                <div>
                                    {{ \Carbon\Carbon::parse($fecha)->formatLocalized('%A') }}<br>{{ $fecha->format('d M') }}
                                </div>
                                @foreach ($pista_selected->horario_con_reservas_por_dia($fecha->format('Y-m-d')) as $item)
                                    @foreach ($item as $intervalo)
                                        <div style="height:{{ $intervalo['height'] }}rem">
                                            <a @if (!$intervalo['valida']) href="#" class="btn-no-disponible" @else href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ $pista_selected->id }}/{{ $intervalo['timestamp'] }}" class="btn-reservar" @endif>
                                                {{ $intervalo['string'] }}
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

@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('.btn-no-disponible').click(function (e) { 
                e.preventDefault();
            });
            $("#myModal").modal('show');
            var input_date = $('#dia').pickadate({
                editable: true,
                selectYears: 100,
                selectMonths: true,
                format: 'dd/mm/yyyy',
                min: false,
                max: false
            });

            var picker = input_date.pickadate('picker');
            $(".diapicker").on("click", function (e) {
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
            
            $('#pista-select2').change(function (e) {
                window.location.href = $('#url_instalacion').html() + $(this).val();
            });
        });
    </script>
@endsection
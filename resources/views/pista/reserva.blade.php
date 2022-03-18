<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="PwZtoQA4Yrgn7creLcg3BTmITL3iYgzoELulzMSV">

    <title>Reserva de pista</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Styles -->
    <link href="https://entradas.aquasierra.es/css/app.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <style>
        .descripcion {
            font-weight: 300;
        }

        select.form-control {
            height: calc(1em + 0.75rem + 2px);
            padding: 0;
            padding-left: 6px;
            font-size: 13px;
            font-weight: bold;
        }

        label.col-form-label {
            font-weight: bold;
        }

        h1 {
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
            color: rgb(52, 70, 147);
            font-weight: bold;
        }

        .horario {
            color: rgb(52, 70, 147);
            font-weight: bold;
        }

        body {
            font-weight: 600;
        }

        .fecha-title {
            font-size: 15px;
            color: rgb(52, 70, 147);
        }

        .navbar-end .navbar-item{
            font-weight: bold;
            color: #4a4a4a;
            padding: 0.5rem 0.75rem;
        }
        .navbar-end .navbar-item.active{
            color: #3273dc;
        }
        .navbar-end .navbar-item:hover{
            color: #3273dc;
            text-decoration: none;
        }
        .navbar{
            background: white;
        }
        .card-body{
            padding: 1.75rem;
        }
        .navbar>.container {
            align-items: center;
            display: flex;
            min-height: 3.25rem;
            width: 100%;
        }
        .navbar-brand{
            align-items: center;
            padding: 10px;
            display: flex;
            flex-shrink: 0;
            min-height: 3.25rem;
        }
        .navbar-brand>.navbar-item{
            padding: 10px;
            cursor: pointer;
        }
        .navbar-burger span:nth-child(1) {
            top: calc(50% - 9px);
        }
        .navbar-burger span:nth-child(2) {
            top: calc(50% - -1px);
        }
        .navbar-burger span:nth-child(3) {
            top: calc(50% - 4px);
        }
        .navbar-burger span {
            background-color: currentColor;
            display: block;
            height: 1px;
            left: calc(50% - 8px);
            position: absolute;
            transform-origin: center;
            transition-duration: 86ms;
            transition-property: background-color,opacity,transform;
            transition-timing-function: ease-out;
            width: 16px;
            top: calc(50% - 1px);
        }
        .navbar-burger {
            color: #4a4a4a;
            cursor: pointer;
            display: block;
            height: 3.25rem;
            position: relative;
            width: 3.25rem;
            margin-left: auto;
        }
        .navbar-burger{
            display: none;
        }
        @media (max-width: 1200px) {
            .navbar>.container{
                display: block;
            }
            a.navbar-item {
                padding: 0;
            }
            a.navbar-item>img{
                max-height: 32px !important;
            }
            .navbar-end{
                display: none;
            }
            .navbar-burger{
                display: block;
            }
        }
    </style>
</head>

<body style="background:linear-gradient(0deg, rgba(36, 36, 36, 0.5), rgba(36, 36, 36, 0.5))@if ($instalacion->id != 4), url(/img/deportes/reserva-{{ strtolower($pista->tipo) }}.jpg) @endif;
    background-size:cover;background-position:bottom">

    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="/{{ request()->slug_instalacion }}">
                    @if (file_exists(public_path() . '/img/'. request()->slug_instalacion .'.png'))
                        <img src="{{ asset('img/'.request()->slug_instalacion.'.png') }}" height="50" />
                    @else
                        <img src="/img/tallerempresarial.png" height="50" />
                    @endif
                </a>

                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false"
                    data-target="navbarBasicExample">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navbarBasicExample" class="navbar-menu">
                <div class="navbar-end">
                    <a href="/{{ request()->slug_instalacion }}"
                        class="navbar-item {{ request()->is(request()->slug_instalacion) ? 'active' : '' }}"> Inicio
                    </a>
                    {{-- <a href="/{{ request()->slug_instalacion }}" class="navbar-item"> Reservar </a> --}}
                    <a href="/{{ request()->slug_instalacion }}" class="navbar-item"> Normas </a>
                    @if (\Auth::check())
                        <a href="/{{ request()->slug_instalacion }}/mis-reservas"
                            class="navbar-item {{ request()->is(request()->slug_instalacion . '/mis-reservas') ? 'active' : '' }}"><i
                                class="fas fa-book-open mr-2"></i> Mis reservas </a>
                        <a href="/{{ request()->slug_instalacion }}/perfil" class="navbar-item"><i
                                class="fas fa-user mr-2"></i> Mi perfil </a>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="navbar-item">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                            <i class="fas fa-power-off mr-2"></i> Cerrar sesión
                        </a>
                    @else
                        <a href="{{ route('login_instalacion', ['slug_instalacion' => request()->slug_instalacion]) }}"
                            class="navbar-item"><i class="fas fa-sign-in-alt mr-2"></i> Acceder</a>

                    @endif
                </div>
            </div>
        </div>
    </nav>
    <div id="app">
        <section class="hero is-medium">
            <div class="has-text-centered title-div title-reserva-section" style="padding-top:4.5rem;padding-bottom:3.5rem;margin-bottom:0">
                <h1 class="title text-center mb-0">{{ $pista->nombre }}</h1>
            </div>
        </section>
        <main>
            <div class="container pb-5">
                <div class="row justify-content-center">
                    <div class="col-sm-7">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h1><i class="far fa-calendar-check mr-2"></i> Confirmar reserva</h1>
                                    <div class="fecha-title">{{ date('d-m-Y', $fecha) }}</div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col">
                                    </div>
                                    <div class="col">
                                    </div>
                                </div> --}}
                                <p class="descripcion">
                                    Estás reservando para <em>{{ $pista->nombre }}</em>. Por favor, revise y confirme
                                    los datos siguientes.
                                </p>
                                <form method="POST"
                                    action="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ request()->id_pista }}/{{ request()->timestamp }}/reserva">
                                    @csrf
                                    <input type="hidden" name="secuencia" id="secuencia" value="{{ $secuencia }}">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label py-0">Tipo:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $pista->tipo }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label py-0">Espacio:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $pista->nombre }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label py-0">Fecha:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('d/m/Y', $fecha) }} (<span
                                                    class="horario">{{ date('H:i', $fecha) }} a <span
                                                        class="hfin">{{ date('H:i', strtotime(date('H:i', $fecha) . " +{$secuencia} minutes")) }}</span></span>)
                                            </div>
                                        </div>
                                    </div>{{-- {{ dd($number) }} --}}
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label py-0">Tarifa:</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="tarifa" id="tarifa">
                                                @if ($pista->allow_more_res)
                                                    @for ($i = 1; $i < $number+1; $i++)
                                                        <option
                                                            data-hfin="{{ date('H:i', strtotime(date('H:i', $fecha) . ' +' . $secuencia * $i . ' minutes')) }}"
                                                            value="{{ $i }}">RESERVA {{ $secuencia * $i }} MINUTOS</option>
                                                    @endfor
                                                @else
                                                    <option
                                                        data-hfin="{{ date('H:i', strtotime(date('H:i', $fecha) . ' +' . $secuencia  . ' minutes')) }}"
                                                        value="1">RESERVA {{ $secuencia }} MINUTOS</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    @if (auth()->user()->instalacion->configuracion->observaciones)
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label py-0">Observaciones:</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="observaciones" rows="3"></textarea>
                                            </div>
                                        </div>
                                    @endif
                                    @foreach ($pista->all_campos_personalizados as $item)
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label py-0">{{ $item->label }}:</label>
                                                <div class="col-sm-9">
                                                    @if ($item->tipo == 'textarea')
                                                        <textarea class="form-control" name="campo_adicional[{{ $item->id }}]" rows="3" {{ $item->required ? 'required' : '' }}></textarea>
                                                    @elseif($item->tipo == 'select')
                                                        <select class="form-control" name="campo_adicional[{{ $item->id }}]">
                                                            @foreach (unserialize($item->opciones) as $option)
                                                                <option value="{{ $option }}">{{ $option }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="{{ $item->tipo }}" name="campo_adicional[{{ $item->id }}]" class="form-control" placeholder="{{ $item->label }}" {{ $item->required ? 'required' : '' }}>
                                                    @endif
                                                </div>
                                            </div>
                                    @endforeach
                                    <div class="form-group row mt-4">
                                        <label class="col-sm-3 col-form-label py-0"></label>
                                        <div class="col-sm-9 text-right d-flex justify-content-end" style="gap: 14px">
                                            <button type="submit" class="btn btn-info text-white">
                                                <i class="fas fa-check"></i>
                                                <div>Reservar</div>
                                            </button>
                                            <a href="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ request()->id_pista }}" class="cancel btn btn-danger">
                                                <i class="fas fa-times"></i>
                                                <div>Cancelar</div>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        $(document).ready(function() {

            $('#tarifa').change(function(e) {
                e.preventDefault();
                $('.hfin').html($(this).find(`[value="${$(this).val()}"]`).data('hfin'));
            });
        });
    </script>

</body>

</html>

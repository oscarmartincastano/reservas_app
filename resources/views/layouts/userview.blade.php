<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bulma Version 0.9.0-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bulma@0.9.0/css/bulma.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/themes/default.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/themes/default.date.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/picker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/picker.date.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/translations/es_ES.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js"
        integrity="sha512-mULnawDVcCnsk9a4aG1QLZZ6rcce/jSzEGqUkeOLy0b6q0+T6syHrxlsAGH7ZVoqC93Pd0lBqd6WguPWih7VHA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link  href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css"  rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js" integrity="sha512-6UofPqm0QupIL0kzS/UIzekR73/luZdC6i/kXDbWnLOJoqwklBK6519iUnShaYceJ0y4FaiPtX/hRnV/X/xlUQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script  src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"  integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="  crossorigin="anonymous"  referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>

    @yield('style')

    <style>
        .active {
            color: #3273dc;
        }

        a.button.active {
            border: 1px solid #3273dc;
            color: #3273dc;
        }

        footer.footer {
            background-color: white !important;
        }

        .modal-backdrop {
            z-index: 39 !important;
        }

        .navbar-burger:hover {
            background-color: transparent;
        }

        .navbar-brand,
        .navbar-tabs {
            align-items: center;
            padding: 10px;
        }

        nav.navbar {
            box-shadow: rgba(0, 0, 0, 0.1) 0px -4px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px;
        }

        .footer {
            padding: 0rem 1.5rem 2rem;
        }

        body>nav.navbar .container {
            margin: 0;
        }

        @media (max-width: 600px) {
            a.navbar-item {
                padding: 0;
            }

            a.navbar-item>img {
                max-height: 32px !important;
            }

            body>nav.navbar {
                position: fixed;
                top: 0;
                width: 100%;
            }

            body>main {
                margin-top: 73px;
            }

            h1.titulo-pagina {
                margin-top: 103px !important;
            }
        }

        @media (max-width: 1025px) {
            .navbar-end>a.navbar-item {
                padding: 13px;
            }

            body>nav.navbar {
                display: block;
                padding: 0;
            }

            nav.navbar .navbar-brand {
                justify-content: space-between;
                width: 100%;
            }

            .navbar-end>a:first-child {
                border-top: 1px solid #ccc;
            }
        }

        @media (min-width: 1025px) {
            .contenedor-navbar {
                width: 100%;
                display: flex;
            }
        }

        .modal-open {
            padding-right: 0 !important;
        }

        @media (max-width: 600px) {
            .thead {
                display: flex;
                justify-content: flex-start;
                padding: 20px;
                flex-direction: row;
                flex-grow: revert;
                overflow: auto;
            }

            .thead {
                display: flex;
                justify-content: flex-start;
                padding: 20px;
                flex-direction: row;
                flex-grow: revert;
                overflow: auto;
            }

            .thead>div.th:not(:first-child) {
                display: block;
            }

            .thead>div {
                min-width: 145px;
            }
        }
    </style>

    <title>Instalación - @yield('pagename')</title>
</head>

<body>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="contenedor-navbar">
            <div class="navbar-brand">
                <a class="navbar-item" href="/{{ request()->slug_instalacion }}" style="padding: 10px">

                    @if (file_exists(public_path() . '/img/ceco.png'))
                        <img src="{{ asset('img/' . request()->slug_instalacion . '.png') }}"
                            style="max-height: 50px" />
                    @else
                        <img src="/img/tallerempresarial.png" style="max-height: 50px" />
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
                        class="navbar-item {{ request()->is('/') ? 'active' : '' }}">Inicio</a>
                    {{-- <a href="" class="navbar-item">Reservar</a> --}}
                    @if (App\Models\Instalacion::where('slug', request()->slug_instalacion)->first()->html_normas)
                        <a href="/{{ request()->slug_instalacion }}/normas" class="navbar-item">Normas</a>
                    @endif
                    @if (\Auth::check())
                        @if (auth()->user()->rol == 'admin' || auth()->user()->rol == 'worker')
                            <a href="/{{ request()->slug_instalacion }}/admin" class="navbar-item"><i
                                    class="fas fa-unlock-alt mr-2"></i>Administración</a>
                        @else
                            <a href="/{{ request()->slug_instalacion }}/mis-reservas"
                                class="navbar-item {{ request()->is(request()->slug_instalacion . '/mis-reservas') ? 'active' : '' }}"><i
                                    class="fas fa-book-open mr-2"></i>Mis reservas</a>
                            <a href="/{{ request()->slug_instalacion }}/perfil" class="navbar-item"><i
                                    class="fas fa-user mr-2"></i>Mi perfil</a>
                        @endif
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="navbar-item">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                            <i class="fas fa-power-off mr-2"></i>Cerrar sesión
                        </a>
                    @else
                        <a href="{{ route('login_instalacion', ['slug_instalacion' => request()->slug_instalacion]) }}"
                            class="navbar-item"><i class="fas fa-sign-in-alt mr-2"></i>Acceder</a>
                        @if (request()->slug_instalacion == 'alminar')
                            <a href="{{ route('register_user_instalacion', ['slug_instalacion' => request()->slug_instalacion]) }}"
                                class="navbar-item"><i class="fa-solid fa-user-plus mr-2"></i>Registrarse</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
        {{-- @if (!auth()->user() && request()->slug_instalacion == 'alminar')
            @php Cookie::queue(Cookie::make('modal', 'true', 10000000)) @endphp
           <div class="modal" id="myModal" tabindex="-1" role="dialog" style="padding-right: 0">
               <div class="modal-dialog" role="document">
               <div class="modal-content m-0" style="top:25vh">
                   <div class="modal-header">
                   <h4 class="h4 mb-0">Aviso</h4>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
                   </div>
                   <div class="modal-body p-4">
                   <p class="mb-4 text-center" style="font-size: 17px">El sistema ha cambiado y todos los usuarios tienen que recuperar su contraseña. Puedes hacerlo desde este link:</p>
                   <p class="text-center pt-2"><a href="{{ route('forgot_password_instalacion', ['slug_instalacion' =>request()->slug_instalacion]) }}" class="btn btn-success">Recuperar contraseña</a></p>
                   </div>
               </div>
               </div>
           </div>
        @endif --}}
        @if (auth()->user() && request()->slug_instalacion == 'alminar' && !auth()->user()->direccion)
            {{-- @php Cookie::queue(Cookie::make('modal', 'true', 10000000)) @endphp --}}
            <div class="modal" id="myModal" tabindex="-1" role="dialog" style="padding-right: 0">
                <div class="modal-dialog" role="document">
                    <div class="modal-content m-0" style="top:25vh">
                        <div class="modal-header">
                            <h4 class="h4 mb-0">Introduce dirección</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-4">
                            <p class="mb-4 text-center" style="font-size: 17px">Nos falta el dato de tu<b>DIRECCIÓN
                                    COMPLETA</b>para el funcionamiento de la aplicación. Añade tu dirección desde aquí
                                o tu cuenta puede ser bloqueada:</p>
                            <form method="POST" action="/{{ request()->slug_instalacion }}/perfil/edit">
                                @csrf
                                <p class="text-center py-2"><input type="text" class="form-control"
                                        name="direccion" placeholder="Dirección..." required></p>
                                <p class="text-center pt-3"><button type="submit" class="btn btn-primary">Añadir
                                        dirección</button></p>
                            </form>
                            {{-- <p class="text-center mt-2"><a href="#"data-dismiss="modal" aria-label="Close" class="btn btn-primary">Entendido</a></p> --}}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>
    <footer class="footer">

        @php
            $instalacion = \App\Models\Instalacion::where('slug', request()->slug_instalacion)->first();
            $sponsors = $instalacion->sponsors;
        @endphp
        @isset($sponsors)
            @include('sponsors', ['sponsors' => $sponsors])
        @endisset
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script>
        $("#myModal").modal('show');
        // Hamburger menu functionality
        document.addEventListener('DOMContentLoaded', () => {
            const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
            if ($navbarBurgers.length > 0) {
                $navbarBurgers.forEach(el => {
                    el.addEventListener('click', () => {
                        const target = el.dataset.target;
                        const $target = document.getElementById(target);
                        el.classList.toggle('is-active');
                        $target.classList.toggle('is-active');
                    });
                });
            }
        });
    </script>
    @yield('script')
</body>

</html>

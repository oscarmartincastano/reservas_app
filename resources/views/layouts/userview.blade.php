<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../images/fav_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bulma Version 0.9.0-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bulma@0.9.0/css/bulma.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/themes/default.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/themes/default.date.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js" integrity="sha512-mULnawDVcCnsk9a4aG1QLZZ6rcce/jSzEGqUkeOLy0b6q0+T6syHrxlsAGH7ZVoqC93Pd0lBqd6WguPWih7VHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @yield('style')

    <style>
        .active{
            color: #3273dc;
        }
        
        a.button.active{
            border: 1px solid #3273dc;
            color: #3273dc;
        }
        footer.footer{
            background-color: white !important;
        }
        .modal-backdrop {
            z-index: 39 !important;
        }
        .navbar-burger:hover {
            background-color: transparent;
        }
        .navbar-brand, .navbar-tabs{
            align-items: center;
            padding: 10px;
        }
        .navbar-brand{
            box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px;
        }
        @media (max-width: 600px) {
            a.navbar-item {
                padding: 0;
            }
            a.navbar-item>img{
                max-height: 32px !important;
            }
        }
        @media (max-width: 1025px) {
            .navbar-end>a.navbar-item{
                padding: 13px;
            }
        }
    </style>

    <title>Instalación - @yield('pagename')</title>
</head>

<body>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="/{{ request()->slug_instalacion }}" style="padding: 10px">
                    
                    @if (file_exists(public_path() . '/img/ceco.png'))
                        <img src="{{ asset('img/'.request()->slug_instalacion.'.png') }}" style="max-height: 50px" />
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
                    <a href="/{{ request()->slug_instalacion }}" class="navbar-item {{request()->is(request()->slug_instalacion) ? 'active' : '' }}"> Inicio </a>
                    {{-- <a href="/{{ request()->slug_instalacion }}" class="navbar-item"> Reservar </a> --}}
                    <a href="/{{ request()->slug_instalacion }}" class="navbar-item"> Normas </a>
                    @if (\Auth::check())
                        @if (auth()->user()->rol != 'admin')
                            <a href="/{{ request()->slug_instalacion }}/mis-reservas" class="navbar-item {{request()->is(request()->slug_instalacion . '/mis-reservas') ? 'active' : '' }}"><i class="fas fa-book-open mr-2"></i> Mis reservas </a>
                            <a href="/{{ request()->slug_instalacion }}/perfil" class="navbar-item"><i class="fas fa-user mr-2"></i> Mi perfil </a>
                        @else
                            <a href="/{{ request()->slug_instalacion }}/perfil" class="navbar-item"><i class="fas fa-unlock-alt mr-2"></i> Administración </a>
                        @endif
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="navbar-item">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <i class="fas fa-power-off mr-2"></i> Cerrar sesión
                        </a>
                    @else
                        <a href="{{ route('login_instalacion', ['slug_instalacion' => request()->slug_instalacion]) }}" class="navbar-item"><i class="fas fa-sign-in-alt mr-2"></i> Acceder</a>

                    @endif
                </div>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
    <footer class="footer">
        {{-- <div class="container">
            <div class="content has-text-centered">
                <p>
                    <a href="https://bulma.io">
                        <img src="https://bulma.io/images/made-with-bulma.png" alt="Made with Bulma" width="128"
                            height="24">
                    </a>
                </p>
            </div>
        </div> --}}
    </footer>
    <script>
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

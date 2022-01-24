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
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    @yield('style')

    <style>
        .active{
            color: #3273dc;
        }
    </style>

    <title>Instalación - @yield('pagename')</title>
</head>

<body>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="/{{ request()->slug_instalacion }}">
                    <img src="{{ asset('img/cecoworking.png') }}" height="100" />
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
                        <a href="/{{ request()->slug_instalacion }}/mis-reservas" class="navbar-item {{request()->is(request()->slug_instalacion . '/mis-reservas') ? 'active' : '' }}"><i class="fas fa-book-open mr-2"></i> Mis reservas </a>
                        <a href="/{{ request()->slug_instalacion }}/perfil" class="navbar-item"><i class="fas fa-user mr-2"></i> Mi prefil </a>
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
</body>

</html>

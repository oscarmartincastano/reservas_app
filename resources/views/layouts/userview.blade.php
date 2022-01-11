<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../images/fav_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <!-- Bulma Version 0.9.0-->
    <link rel="stylesheet" href="https://unpkg.com/bulma@0.9.0/css/bulma.min.css" />
    <title>Instalación - @yield('pagename')</title>
    <style type="text/css">
        html,
        body {
            font-family: 'Open Sans';
        }
        .navbar-item img {
            max-height: none;
        }
        .div-reservas{
            box-shadow: 0px 0px 20px 0px rgb(204 204 204);
        }
        .div-reservas .pistas{
            background: rgba(55,61,67,1);
            display: flex;
            justify-content: center;
            border-radius: 7px 7px 0 0;
        }
        .div-reservas .pistas a {
            color: #828282;
            font-size: 20px;
        }
        .div-reservas .pistas .active a {
            color: white;
        }
        .div-reservas .pistas a:hover {
            color: white;
        }
        .div-reservas .pistas div {
            margin: 15px 0;
            padding: 0 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 220px;
        }
        .div-reservas .pistas div:not(:first-child) {
            border-left: 1px solid #ccc;
        }
        .div-reservas .calendario-horarios{
            padding: 20px;
        }
        .navigator{
            display: flex;
            justify-content: space-between;
        }
        .table{
            border: 1px solid #eee;
            margin: 0 0 15px;
            text-align: center;
            width: 100%;
        }
        .tabla-reservas{
            padding: 20px;
        }
        .tabla-reservas th, .tabla-reservas td{
            border: 2px solid white;
        }

        .tabla-reservas td{
            height: 1.5rem;
            background: #52b5f7;
            vertical-align: middle;
            padding-top: 0;
            padding-bottom: 0;
        }
        .tabla-reservas td a.btn-reservar{
            background: #52b5f7;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            font-size: 14px;
        }
        .tabla-reservas td:hover, .tabla-reservas td:hover a, .tabla-reservas td.reserved,.tabla-reservas td.reserved a{
            background: #0077c7 !important;
        }

        .tabla-reservas td.reserved a{
            font-weight: bold;
        }
    </style>
</head>

<body>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="/">
                    <img src="{{ asset('img/matagrande.jpg') }}" width="75" />
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
                    <a class="navbar-item"> Inicio </a>
                    <a class="navbar-item"> Reservar </a>
                    <a class="navbar-item"> Normas </a>
                    <a class="navbar-item"> Acceder</a>
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

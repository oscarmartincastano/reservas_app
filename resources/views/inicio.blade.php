<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta nombre="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
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
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
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
        nav.navbar{
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
            a.navbar-item>img{
                max-height: 32px !important;
            }
            body>nav.navbar {
                position: fixed;
                top: 0;
                width: 100%;
            }
            body>main{
                margin-top: 73px;
            }
            h1.titulo-pagina {
                margin-top: 103px !important;
            }
        }
        @media (max-width: 1025px) {
            .navbar-end>a.navbar-item{
                padding: 13px;
            }
            body>nav.navbar {
                display: block;
                padding: 0;
            }
            nav.navbar .navbar-brand{
                justify-content: space-between;
                width: 100%;
            }
            .navbar-end>a:first-child {
                border-top: 1px solid #ccc;
            }
        }
        @media (min-width: 1025px) {
            .contenedor-navbar{
                width: 100%;
                display: flex;
            }
        }
    </style>

    <title>Gestión instalación</title>
</head>

<body>
<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="contenedor-navbar">
        <div class="navbar-brand">
            <a class="navbar-item" href="/" style="padding-left: 10px;padding-bottom: 0px;padding-top: 0px;">


                <img src="/img/logo.png" style="max-height: 80px" />

            </a>

            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false"
               data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

    </div>
</nav>
<main>
    <div class="row" style="background-image: linear-gradient(160deg, #49b6ea 53%, #473bf6 100%);padding: 3%;">

        <h1 class="title text-center mt-5 titulo-pagina" style="color:white;">GESTIÓN DE PISTAS DEPORTIVAS</h1>

    </div>
    <div class="container">
        <div class="row" style="place-content: center">

            <p class="mt-5 mb-5" style="font-size: 1.3em; text-align: center;"> La mejor aplicación para gestionar alquileres de instalaciones deportivas, al mejor precio.</p>
            <div class="col-md-3" style="text-align: center;"><img src="/img/user-block.png" ><h5  class=" text-center mt-5 titulo-pagina" style="font-size: 1.5em;">Reservas Continuas</h5>
                <p style="text-align: justify"> Haz fácilmente reservas continuas para bloquear pistas e igual puedes eliminar éstas reservas si un día quieres alquilar la pista deportiva libremente.</p></div>
            <div class="col-md-3" style="text-align: center;"><img src="/img/precio-dinamico.png" ><h5  class="text-center mt-5 titulo-pagina" style="font-size: 1.5em;">Precios Dinámicos</h5>
                <p style="text-align: justify">Juega con los precios, una aplicación flexible que te permiten cambiar los precios en función de las necesidades de la instalación sin tediosos procesos.</p></div>
            <div class="col-md-3" style="text-align: center;"><img src="/img/reservas-periodicas.png" ><h5  class="text-center mt-5 titulo-pagina" style="font-size: 1.5em;">Bloqueo de usuarios</h5>
                <p style="text-align: justify">Si tienes usuarios que no se presentan o que no hacen un buen uso de la aplicación puedes bloquearlos desde tu panel de administración</p></div>
        </div>


    </div>
</main>
<div class="container">
    <h2 class="title text-center titulo-pagina" style="margin-top: 70px !important;">Descargar App</h2>
    <div class="divider mb-3" style="padding: 5px !important;">
        <div></div>
    </div>
    <div class="content has-text-centered">

        <div class="d-flex flex-row justify-content-center">
            <div>
                <a href="https://play.google.com/store/apps/details?id=com.taller.gestincomunidad&hl=en_US&gl=ES"><img src="{{ asset('img/google-play.png') }}"></a>
                <a href="https://apps.apple.com/es/app/gesti%C3%B3n-comunidades/id1519070429"><img src="{{ asset('img/app-store.png') }}"></a>
            </div>
        </div>
    </div>
</div>
<footer class="footer mt-5" style="background:#52b5f7 !important; box-shadow:rgb(0 0 0 / 17%) 0px -10px 15px -3px, rgb(0 0 0 / 5%) 0px 4px 6px -2px; padding: 0rem 1.5rem 0rem;     position: absolute;
    width: 100%; bottom: 0" >

    <div class="d-flex justify-content-center">
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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

<style>
    .row{
        justify-content: center;
    }
    .row>div {
        width: 80%;
    }

    .separador-linea {
        height: 1px;
        width: 100%;
        background-color: #cfd3d6;
        position: absolute;
        left: 0;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: rgb(250, 250, 250);
        height: 80px;
        padding: 16px 16px 16px 32px;
    }

    main {
        background: #fafafa;
    }

    .titulo-instalacion {
        position: relative !important;
        background-color: #0e2433 !important;
        background-repeat: no-repeat !important;
        background-position: 50% !important;
        background-size: 100% auto !important;
    }


    .contenido-titulo {
        margin: 0 auto;
        padding: 32px 0 40px;
        color: white;
        text-shadow: 1px 1px 1px rgb(0 0 0 / 50%);
    }

    .contenido-titulo h1 {
        font-size: 64px;
        font-weight: 600;
        line-height: 1.4;
        margin: 0;
        padding: 0;
    }

    .contenido-titulo .direccion {
        font-size: 16px;
        line-height: 1.4;
        text-indent: 4px;
    }

    .shadow-box {
        box-shadow: -1px 5px 17px 0 rgb(0 0 0 / 10%);
    }

    .card {
        background-color: white !important;
    }

    .contenido-principal {
        padding: 50px 0;
    }

    .filtros {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        border-bottom: 1px solid #f2f2f2;
    }

    .filtros .form-control {
        border: 0 !important;
        border-right: 1px solid #f2f2f2 !important;
        font-weight: bold;
    }

    .pistas-horario .celda {
        text-align: center !important;
        font-size: 1em;
        padding: 0 0 0 0px;
        font-weight: bold;
    }

    .tabla-horario {
        display: flex;
        position: relative;
        flex-wrap: wrap
    }

    .tabla-horario .celda {
        text-align: center;
    }

    .horas-tramos {
        pointer-events: none;
        color: rgba(1, 1, 1, .4);
        display: flex;
    }

    .celda {
        border: 1px solid #f2f2f2;
    }

    .pistas-horario {
        min-width: 160px;
    }

    .pistas-horario .celda {
        text-align: left;
    }

    .horas-tramos-pistas {
        position: relative;
    }

    .horas-tramos-pistas>.slots-pista {
        position: relative;
    }

    .slots-pista {
        height: 40px;
        position: relative;
    }

    .slots-horas {
        display: flex;
    }

    .slots-pista .slot {
        padding: 1px;
    }

    .slots-pista .slot div {
        height: 100%;
        border-radius: 5px;
        background-color: #198754;
        cursor: pointer;
    }

    .slot-reserva>div:hover {
        background-color: #3fbf83;
    }

    .block-before {
        border-right: 2px solid #198754;
        position: absolute;
        bottom: 0;
        background-color: #f9f9f9;
        background-image: linear-gradient(45deg, transparent 25%, #fff 0, #fff 50%, transparent 0, transparent 75%, #fff 0, #fff);
        z-index: 800;
    }

    .tramos {
        position: relative;
    }

    .select2-container--default .select2-selection--single {
        border: 0 !important;
        font-weight: bold;
    }
    
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background: #dddddd;
        color: #212529;
    }

    .select2-results__option--selectable {
        font-weight: 500;
    }

    .select2-container--default .select2-results>.select2-results__options {
        max-height: unset;
        border-radius: 0 0 3px 3px;
        border: 1px solid #f2f2f2;
        box-shadow: 0 24px 32px 0 rgb(1 1 1 / 5%);
    }

    .select2-dropdown {
        border: 0;
    }

    .tooltip-inner {
        white-space: pre-wrap;
        padding: 10px;
    }

    .btn-no-disponible {
        font-size: 0.7em;
        background: grey !important;
        cursor: auto !important;
    }

    .btn-reservado {
        font-size: 0.7em;
        background: #8f0d1a !important;
        cursor: auto !important;
    }


    .btn-no-disponible>a {

        text-decoration: none !important;
    }

    .page-header a {
        text-decoration: none;
    }

    #ver-mas-zonas {
        display: none;
    }

    #ver-zonas-anteriores {
        display: none;
    }

    #logo {
        width: 80px;
        margin-left: 2%;
    }

    #logo2 {
        width: 160px;
    }

    footer {
        background-color: #203a74;
    }

    footer h6 {
        color: white;
    }

    footer ul>li {
        display: flex;
        gap: 4%;
        align-items: center;
        color: white;
    }

    #lista1Footer>li {
        padding: 5px;
        border-bottom: #ef7d1a8a 1px solid;
    }

    #lista2Footer>li {
        line-height: 1.75;
    }

    #lista3Footer>li {
        line-height: 2;
    }

    footer ul>li>a {
        color: white;
        text-decoration: none;
    }

    footer ul>li>a:hover {
        text-decoration: none;
        color: white;
    }

    footer>section:first-of-type {
        margin-bottom: 20px;
    }

    .imagenFooter {
        width: 100%;
    }

    #redes {
        gap: 5px;
    }

    #divLogos {
        align-self: center;
    }

    @media (max-width: 768px) {

        footer.p-5 {
            padding: 3rem 0 3rem 0 !important;
            box-sizing: border-box;
        }

        footer>section>div {
            padding-right: 0;
        }

        #divNuestroHorario {
            background-color: white;
            padding: 10px 0 10px 0;
        }

        #divNuestroHorario>strong {
            color: #203a74 !important;
        }

        #divNuestroHorario>ul>li {
            color: #203a74;
        }

        #lista2Footer>li:nth-child(1)>strong,
        #lista2Footer>li:nth-child(3)>strong,
        #lista2Footer>li:nth-child(5)>a>span {
            color: #203a74 !important;
        }



        footer>section>div.col-md-3 {
            padding-left: 0;
            padding-right: 0;
        }

        footer>section>div.col-md-2 {
            padding-left: 0;
            padding-right: 0;
        }

        footer>section>div.col-xl-2 {
            padding-left: 0;
            padding-right: 0;
        }

        footer>section {
            gap: 20px;
        }

        #divLogos {
            align-items: center !important;
        }
    }

    @media(max-width:500px) {
        #logo2 {
            display: none;
        }

        #divLogos {
            align-items: center !important;
        }
    }

    /* .card-reserva {
    max-width: 860px;
} */
    /* .select-fecha, #alt-select-fecha {
    max-width: 195px;
} */
    @media(max-width: 992px) {
        .tabla-horario {
            display: block !important;
        }

        .pistas-horario .celda {
            text-align: center !important;
            font-size: 0.7em;
        }

        .pistas-horario {
            background: lightgray;
            display: flex !important;
            position: relative;
            min-width: 160px !important;
        }

        .horas-tramos-pistas>div, .horas-tramos-pistas>div>div, .pistas-horario .celda:not(:first-child) {
        width: 100% !important;
    }

    .tramos {
        display: flex !important;
    }

    .horas-tramos {
        display: block !important;
    }

    .horas-tramos-pistas>.slots-pista {
    position: relative;
}

    .horas-tramos-pistas {
        display: flex !important;
        width: 100% !important;
    }

    .slots-horas {
        display: block !important;
    }
    .horas-tramos-pistas .celda {
        height: 40px !important;
        width: 100% !important;
    }

        .block-before {
            display: none !important;
        }

        .pistas-horario .celda:first-child {
            padding: 19px;
        }

        h1 {
            font-size: 2rem !important;
        }

        .contenido-titulo {
            padding: 16px;
        }

        .card-reserva {
            width: auto !important;
        }

        .page-header>div {
            display: none;
        }

        .filtros>div {
            text-align: center;
            width: 100%
        }

        .show-responsive {
            display: block !important;
        }

        .slot-reserva a {
            display: flex !important;
            justify-content: center;
            align-items: center;
        }

        .contenido-principal {
            padding: 50px 15px;
        }

        .btn-no-disponible{
            background: grey !important;
        }
    }




    .show-responsive {
        display: none;
        color: white;
    }

    .leyenda {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 8px;
    }

    .leyenda div {
        color: rgba(1, 1, 1, .4);
        display: flex;
        align-items: center;
        margin-left: 16px;
        cursor: default;
    }

    .leyenda div::before {
        content: "";
        display: block;
        width: 12px;
        height: 12px;
        border: 1px solid #f2f2f2;
        margin-right: 8px;
        border-radius: 4px;
    }

    .disponible::before {
        background-color: #198754;
    }

    .reservada::before {
        background-color: #8f0d1a;
    }

    .no-disponible::before {
        background-color: grey;
    }

    .list-tags {
        list-style: none;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
    }

    .list-tags li {
        display: flex;
        align-items: center;
        margin: 4px;
        padding: 0 8px;
        height: 28px;
        line-height: 28px;
        font-size: 12px;
        font-weight: 500;
        background-color: #e7e9eb;
        border-radius: 14px;
        white-space: nowrap;
        color: #000;
        border: 0;
        cursor: default;
    }

    .group-horario li {
        display: flex;
        justify-content: space-between;
    }

    .group-horario li>div {
        font-weight: 500;
    }

    .card-info p {
        margin-bottom: 0.25rem;
    }

    #ui-datepicker-div {
        z-index: 1000 !important;
        background: white !important;
        padding: 12px;
    }

    #ui-datepicker-div * {
        background: white;
        font-family: Arbeit, sans-serif;
    }

    .ui-datepicker-header {
        border: 0;
    }

    .ui-datepicker-title {
        color: black;
    }

    .ui-datepicker-calendar thead tr {
        font-size: 14px;
        font-weight: 400;
        color: rgba(1, 1, 1, .6);
        border-bottom: 1px solid rgba(1, 1, 1, .1);
    }

    body {
        overflow-x: hidden;
    }

    .ui-datepicker-calendar tbody tr td>* {
        border: 0 !important;
        font-size: 16px;
        width: 100%;
        height: 100%;
        border-radius: 40px;
        color: rgb(47, 51, 51);
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    td .ui-state-active {
        color: #fff !important;
        background-color: #335fff !important;
    }

    .ui-datepicker-calendar tbody tr td {
        height: 40px;
        width: 44px;
    }

    .ui-state-default,
    .ui-widget-content .ui-state-default,
    .ui-widget-header .ui-state-default,
    .ui-button,
    html .ui-button.ui-state-disabled:hover,
    html .ui-button.ui-state-disabled:active {
        color: rgb(47, 51, 51);
        font-weight: 500;
    }

    .ui-datepicker-next-hover {
        border: 0;
    }

    .ui-icon,
    .ui-widget-content .ui-icon {
        background-image: url(https://code.jquery.com/ui/1.12.1/themes/ui-lightness/images/ui-icons_222222_256x240.png) !important;
    }

    [data-handler="next"] span {
        transform: rotate(90deg);
    }

    .ui-datepicker-prev span {
        transform: rotate(-90deg);
    }

    .ui-widget-header .ui-corner-all {
        border-radius: 45px;
        border: 1px solid #335fff;
    }

    .select-fecha {
        cursor: pointer;
        position: relative;
    }

    .select-fecha::-webkit-calendar-picker-indicator {
        display: none;
        -webkit-appearance: none;
    }

    .select-fecha:focus::before {}

    .select-fecha::before,
    .div-alt-select-fecha::before {
        content: "";
        border-color: #0d6efd transparent transparent transparent;
        border-style: solid;
        border-width: 5px 4px 0 4px;
        height: 0;
        left: 88%;
        margin-left: -4px;
        margin-top: -2px;
        position: absolute;
        top: 50%;
        width: 0;
        transition: all 0.4s;
        z-index: 201;
    }

    body>main>div>div>div.col-md-9>div.card.shadow-box.card-reserva.mb-4>div.filtros.p-0.d-flex>div:nth-child(1)>span>span.selection>span>span.select2-selection__arrow>b {
        border-color: #0d6efd transparent transparent transparent !important;
    }

    .select-fecha:focus::before {
        transform: rotate(180deg);
    }

    .loader-horario {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
        position: absolute;
        width: 100%;
        background: white;
        z-index: 800000;
    }

    .loader-horario {
        font-size: 18px;
        font-weight: 500;
        line-height: 1.2;
    }

    header .page-header a.px-3 {
        color: rgb(47, 51, 51);
        font-weight: 500;
    }

    .page-header {
        background: white;
    }

    header .page-header {
        box-shadow: 0 8px 24px 0 rgb(47 51 51 / 10%);
    }

    .fecha_inicio_evento {
        font-size: 28px;
        font-weight: 600;
        padding-bottom: 10px;
        border-bottom: solid 1px #eaeaea;
    }

    .num-dia-evento {
        color: #335fff;
    }

    .titulo-evento {
        text-transform: uppercase;
        font-weight: 600;
        font-size: 17px;
        margin-bottom: 4px;
    }

    .cierre-inscrp {
        font-size: 14px;
    }

    .item-evento {
        border: solid 1px #eaeaea;
        border-left-color: #6EDE29;
        margin-bottom: 3px;
    }

    .contenido-evento {
        padding: 10px 0;
    }

    .post-header {
        display: flex;
        justify-content: center;
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        box-shadow: 0 8px 24px 0 rgb(47 51 51 / 10%);
        z-index: 10;
        background-color: #fff;
        border-top: 1px solid rgba(1, 1, 1, .05);
    }

    .menu-header {
        display: flex;
        align-items: center;
        font-size: 16px;
        flex-wrap: wrap;
        padding: 10px;
        gap: 20px;
        justify-content: center;
    }

    .menu-header a {
        display: block;
        padding: 1em 0;
        line-height: 1em;
        border-bottom: 2px solid transparent;
        text-decoration: none;
        opacity: .6;
        transition: all .15s;
        position: relative;
        color: black;
    }

    .menu-header a.active {
        opacity: 1;
        border-bottom-color: #335fff;
    }

    @media (max-width: 650px) {
        .fecha_inicio_evento {
            display: block !important;
        }

        .menu-main-header {
            position: absolute;
            top: 79px;
            background: white;
            z-index: 200;
            width: 100%;
            left: 0;
            z-index: 210;
        }

        .menu-main-header>div {
            flex-direction: column;
            width: 100%;
        }

        .menu-main-header .px-3 {
            padding-left: 2rem !important;
            padding-top: 1rem;
            padding-bottom: 1rem;
            border: 1px solid #dee2e6 !important;
            opacity: 1;
        }

        .navbar-burger {
            display: block !important;
        }
    }

    .navbar-burger {
        color: black;
        font-size: 20px;
        display: none;
    }

    .div-hoy {
        cursor: pointer;
        background: white;
        font-weight: bold;
        position: absolute;
        z-index: 200;
        width: 112px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding-left: 14px;
    }

    #alt-select-fecha {
        cursor: pointer;
        background: white;
        font-weight: bold;
        position: absolute;
        z-index: 199;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding-left: 14px;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background: white !important;
    }

    .select2-container--default {
        min-width: 132px;
    }

    .info-instalacion {
        max-height: 200px;
        overflow: hidden;
        transition: max-height .55s;
        font-size: 14px;
    }

    .toggle-ver-mas {
        display: block;
        padding: 5px 16px;
        font-size: 16px;
        color: #335fff;
        cursor: pointer;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        margin: 0 auto;
        background-color: #fff;
        border: 0;
        border-radius: 32px;
        border: 1px solid #335fff;
    }

    .info-instalacion:not(.no-max-height)::after {
        content: "";
        display: block;
        position: -webkit-sticky;
        position: sticky;
        bottom: 0;
        height: 40px;
        background: -webkit-gradient(linear, left bottom, left top, from(hsla(0, 0%, 100%, .8)), to(hsla(0, 0%, 100%, 0)));
        background: -webkit-linear-gradient(bottom, hsla(0, 0%, 100%, .8), hsla(0, 0%, 100%, 0));
        background: linear-gradient(0deg, hsla(0, 0%, 100%, .8) 0, hsla(0, 0%, 100%, 0));
    }

    .no-max-height {
        max-height: 2000px;
        margin-bottom: 25px;
        transition: max-height .55s;
    }

    .galeria-intalacion input[type=radio] {
        display: none;
    }

    .galeria-intalacion .card {
        position: absolute;
        width: 60%;
        height: 100%;
        left: 0;
        right: 0;
        margin: auto;
        transition: transform 0.4s ease;
        cursor: pointer;
    }

    .galeria-intalacion .container {
        width: 100%;
        max-width: 800px;
        max-height: 600px;
        height: 100%;
        transform-style: preserve-3d;
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
    }

    .galeria-intalacion .cards {
        position: relative;
        width: 100%;
        height: 100%;
        margin-bottom: 20px;
    }

    .galeria-intalacion img {
        width: 100%;
        height: 100%;
        border-radius: 10px;
        -o-object-fit: cover;
        object-fit: cover;
    }

    #item-1:checked~.cards #song-3,
    #item-2:checked~.cards #song-1,
    #item-3:checked~.cards #song-2 {
        transform: translatex(-40%) scale(0.8);
        opacity: 0.4;
        z-index: 0;
    }

    #item-1:checked~.cards #song-2,
    #item-2:checked~.cards #song-3,
    #item-3:checked~.cards #song-1 {
        transform: translatex(40%) scale(0.8);
        opacity: 0.4;
        z-index: 0;
    }

    #item-1:checked~.cards #song-1,
    #item-2:checked~.cards #song-2,
    #item-3:checked~.cards #song-3 {
        transform: translatex(0) scale(1);
        opacity: 1;
        z-index: 1;
    }

    #item-1:checked~.cards #song-1 img,
    #item-2:checked~.cards #song-2 img,
    #item-3:checked~.cards #song-3 img {
        box-shadow: 0px 0px 5px 0px rgba(81, 81, 81, 0.47);
    }

    /* .galeria-intalacion{
    height: 350px;
} */
    @media(max-width:1275px) {
        .app-desc {
            display: none !important;
        }

        .menu-app {
            display: block !important;
        }
    }

</style>

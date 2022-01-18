<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="PwZtoQA4Yrgn7creLcg3BTmITL3iYgzoELulzMSV">

    <title>Reserva de pista</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Styles -->
    <link href="https://entradas.aquasierra.es/css/app.css" rel="stylesheet">
    <style>
        .descripcion{
            font-weight: 300;
        }
        select.form-control{
            height: calc(1em + 0.75rem + 2px);
            padding: 0;
            padding-left: 6px;
            font-size: 13px;
            font-weight: bold;
        }
        label.col-form-label{
            font-weight: bold;
        }
        h1{
            font-size: 1.5rem;
            color: rgb(52, 70, 147);
            font-weight: bold;
        }
        .horario{
            color: rgb(52, 70, 147);
            font-weight: bold;
        }
        body{
            font-weight: 600;
        }
        .fecha-title{
            font-size: 15px;
            color: rgb(52, 70, 147);
        }
    </style>
</head>

<body>
    <div id="app">

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
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
                                <form method="POST" action="/{{ request()->slug_instalacion }}/{{ request()->deporte }}/{{ request()->id_pista }}/{{ request()->timestamp }}/reserva">
                                    @csrf
                                    <input type="hidden" name="secuencia" id="secuencia" value="{{ $secuencia }}">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label py-0">Deporte:</label>
                                        <div class="col-sm-10">
                                            <div>{{ $pista->tipo }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label py-0">Espacio:</label>
                                        <div class="col-sm-10">
                                            <div>{{ $pista->nombre }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label py-0">Fecha:</label>
                                        <div class="col-sm-10">
                                            <div>{{ date('d/m/Y', $fecha) }} (<span class="horario">{{ date('H:i', $fecha) }} a <span class="hfin">{{ date('H:i',strtotime (date('H:i', $fecha) . " +{$secuencia} minutes")) }}</span></span>)</div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label py-0">Tarifa:</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="tarifa" id="tarifa">
                                                <option data-hfin="{{ date('H:i',strtotime (date('H:i', $fecha) . " +" . $secuencia * 1 . " minutes")) }}" value="1">RESERVA {{ $secuencia }} MINUTOS</option>
                                                <option data-hfin="{{ date('H:i',strtotime (date('H:i', $fecha) . " +" . $secuencia * 2 . " minutes")) }}" value="2">RESERVA {{ $secuencia*2 }} MINUTOS</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-4">
                                        <label class="col-sm-2 col-form-label py-0"></label>
                                        <div class="col-sm-10 text-right d-flex justify-content-end" style="gap: 14px">
                                            <button type="submit" class="btn btn-info text-white">
                                                <i class="fas fa-check"></i>
                                                <div>Reservar</div>
                                            </button>
                                            <button class="cancel btn btn-danger">
                                                <i class="fas fa-times"></i>
                                                <div>Cancelar</div>
                                            </button>
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
        $(document).ready(function () {

            $('#tarifa').change(function (e) { 
                e.preventDefault();
                $('.hfin').html($(this).find(`[value="${$(this).val()}"]`).data('hfin'));
            });
        });
    </script>

</body>

</html>

@extends('layouts.admin')

@section('style')
    <style>
        .btn-dia {
            border: 1px solid #e5e5e5;
        }

        .btn-dia .numero {
            border: 2px solid #6dc3ee;
            padding: 10px;
            line-height: 30px;
            font-size: 14px;
            border-radius: 40px;
        }

        .col.text-center {
            padding: 0;
        }

        .dia {
            border: 1px solid #015e8c;
            font-size: 14px;
            background: #015e8c;
            color: white;
        }

        .mes {
            border: 1px solid #0073AA;
            font-size: 14px;
            background: #0073AA;
            color: white;
        }

        .btn-dia:hover {
            background: #d9f2ff;
        }

        .btn-dia:hover span.numero {
            background: #6dc3ee;
            color: white;
        }

        .btn-dia.active {
            background-color: #ddd;
            border-color: #ddd;
        }

        .btn-dia.active span {
            background-color: #fff;
            border-color: #fff;
            color: black;
        }

        .selector-pistas {
            display: flex;
            align-content: center;
            align-items: center;
            gap: 12px;
        }

        .selector-pistas i {
            font-size: 18px;
        }

        .next-prev-week a {
            padding: 12px 18px !important;
        }

        .reservas-dia {
            padding: 2%;
            background: #ddd;
            min-height: 180px;
        }

        .reservas-dia>div {
            padding: 30px 35px 10px;
            background: white;
        }

        .nav-tabs.nav-tabs-fillup {
            border-bottom: 1px solid #007be8;
        }
        .tab-content h4{
            font-weight: 300;
        }
        .tab-content h4 span, strong{
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 data-slug="{{ auth()->user()->instalacion->slug }}" class="text-primary no-margin inst-name">{{ auth()->user()->instalacion->nombre }}</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    {{-- <div class="card-header">
                        <div class="card-title">Gestión de reservas</div>
                    </div> --}}
                    <div class="card-body">
                        <div class="row d-flex justify-content-between mb-2">
                            <div class="selector-pistas">
                                <i class="far fa-calendar"></i>
                                <select class="form-control" name="select-pista" id="select-pista">
                                    <option value="Pista 1">Todos los espacios</option>
                                    <option value="Pista 1">Pista 1</option>
                                    <option value="Pista 2">Pista 2</option>
                                    <option value="Pista 3">Pista 3</option>
                                </select>
                            </div>
                            <div class="next-prev-week">
                                <div>
                                    <a href="#" class="btn"><</a>
                                    <a href="#" class="btn">></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center text-uppercase p-3 mes">
                                {{ \Carbon\Carbon::parse($period->start)->formatLocalized('%B') }}
                                {{ \Carbon\Carbon::parse($period->start)->formatLocalized('%Y') }}</div>
                        </div>
                        <div class="row dias">
                            @foreach ($period as $fecha)
                                <div class="col text-center">
                                    <div class="text-uppercase p-3 dia">
                                        {{ \Carbon\Carbon::parse($fecha)->formatLocalized('%A') }}</div>
                                    <div><a data-fecha="{{ $fecha->format('Y-m-d') }}" data-fecha_long="{{  $fecha->format('d/m/Y') }}" href="#" class="btn-dia w-100 h-100 d-block p-5"><span
                                                class="numero">{{ $fecha->format('d') }}</span></a></div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row reservas-dia">
                            <div class="col">
                                <ul class="nav nav-tabs nav-tabs-fillup d-none d-md-flex d-lg-flex d-xl-flex"
                                    data-init-reponsive-tabs="dropdownfx">
                                    @foreach ($pistas as $item)
                                        <li class="nav-item">
                                            <a href="#" class="active" data-toggle="tab"
                                                data-target="#{{ $item->id }}"><span>{{ $item->nombre }}</span></a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content reservas-dia">
                                    @foreach ($pistas as $pista)
                                        <h4><strong>{{ $pista->nombre }}</strong> Reservas para <span class="fecha"></span></h4>
                                        <div class="tab-pane active" id="{{ $item->id }}">
                                            @foreach ($item->reservas_por_dia('2022-01-27') as $reservas)
                                                <div>{{ $reservas->timestamp }}</div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="small no-margin">
                </p>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('.dias').on('click', '.btn-dia', function(e) {
                e.preventDefault();
                $('.btn-dia').removeClass('active');
                $(this).addClass('active');
                $('span.fecha').html($(this).data('fecha_long'));
                
                $.ajax({
                    url: `/${$('.inst-name').data('slug')}/admin/reservas/${$(this).data('fecha')}`,
                    success: data => {
                        $('.reservas-dia.tab-content').html(data);
                    },
                    error: data => {
                        console.log(data)
                    }
                });
            });
        });
    </script>
@endsection

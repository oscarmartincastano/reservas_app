@extends('layouts.userview')

@section('pagename', 'Mis reservas')

@section('style')
    <style>
        .reserva{
            margin-top: 25px;
        }
        .reserva .card{
            color: white;
            border-radius: 5px;
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
            font-size: 1.1rem;
            font-weight: bold;
        }
        .horario{
            font-weight: bold;
        }
        .fecha-title{
            font-size: 15px;
        }
        .boton-cancelar{
            position: absolute;
            right: 10px;
            bottom: 5px;
        }
        .h2{
            font-size: 1.7rem;
            font-weight: bold;
            margin-bottom: 0;
        }
        .table-reservas thead{
            font-weight: bold;
        }
        #DataTables_Table_0_length > label > select{
            padding-right: 24px;
        }
        .pagination{
            margin-top: 15px !important;
        }
        nav.navbar{
            margin-bottom: 25px;
        }
        @media (min-width: 1215px) {
            table.table-reservas{
                display: table;
                border: 0;
            }
        }
        @media (max-width: 900px) {
            table.table-reservas{
                font-size: 14px;
            }
            a.cancel {
                font-size: 14px;
                padding: 1px 8px;
            }
        }
    </style>
@endsection

@section('content')

<div class="container is-max-desktop mt-2">
    
    <div class="container mt-3">
        <h1 class="title titulo-pagina">Mis Reservas</h1>
        <div class="card" style="box-shadow: none">
            <div class="card-body">
                <div class="list-reservas row">
                    <table class="table table-reservas  w-100" style="overflow-x: auto !important;-webkit-overflow-scrolling: touch !important;">
                        <thead>
                            <tr>
                                <td>Fecha de alquiler</td>
                                {{-- <td>Día de la semana</td> --}}
                                <td>Horas</td>
                                {{-- <td>Hora final</td> --}}
                                <td>Espacio</td>
                                <td>Estado</td>
                                <td>#</td>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reservas as $item)
                            <tr>
                                <td>{{ date('d/m/Y', $item->timestamp) }}</td>
                                {{-- <td style="text-transform:capitalize">{{ \Carbon\Carbon::parse($item->fecha)->translatedFormat('l') }}</td> --}}
                                <td>{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->format('H:i')  }} - {{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}</td>
                                {{-- <td>{{ \Carbon\Carbon::createFromTimestamp($item->timestamp)->addMinutes($item->minutos_totales)->format('H:i') }}</td> --}}
                                <td>{{ $item->pista->tipo }}. {{ $item->pista->nombre }}</td>
                                <td>
                                    @if ($item->estado  == 'active')
                                        @if (strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i')))
                                            Confirmada
                                        @else
                                            Pasado
                                        @endif
                                    @endif
                                    @if ($item->estado  == 'espera')
                                        <span style="color: #ff9800">En lista de espera</span>
                                    @endif
                                    @if($item->estado == 'desierta')
                                        <span class="text-warning">Desierta</span>
                                    @endif
                                    @if($item->estado == 'canceled')
                                        <span class="text-danger">Cancelada</span>
                                    @endif
                                    @if($item->estado == 'pasado')
                                        <span class="text-success">Validada</span>
                                    @endif
                                </td>
                                <td>
                                        @if (($item->estado  == 'active' || $item->estado == 'espera') && strtotime(date('Y-m-d H:i', $item->timestamp) . ' +' . $item->minutos_totales . ' minutes') > strtotime(date('Y-m-d H:i')))
                                            <form action="/{{ request()->slug_instalacion }}/mis-reservas/{{ $item->id }}/cancel" method="post">
                                                @csrf
                                            </form>
                                            @if(strtotime(date('Y-m-d H:i') . " + {$item->pista->antelacion_cancelacion} hours") < $item->timestamp)
                                                <a class="cancel btn btn-danger" title="Cancelar reserva" onclick="if(confirm('¿Estás seguro que quieres cancelar esta reserva?')){$(this).prev().submit()}">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            @endif
                                        @endif
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No se encuentran registros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{-- <h2 class="h2 text-success">Reservas activas</h2> --}}
                    {{-- @if (count(auth()->user()->reservas_activas) == 0)
                        <p class="mt-3 mb-4">No hay reservas activas actualmente.</p>
                    @endif
                    @foreach (auth()->user()->reservas_activas as $item)
                        <div class="col-md-6 reserva">
                            <div class="card" style="background:url(/img/deportes/fondo-{{ strtolower($item->pista->tipo) }}.jpg);background-size: cover; ">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h1><i class="far fa-calendar-check mr-2"></i> {{ date('d-m-Y', $item->timestamp) }}</h1>
                                        <h1><i class="far fa-clock mr-2"></i> {{ date('H:i', $item->timestamp) }} a {{ date('H:i',strtotime (date('H:i', $item->timestamp) . " +{$item->minutos_totales} minutes")) }}</h1> 
                                    </div>
                                    <div class="form-group row mt-5 mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Deporte:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->pista->tipo }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Espacio:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->pista->nombre }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Fecha:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('d/m/Y', $item->timestamp) }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Hora:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('H:i', $item->timestamp) }} - {{ date('H:i',strtotime (date('H:i', $item->timestamp) . " +{$item->minutos_totales} minutes")) }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Minutos totales:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->minutos_totales }} minutos</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Reserva realizada:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('d/m/Y, H:i', strtotime($item->created_at)) }}</div>
                                        </div>
                                    </div>
                                    @if (auth()->user()->instalacion->configuracion->allow_cancel)
                                        <div class="form-group row mt-4 mb-2 boton-cancelar">
                                            <div class="col-sm-12 text-right d-flex justify-content-end" style="gap: 14px">
                                                <form action="/mis-reservas/{{ $item->id }}/cancel" method="post">
                                                    @csrf
                                                    <button class="cancel btn btn-danger">
                                                        <i class="fas fa-times"></i> Cancelar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if (count(auth()->user()->reservas_pasadas) > 0)
                        <h2 class="h2 mt-5">Reservas pasadas</h2>
                    @endif
                    @foreach (auth()->user()->reservas_pasadas as $item)
                        <div class="col-md-6 reserva">
                            <div class="card" style="background:url(/img/deportes/fondo-{{ strtolower($item->pista->tipo) }}.jpg);background-size: cover; ">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h1><i class="far fa-calendar-check mr-2"></i> {{ date('d-m-Y', $item->timestamp) }}</h1>
                                        <h1><i class="far fa-clock mr-2"></i> {{ date('H:i', $item->timestamp) }} a {{ date('H:i',strtotime (date('H:i', $item->timestamp) . " +{$item->minutos_totales} minutes")) }}</h1> 
                                    </div>
                                    <div class="form-group row mt-5 mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Deporte:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->pista->tipo }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Espacio:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->pista->nombre }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Fecha:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('d/m/Y', $item->timestamp) }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Hora:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('H:i', $item->timestamp) }} - {{ date('H:i',strtotime (date('H:i', $item->timestamp) . " +{$item->minutos_totales} minutes")) }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Minutos totales:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->minutos_totales }} minutos</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Reserva realizada:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('d/m/Y, H:i', strtotime($item->created_at)) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if (count(auth()->user()->reservas_canceladas) > 0)
                        <h2 class="h2 mt-5 text-danger">Reservas canceladas</h2>
                    @endif
                    @foreach (auth()->user()->reservas_canceladas as $item)
                        <div class="col-md-6 reserva">
                            <div class="card" style="background:url(/img/deportes/fondo-{{ strtolower($item->pista->tipo) }}.jpg);background-size: cover; ">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h1><i class="far fa-calendar-check mr-2"></i> {{ date('d-m-Y', $item->timestamp) }}</h1>
                                        <h1><i class="far fa-clock mr-2"></i> {{ date('H:i', $item->timestamp) }} a {{ date('H:i',strtotime (date('H:i', $item->timestamp) . " +{$item->minutos_totales} minutes")) }}</h1> 
                                    </div>
                                    <div class="form-group row mt-5 mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Deporte:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->pista->tipo }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Espacio:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->pista->nombre }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Fecha:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('d/m/Y', $item->timestamp) }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Hora:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('H:i', $item->timestamp) }} - {{ date('H:i',strtotime (date('H:i', $item->timestamp) . " +{$item->minutos_totales} minutes")) }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Minutos totales:</label>
                                        <div class="col-sm-9">
                                            <div>{{ $item->minutos_totales }} minutos</div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-2">
                                        <label class="col-sm-3 col-form-label py-0">Reserva realizada:</label>
                                        <div class="col-sm-9">
                                            <div>{{ date('d/m/Y, H:i', strtotime($item->created_at)) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach --}}
                </div>
            </div>
        </div>
        
        <div class="text-right p-3">
            {{ $reservas->links() }}
        </div>
    </div>
</div>

@endsection

@section('script')
    <script>
        $(document).ready(function () {
            
            /* $('.table-reservas').dataTable({
                "info": false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [[ 6, "desc"], [ 0, "asc"], [ 3, "desc"]]
            }); */
        });
    </script>
@endsection
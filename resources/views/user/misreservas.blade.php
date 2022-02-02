@extends('layouts.userview')

@section('pagename', 'Inicio')

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
    </style>
@endsection

@section('content')

<div class="container is-max-desktop">
    
    <div class="container mt-3">
        <h1 class="title text-center">Mis Reservas</h1>
        <div class="list-reservas row">
            <h2 class="h2 text-success">Reservas activas</h2>
            @if (count(auth()->user()->reservas_activas) == 0)
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
                            {{-- <div class="row">
                                <div class="col">
                                </div>
                                <div class="col">
                                </div>
                            </div> --}}
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
                                        <form action="/{{ request()->slug_instalacion }}/mis-reservas/{{ $item->id }}/cancel" method="post">
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
                            {{-- <div class="row">
                                <div class="col">
                                </div>
                                <div class="col">
                                </div>
                            </div> --}}
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
                            {{-- <div class="row">
                                <div class="col">
                                </div>
                                <div class="col">
                                </div>
                            </div> --}}
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
        </div>
    </div>
</div>

@endsection
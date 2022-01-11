@extends('layouts.userview')

@section('pagename', 'Tenis')

@section('content')

<section class="hero is-medium">
    <div class="hero-body has-text-centered" style="padding-top: 20px;padding-bottom: 3rem">
        <h1 class="title is-2">Tenis</h1>
        
    </div>
</section>

<div class="container is-max-desktop">
    <div class="columns">
        <div class="column is-full">
            <div class="div-reservas">
                <div class="pistas">
                    @foreach ($pistas as $index => $pista)
                        <div class="{{ $index == 0 ? 'active' : '' }}"><a class="select-pista" data-id_pista="{{ $pista->id }}" href="#">{{ $pista->nombre }}</a></div>
                    @endforeach
                </div>
                <div class="calendario-horarios">
                    <div class="navigator"> 
                        <div><a class="button" href="#"><</a> <a class="button" href="#">Hoy</a> <a class="button" href="#">></a> </div>
                        <div style="text-transform: capitalize">{{ \Carbon\Carbon::parse(iterator_to_array($period)[0])->formatLocalized('%B %Y')  }}</div> 
                    </div>
                </div>
                <div class="tabla-reservas">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Hora</th>
                                @foreach ($period as $fecha)
                                    <th style="text-transform: capitalize">{{ \Carbon\Carbon::parse($fecha)->formatLocalized('%A') }}<br>{{ $fecha->format('d/m') }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>8:00 - 9:00</th>
                                <td rowspan="2" class="reserved"><a href="#" class="btn-reservar">RESERVADO</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                            </tr>
                            <tr>
                                <th>9:00 - 10:00</th>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                            </tr>
                            <tr>
                                <th>10:00 - 11:00</th>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                            </tr>
                            <tr>
                                <th>11:00 - 12:00</th>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                            </tr>
                            <tr>
                                <th>12:00 - 13:00</th>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                            </tr>
                            <tr>
                                <th>13:00 - 14:00</th>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                            </tr>
                            <tr>
                                <th>14:00 - 15:00</th>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                            </tr>
                            <tr>
                                <th>15:00 - 16:00</th>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                            </tr>
                            <tr>
                                <th>16:00 - 17:00</th>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                                <td><a href="#" class="btn-reservar">RESERVAR</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
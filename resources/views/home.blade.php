@extends('layouts.userview')

@section('pagename', 'Inicio')

@section('content')


<div class="container">
    <h1 class="title text-center mt-5 titulo-pagina">Selecciona espacio</h1>
    <div class="divider mb-5">
        <div></div>
    </div>
    <div class="row" style="place-content: center">
        @php 
        @endphp
        @foreach ($instalacion->deportes as $index => $item)
            <div class="col-md-4" style="padding: calc(var(--bs-gutter-x) * .5)">
                @if (!file_exists(public_path() . '/img/deportes/'.lcfirst($item).'.jpg'))
                    <a style="
                    display: flex;
                    align-content: center;
                    align-items: center;
                    justify-content: center;
                    background: linear-gradient(0deg, rgba(36, 36, 36, 0.5), rgba(36, 36, 36, 0.5));
                    font-family: 'Fira Sans', sans-serif;
                    text-transform: uppercase;
                    font-weight: bold;
                    color: white;
                    font-size:2em" href="/{{ request()->slug_instalacion }}/{{ $item }}">
                        <img style="visibility: hidden" src="{{ asset('img/deportes/piscina.jpg') }}">
                        <span style="position: absolute">{{ $item }}</span>
                    </a>
                @else
                    <a href="/{{ request()->slug_instalacion }}/{{ $item }}"><img src="{{ asset('img/deportes/'.lcfirst($item).'.jpg') }}"></a>
                @endif
            </div>
        @endforeach
    </div>
</div>

@endsection
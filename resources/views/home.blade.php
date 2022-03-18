@extends('layouts.userview')

@section('pagename', 'Inicio')

@section('content')


<div class="container">
    <h1 class="title text-center mt-5">Selecciona espacio</h1>
    <div class="divider mb-5">
        <div></div>
    </div>
    <div class="row">
        @foreach ($instalacion->deportes as $item)
            <div class="col">
                @if ($instalacion->id == 4)
                    <a style="    min-height: 200px;
                    display: flex;
                    align-content: center;
                    align-items: center;
                    justify-content: center;
                    background: linear-gradient(0deg, rgba(36, 36, 36, 0.5), rgba(36, 36, 36, 0.5));
                    font-family: 'Fira Sans', sans-serif;
                    text-transform: uppercase;
                    font-weight: bold;
                    color: white;
                    font-size:2em" href="/{{ request()->slug_instalacion }}/{{ $item }}">{{ $item }}</a>
                @else
                    <a href="/{{ request()->slug_instalacion }}/{{ $item }}"><img src="{{ asset('img/deportes/'.strtolower($item).'.jpg') }}"></a> --}}
                @endif
            </div>
        @endforeach
    </div>
</div>

@endsection
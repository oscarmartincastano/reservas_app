@extends('layouts.userview')

@section('pagename', 'Inicio')

@section('content')

<section class="hero is-medium">
    <div class="hero-body has-text-centered" style="padding-top: 90px;padding-bottom: 3rem">
        <h1 class="title is-2">Selecciona deporte</h1>
        <div class="divider">
            <div></div>
        </div>
    </div>
</section>
{{-- {{ dd($pistas[0]->horario_deserialized) }} --}}
<div class="container is-max-desktop">
    <div class="columns is-8 select-deporte">
        @foreach ($instalacion->deportes as $item)
            <div class="column has-text-centered"><a href="/{{ $item }}"><img src="{{ asset('img/deportes/'.$item.'.jpg') }}"></a></div>
        @endforeach
    </div>
</div>

@endsection
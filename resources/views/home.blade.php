@extends('layouts.userview')

@section('pagename', 'Inicio')

@section('content')


<div class="container">
    <h1 class="title text-center">Selecciona espacio</h1>
    <div class="divider mb-5">
        <div></div>
    </div>
    <div class="row">
        @foreach ($instalacion->deportes as $item)
            <div class="col"><a href="/{{ request()->slug_instalacion }}/{{ $item }}"><img src="{{ asset('img/deportes/'.$item.'.jpg') }}"></a></div>
        @endforeach
    </div>
</div>

@endsection
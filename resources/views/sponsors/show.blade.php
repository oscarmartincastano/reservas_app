@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Ver Patrocinador {{ $sponsor->name }}</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="row">
                    <div class="col">
                        <div class="card card-default">
                            <div class="card-header  separator">
                                <div class="card-title">Información</div>
                            </div>
                            <div class="card-header border-bottom text-center">
                                <div class="mb-3 mx-auto">
                                    <img class="rounded-circle" src="{{ asset($sponsor->logo) }}" alt="Sponsor Avatar"
                                        width="110">
                                </div>
                                <h4 class="mb-0">{{ $sponsor->name }}</a></h4>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-4"><strong>Nombre:</strong> {{ $sponsor->name }}</li>

                                    <li class="list-group-item px-4"><strong>Página Web:</strong>
                                        <a href="{{ $sponsor->website }}" target="_blank">{{ $sponsor->website }}</a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

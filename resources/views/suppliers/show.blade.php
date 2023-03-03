@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Ver Proveedor {{ $supplier->name }}</h3>
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
                                <h4 class="mb-0">{{ $supplier->name }}</a></h4>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">

                                    <li class="list-group-item px-4"><strong>Teléfono:</strong>
                                        <a href="tel:{{ $supplier->phone }}">{{ $supplier->phone }}
                                        </a>
                                    </li>

                                    <li class="list-group-item px-4"><strong>Correo:</strong>
                                        <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}
                                        </a>
                                    </li>

                                    <li class="list-group-item px-4"><strong>CIF:</strong>
                                        {{ $supplier->cif }}
                                    </li>

                                    <li class="list-group-item px-4"><strong>Dirección:</strong>
                                        {{ $supplier->address }}, {{ $supplier->cp }} {{ $supplier->city }}
                                        ({{ $supplier->province }})
                                    </li>

                                </ul>
                                <p class="list-group-item px-4">
                                    {{ $supplier->notes }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('style')
    <style>
        .clickable {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">{{ auth()->user()->instalacion->nombre }}</h3>
                </div>
            </div>

            <div class="p-t-15 p-b-15 p-l-20 p-r-20">
                <div class="card ">
                    <div class="card-header">
                        <div class="card-title">Listado de facturas</div>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('invoices.create', ['slug_instalacion' => request()->slug_instalacion]) }}"
                            class="text-white btn btn-primary">Añadir nuevo</a>
                        <table class="table table-hover" id="table-invoices">
                            <thead>
                                <tr>
                                    <th>Fecha</th>

                                    <th>Proveedor</th>

                                    <th>fecha-pago/pendiente</th>

                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($invoices)
                                    @foreach ($invoices as $invoice)
                                        <tr class="clickable"
                                            data-href="{{ route('invoices.show', ['slug_instalacion' => request()->slug_instalacion, 'id' => $invoice->id]) }}">

                                            <td class="text-center" data-order="{{ $invoice->date }}">
                                                {{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}</td>

                                            <td>{{ $invoice->supplier->name }}</td>

                                            @php
                                                $falta = round($invoice->total, 2) - round($invoice->paid, 2);
                                            @endphp

                                            @if ($falta > 0)
                                                <td class="text-center" data-order="{{ $falta }}">
                                                    <div class="badge badge-danger p-2 text-white">
                                                        {{ $falta }} €
                                                    </div>
                                                </td>
                                            @else
                                                <td class="text-center" data-order="0">
                                                    <div class="badge badge-success p-2 text-white">
                                                        {{ \Carbon\Carbon::parse($invoice->paid_at)->format('d/m/Y') }}
                                                    </div>
                                                </td>
                                            @endif

                                            <td>
                                                <div class="d-flex" style="gap:5px">

                                                    <a href="{{ route('invoices.show', ['slug_instalacion' => request()->slug_instalacion, 'id' => $invoice->id]) }}"
                                                        class="btn btn-success"><i data-feather="eye"></i></a>

                                                    <a href="{{ route('invoices.edit', ['slug_instalacion' => request()->slug_instalacion, 'id' => $invoice->id]) }}"
                                                        class="btn btn-primary"><i data-feather="edit"></i></a>

                                                    <form
                                                        action=" {{ route('invoices.destroy', ['slug_instalacion' => request()->slug_instalacion, 'id' => $invoice->id]) }} "
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"><i
                                                                data-feather="trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
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
            $('#table-invoices').DataTable({
                "info": false,
                "paging": false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
                },
                "order": [
                    [0, "desc"]
                ],

                "columnDefs": [{
                    "targets": [3],
                    "orderable": false
                }],

            });

            $('.clickable').click(function(e) {
                if (e.target.tagName == 'TD') {
                    window.location = $(this).data("href");
                }
            });

            columnDefs: [{
                targets: [3],
                orderable: false
            }]

        });
    </script>
@endsection

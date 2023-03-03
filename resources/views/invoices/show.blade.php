@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12 m-b-10">

            <div class="p-l-20 p-r-20 p-b-10 pt-3">
                <div>
                    <h3 class="text-primary no-margin">Ver Factura
                        @isset($invoice->number)
                            {{ $invoice->number }}
                        @endisset
                        ({{ Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }})
                    </h3>
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
                                <h4 class="mb-0">Factura
                                    @isset($invoice->number)
                                        {{ $invoice->number }}
                                    @endisset
                                    ({{ Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }})</a></h4>
                            </div>

                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-4"><strong>Proveedor:</strong>
                                        <a
                                            href="{{ route('suppliers.show', [
                                                'slug_instalacion' => request()->slug_instalacion,
                                                'id' => $invoice->supplier->id,
                                            ]) }}">
                                            {{ $invoice->supplier->name }}
                                        </a>
                                    </li>

                                    <li class="list-group-item px-4"><strong>Entidad Bancaria:</strong>
                                        {{ $invoice->bank->name }}
                                    </li>

                                    <li class="list-group-item px-4"><strong>Fecha de emisión:</strong>
                                        {{ Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}
                                    </li>

                                    @if ($invoice->number)
                                        <li class="list-group-item px-4"><strong>Número de factura:</strong>
                                            {{ $invoice->number }}
                                        </li>
                                    @endif

                                    @if (round($invoice->total, 2) - round($invoice->paid, 2) > 0)
                                        <li class="list-group-item px-4"><strong>Falta por pagar:</strong>
                                            <div class="badge badge-danger">
                                                {{ round($invoice->total, 2) - round($invoice->paid, 2) }} €
                                            </div>
                                        </li>
                                    @else
                                        <li class="list-group-item px-4"><strong>Fecha de pago</strong>
                                            {{ Carbon\Carbon::parse($invoice->payment_date)->format('d/m/Y') }}
                                        </li>
                                    @endif

                                    @isset($invoice->file)
                                        <li class="list-group-item px-4">
                                            <a href="{{ asset($invoice->filePath) }}" class="btn btn-primary btn-cons"
                                                target="_blank">
                                                Ver pdf de la factura
                                            </a>
                                        </li>
                                    @endisset
                                </ul>
                                
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Concepto</th>
                                                <th scope="col">Tipo</th>
                                                <th scope="col">base</th>
                                                <th scope="col">Iva</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoice->invoiceLines as $invoiceLine)
                                                <tr>
                                                    <td>{{ $invoiceLine->concept }}</td>
                                                    <td>{{ $invoiceLine->service_type->name }}</td>
                                                    <td>{{ $invoiceLine->base }}</td>
                                                    <td>{{ $invoiceLine->iva }}</td>
                                                    <td>{{ $invoiceLine->total }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" class="text-right"><strong>Totales:</strong></td>
                                                <td><strong>{{ $invoice->subtotal }} €</strong></td>
                                                <td><strong>{{ $invoice->iva }} €</strong></td>
                                                <td><strong>{{ $invoice->total }} €</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

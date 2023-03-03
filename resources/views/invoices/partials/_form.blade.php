<form enctype="multipart/form-data"
    @isset($invoice)
    action="{{ route('invoices.update', ['slug_instalacion' => request()->slug_instalacion, 'id' => $invoice->id]) }}"
    @else
    action="{{ route('invoices.store', ['slug_instalacion' => request()->slug_instalacion]) }}"
@endisset
    method="post" role="form" class="form-horizontal">
    @csrf

    <div class="form-group row">
        <label class="col-md-2 control-label">Proveedor</label>
        <select name="supplier_id" id="" class="select2 form-control col-md-10" data-init-plugin="select2">
            @foreach (App\Models\Instalacion::where('slug', request()->slug_instalacion)->first()->suppliers as $supplier)
                <option value="{{ $supplier->id }}"
                    @isset($invoice) @if ($invoice->supplier_id == $supplier->id) selected @endif @endisset>
                    {{ $supplier->name }}
                </option>
            @endforeach
        </select>
        @error('supplier_id')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label">Entidad Bancaria</label>
        <select name="bank_id" id="" class="select2 form-control col-md-10" data-init-plugin="select2">
            @foreach (App\Models\Instalacion::where('slug', request()->slug_instalacion)->first()->banks as $bank)
                <option value="{{ $bank->id }}"
                    @isset($invoice) @if ($invoice->bank_id == $bank->id) selected @endif @endisset>
                    {{ $bank->name }}
                </option>
            @endforeach
        </select>
        @error('bank_id')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label">Número de factura</label>
        <input name="number" type="text" placeholder="Número de factura del proveedor..."
            class="form-control col-md-10" value="{{ old('number', isset($invoice) ? $invoice->number : '') }}">
        @error('number')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label">Fecha</label>
        <input name="date" type="date" placeholder="name..." class="form-control col-md-10"
            value="{{ old(
                'date',
                isset($invoice) ? Carbon\Carbon::parse($invoice->date)->format('Y-m-d') : Carbon\Carbon::now()->format('Y-m-d'),
            ) }}"
            required>
        @error('date')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label">Pagado</label>
        <input name="paid" type="number" placeholder="Cantidad pagada..." class="form-control col-md-10"
            step="0.01" min="0"
            value={{ old('paid', isset($invoice) ? str_replace(',', '.', $invoice->paid) : 0) }} required>
        @error('paid')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label">Fecha de pago</label>
        <input name="paid_at" type="date" placeholder="name..." class="form-control col-md-10"
            value="{{ old(
                'paid_at',
                isset($invoice) ? Carbon\Carbon::parse($invoice->paid_at)->format('Y-m-d') : Carbon\Carbon::now()->format('Y-m-d'),
            ) }}"
            required>
        @error('paid_at')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group row">
        <label class="col-md-2 control-label">Archivo de factura</label>

        <input type="file" name="file" class="form-control col-md-10" accept="application/pdf" />
        @error('file')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    {{-- <div class="form-group row">
        <label class="col-md-2 control-label">Notas</label>
        <textarea name="notes" id="" cols="30" rows="10" class="form-control col-md-10"
            placeholder="Notas...">{{ old('notes', isset($invoice) ? $invoice->notes : '') }}</textarea>
        @error('notes')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div> --}}

    <label class="col-md-2 control-label">líneas de factura</label>

    <div class="form-group row">
        <div class="col">
            <div class="table-responsive">
                <table class="table" id="table-invoice-lines">
                    <thead>
                        <tr>
                            {{-- <th>id</th> --}}
                            <th>Concepto</th>
                            <th>Tipo</th>
                            <th style="max-width:100px">Base</th>
                            <th style="max-width:100px">IVA</th>
                            <th style="max-width:100px">Total</th>
                            <th>#</th>
                        </tr>
                    </thead>

                    <tbody id="t-body">
                        @isset($invoice)
                            @foreach ($invoice->invoiceLines as $invoiceLine)
                                <tr>
                                    {{-- <td>
                                                    <input @if ($invoiceLine) value="{{ $loop->index }}" @endif type="text"
                                                        name="invoiceLines[{{ $loop->index }}][id]" required placeholder="Id..." class="form-control"
                                                        data-name="id">
                                                </td> --}}

                                    <td>
                                        <input @if ($invoiceLine) value="{{ $invoiceLine->concept }}" @endif
                                            type="text" name="invoiceLines[{{ $loop->index }}][concept]" required
                                            placeholder="Concepto..." class="form-control" data-name="concept">
                                    </td>

                                    <td>
                                        <select name="invoiceLines[{{ $loop->index }}][service_type_id]"
                                            data-init-plugin="select2" class="select2 full-width" id="select-tipo"
                                            data-name="tipo">
                                            @foreach (\App\Models\Instalacion::where('slug', request()->slug_instalacion)->first()->serviceTypes as $tipo)
                                                <option @if (isset($invoiceLine) && $tipo->id == $invoiceLine->id_tipo) selected @endif
                                                    value="{{ $tipo->id }}" data-iva="{{ $tipo->iva }}"">
                                                    {{ $tipo->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td style="max-width:100px">
                                        <input type="text" name="invoiceLines[{{ $loop->index }}][base]"
                                            id="input-base" required placeholder="Base..." class="form-control text-right"
                                            @if ($invoiceLine) value="{{ $invoiceLine->base }}" @endif
                                            data-name="base">
                                    </td>

                                    <td style="max-width:100px" class="text-right align-middle">
                                        <input type="text" name="invoiceLines[{{ $loop->index }}][iva]" id="input-iva"
                                            required placeholder="IVA..." class="form-control" data-name="iva"
                                            @if ($invoiceLine) value="{{ $invoiceLine->iva }}" @endif
                                            hidden>

                                        <div id="iva-line-input-mirror">
                                            @if ($invoiceLine)
                                                {{ $invoiceLine->iva }} €
                                            @endif
                                        </div>

                                    </td>

                                    <td style="max-width:100px">
                                        <input type="text" id="total"
                                            name="invoiceLines[{{ $loop->index }}][total]" required
                                            placeholder="Total..." class="form-control text-right"
                                            @if ($invoiceLine) value="{{ $invoiceLine->total }}" @endif
                                            data-name="total">
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-danger delete_invoiceLine"> <i
                                                class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @php
                                    $index = $loop->index + 1;
                                @endphp
                            @endforeach
                        @endisset
                        @php
                            $index = $index ?? 0;
                        @endphp
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <button type="button" class="btn btn-primary form" id="add-invoiceLine">Añadir
                                    línea</button>
                            </td>

                            <th class="text-right">Totales:
                            </th>
                            <td style="max-width:100px" class="text-right">
                                <input type="text" name="base" id="input-base-total" required
                                    @if (isset($invoice)) value="{{ $invoice->base }}" @endif
                                    placeholder="Base..." class="form-control" hidden>

                                <div id="input-base-mirror">
                                    @if (isset($invoice))
                                        {{ $invoice->base }}
                                    @endif
                                </div>

                            </td>

                            <td style="max-width:100px" class="text-right">
                                <input type="text" name="iva" id="input-iva-total" required
                                    @if (isset($invoice)) value="{{ $invoice->iva }}" @endif
                                    placeholder="IVA..." class="form-control" hidden>

                                <div id="input-iva-mirror">
                                    @if (isset($invoice))
                                        {{ $invoice->iva }}
                                    @endif
                                </div>
                            </td>

                            <td style="max-width:100px" class="text-right">
                                <input type="text" id="input-total-total" name="total" required
                                    @if (isset($invoice)) value="{{ $invoice->total }}" @endif
                                    placeholder="Total..." class="form-control" hidden>

                                <div id="input-total-mirror">
                                    @if (isset($invoice))
                                        {{ $invoice->total }}
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @isset($invoice)
        @method('PUT')
        <button class="btn btn-warning btn-lg m-b-10 mt-3" type="submit">Actualizar</button>
    @else
        @method('POST')
        <button class="btn btn-primary btn-lg m-b-10 mt-3" type="submit">Añadir</button>
    @endisset
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const recalcularFactura = function() {
            [...document.querySelectorAll('input[data-name="base"]')].forEach(input => {
                input.value = parseFloat(input.value.replace(',', '.')).toFixed(2);
            });

            [...document.querySelectorAll('input[data-name="iva"]')].forEach(input => {
                input.value = parseFloat(input.value.replace(',', '.')).toFixed(2);
            });

            [...document.querySelectorAll('input[data-name="total"]')].forEach(input => {
                input.value = parseFloat(input.value.replace(',', '.')).toFixed(2);
            });

            const baseTotal = [...document.querySelectorAll('input[data-name="base"]')].reduce((total,
                input) => total + parseFloat(input.value), 0);

            const ivaTotal = [...document.querySelectorAll('input[data-name="iva"]')].reduce((total,
                input) => total + parseFloat(input.value), 0);

            const totalTotal = [...document.querySelectorAll('input[data-name="total"]')].reduce((total,
                input) => total + parseFloat(input.value), 0);

            const mirrorBase = document.getElementById('input-base-mirror');
            const mirrorIva = document.getElementById('input-iva-mirror');
            const mirrorTotal = document.getElementById('input-total-mirror');
            // REFACTOR: use an event to update the mirrors
            // REFACTOR: delete the inputs and calculate iva and total in the backend
            mirrorBase.innerText = baseTotal.toFixed(2) + '€';
            mirrorIva.innerText = ivaTotal.toFixed(2) + '€';
            mirrorTotal.innerText = totalTotal.toFixed(2) + '€';

            document.getElementById('input-base-total').value = baseTotal.toFixed(2);
            document.getElementById('input-iva-total').value = ivaTotal.toFixed(2);
            document.getElementById('input-total-total').value = totalTotal.toFixed(2);
        }


        const deleteRow = function() {
            this.parentNode.parentNode.remove();

            // asegurar que todas las filas tienen el name correcto, comienza en 0 y va incrementando

            const filas = document.querySelectorAll('#t-body tr');

            filas.forEach((fila, index) => {

                // fila.querySelector('input[data-name="id"]').name = `invoiceLines[${index}][id]`;
                // fila.querySelector('input[data-name="id"]').value = index;

                fila.querySelector('input[data-name="concept"]').name =
                    `invoiceLines[${index}][concept]`;
                fila.querySelector('select[data-name="tipo"]').name =
                    `invoiceLines[${index}][service_type_id]`;
                fila.querySelector('input[data-name="base"]').name = `invoiceLines[${index}][base]`;
                fila.querySelector('input[data-name="iva"]').name = `invoiceLines[${index}][iva]`;
                fila.querySelector('input[data-name="total"]').name =
                    `invoiceLines[${index}][total]`;
            });

            recalcularFactura();

        }


        const addDeleteRowOnClick = function(element) {
            element.addEventListener('click', deleteRow);
        };

        const tipos = <?php echo json_encode(
            App\Models\Instalacion::where('slug', request()->slug_instalacion)
                ->first()
                ->serviceTypes()
                ->get()
                ->toArray(),
        ); ?>;

        const options = tipos.map(tipo => {
            return `<option value="${tipo.id}" data-iva="${tipo.iva}"   >${tipo.name}</option>`;
        }).join('');

        const addRecalcularporBase = function(element) {
            element.addEventListener('change', function() {

                const baseInput = this.parentNode.parentNode.querySelector(
                    'input[data-name="base"]');

                const ivaInput = this.parentNode.parentNode.querySelector(
                    'input[data-name="iva"]');

                const totalInput = this.parentNode.parentNode.querySelector(
                    'input[data-name="total"]');

                const tipoInput = this.parentNode.parentNode.querySelector(
                    'select[data-name="tipo"]');

                const tipo = tipoInput.options[tipoInput.selectedIndex].dataset.iva / 100;

                baseInput.value = baseInput.value.replace(',', '.');

                if (isNaN(baseInput.value)) {
                    baseInput.value = 0.0;
                }

                baseInput.value = parseFloat(baseInput.value).toFixed(2);

                ivaInput.value = (baseInput.value * tipo).toFixed(2);

                const lineMirror = this.parentNode.parentNode.querySelector(
                    '#iva-line-input-mirror');

                lineMirror.innerText = ivaInput.value + '€';

                totalInput.value = (parseFloat(ivaInput.value) + parseFloat(baseInput.value))
                    .toFixed(2);

                recalcularFactura();
            });
        };

        const addRecalcularporTipo = function(element) {
            $(element).change(function() {

                const ivaInput = this.parentNode.parentNode.querySelector(
                    'input[data-name="iva"]');

                const totalInput = this.parentNode.parentNode.querySelector(
                    'input[data-name="total"]');

                const baseInput = this.parentNode.parentNode.querySelector(
                    'input[data-name="base"]');

                const tipoInput = this.parentNode.parentNode.querySelector(
                    'select[data-name="tipo"]');

                const tipo = tipoInput.options[tipoInput.selectedIndex].dataset.iva / 100;


                ivaInput.value = (baseInput.value * tipo).toFixed(2);

                const lineMirror = this.parentNode.parentNode.querySelector(
                    '#iva-line-input-mirror');
                lineMirror.innerText = ivaInput.value + '€';

                totalInput.value = (parseFloat(ivaInput.value) + parseFloat(baseInput.value))
                    .toFixed(2);

                recalcularFactura();
            });
        };

        const addRecalcularPorTotal = function(element) {
            element.addEventListener('change', function() {

                const ivaInput = this.parentNode.parentNode.querySelector(
                    'input[data-name="iva"]');

                const totalInput = this.parentNode.parentNode.querySelector(
                    'input[data-name="total"]');

                const baseInput = this.parentNode.parentNode.querySelector(
                    'input[data-name="base"]');

                const tipoInput = this.parentNode.parentNode.querySelector(
                    'select[data-name="tipo"]');

                const tipo = tipoInput.options[tipoInput.selectedIndex].dataset.iva / 100;

                totalInput.value = totalInput.value.replace(',', '.');

                if (isNaN(totalInput.value)) {
                    totalInput.value = 0.0;
                }

                totalInput.value = parseFloat(totalInput.value).toFixed(2);

                baseInput.value = (parseFloat(totalInput.value) / (1 + parseFloat(tipo))).toFixed(
                    2);

                ivaInput.value = (baseInput.value * tipo).toFixed(2);

                const lineMirror = this.parentNode.parentNode.querySelector(
                    '#iva-line-input-mirror');
                lineMirror.innerText = ivaInput.value + '€';

                recalcularFactura();
            });
        };


        let index = <?php echo $index; ?>;

        document.getElementById('add-invoiceLine').addEventListener('click', function() {
            const newTr = document.createElement('tr');
            newTr.innerHTML = `
    <td>
        <input type="text"
            name="invoiceLines[${index}][concept]" required placeholder="Concepto..." class="form-control" data-name="concept">
    </td>
    <td>
        <select name="invoiceLines[${index}][service_type_id]" data-init-plugin="select2" class="select2 full-width" data-name="tipo"></select>
    </td>
    <td style="max-width:100px">
        <input  type="text"
            name="invoiceLines[${index}][base]" value="0.00" id="input-base" required placeholder="Base..." class="form-control text-right" data-name="base">
            </td>
    <td style="max-width:100px" class="align-middle">
        <input type="text"
            name="invoiceLines[${index}][iva]" value="0.00" id="input-iva" required placeholder="IVA..." class="form-control" data-name="iva" hidden>
        <div id="iva-line-input-mirror" class="text-right">

        </div>
    </td>
    <td style="max-width:100px">
        <input type="text" value="0.00" id="total"
            name="invoiceLines[${index}][total]" required placeholder="Total..." class="form-control text-right" data-name="total">
    </td>
    <td>
        <button type="button" class="btn btn-danger delete_invoiceLine"> <i class="fas fa-trash"></i>
        </button>
        </td>
        `;
            index++;
            newTr.querySelector('select').innerHTML = options;
            document.getElementById('t-body').appendChild(newTr);
            $(newTr).find('.select2').select2();

            addDeleteRowOnClick([...newTr.getElementsByClassName('delete_invoiceLine')][0]);
            addRecalcularporBase([...newTr.querySelectorAll('input[data-name="base"]')][0]);
            addRecalcularporTipo([...newTr.querySelectorAll('select[data-name="tipo"]')][0]);
            addRecalcularPorTotal([...newTr.querySelectorAll('input[data-name="total"]')][0]);
        });

        [...document.querySelectorAll('select[data-name = "tipo"')].forEach(element => {
            addRecalcularporTipo(element);
        });


        [...document.querySelectorAll('input[data-name="base"]')].forEach(element => {
            addRecalcularporBase(element);
        });

        [...document.querySelectorAll('input[data-name="total"]')].forEach(element => {
            addRecalcularPorTotal(element);
        });

        [...document.getElementsByClassName('delete_invoiceLine')].forEach(element => {
            addDeleteRowOnClick(element);
        });

        recalcularFactura();

        if (index == 0) {
            document.getElementById('add-invoiceLine').click();
        }

    });
</script>

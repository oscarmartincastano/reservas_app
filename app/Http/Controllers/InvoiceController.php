<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Instalacion;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instalacion = Instalacion::where('slug', request('slug_instalacion'))->firstOrFail();

        return view('invoices.index', [
            'instalacion' => $instalacion,
            'invoices' => $instalacion->invoices()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $instalacion = Instalacion::where('slug', request('slug_instalacion'))->firstOrFail();

        return view('invoices.create', [
            'instalacion' => $instalacion,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreInvoiceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoiceRequest $request)
    {
        $instalacion = Instalacion::where('slug', request('slug_instalacion'))->firstOrFail();

        $file_path = null;
        if ($request->hasFile('file')) {
            $file_path = auth()->id() . '_' . time() . '.' . $request->file->extension();

            $path = Storage::putFile(
                'public/invoices',
                $request->file('file')
            );

            if (!$path) {
                return redirect(route('gastos.index'))->with('error', 'Error al subir el archivo');
            }

            $file_path = basename($path);
        }

        $invoice = new Invoice(
            array_merge(
                $request->validated(),
                [
                    'file' => $file_path,
                ]
            )
        );
        $invoice->instalacion_id = $instalacion->id;
        $invoice->save();

        foreach ($request->invoiceLines as $invoiceLine) {
            $invoiceLine = new InvoiceLine($invoiceLine);
            $invoiceLine->invoice_id = $invoice->id;
            $invoiceLine->save();
        }
        return redirect()->route('invoices.index', ['slug_instalacion' => $instalacion->slug])->with('success', 'Factura creada correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->firstOrFail();
        $invoice = Invoice::where('id', request()->id)->firstOrFail();

        return view('invoices.show', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->firstOrFail();
        $invoice = Invoice::where('id', request()->id)->firstOrFail();

        return view('invoices.edit', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInvoiceRequest  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->firstOrFail();
        $invoice = Invoice::where('id', request()->id)->firstOrFail();

        $data = $request->validated();
        $file_path = null;
        if ($request->hasFile('file')) {
            $file_path = auth()->id() . '_' . time() . '.' . $request->file->extension();

            $path = Storage::putFile(
                'public/invoices',
                $request->file('file')
            );

            if (!$path) {
                return redirect(route('gastos.index'))->with('error', 'Error al subir el archivo');
            }

            $file_path = basename($path);
            $data['file'] = $file_path;
        }

        $invoice->invoiceLines()->delete();

        $invoice->update($data);

        foreach ($request->invoiceLines as $invoiceLine) {
            $invoiceLine = new InvoiceLine($invoiceLine);
            $invoiceLine->invoice_id = $invoice->id;
            $invoiceLine->save();
        }

        return redirect()->route('invoices.index', ['slug_instalacion' => $invoice->instalacion->slug])->with('success', 'Factura actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        $invoice = Invoice::where('id', request()->id)->firstOrFail();
        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->firstOrFail();

        $invoice->invoiceLines()->delete();
        $invoice->delete();

        return redirect()->route('invoices.index', ['slug_instalacion' => $instalacion->slug])->with('success', 'Factura eliminada correctamente');
    }
}

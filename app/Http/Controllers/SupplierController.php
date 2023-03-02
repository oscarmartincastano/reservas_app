<?php

namespace App\Http\Controllers;

use App\Models\Instalacion;
use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->first();

        $suppliers = $instalacion->suppliers;

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSupplierRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSupplierRequest $request)
    {
        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->first();

        $supplier = new Supplier($request->validated());

        $supplier->instalacion()->associate($instalacion);

        $supplier->save();

        return redirect()->route('suppliers.index', ['slug_instalacion' => $instalacion->slug])->with('success', 'Proveedor creado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        $supplier = Supplier::find(request()->id);
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        $supplier = Supplier::find(request()->id);
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSupplierRequest  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {

        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->first();
        $supplier = Supplier::where('id', $supplier->id);

        $supplier->update($request->validated());

        return redirect()->route('suppliers.index', ['slug_instalacion' => $instalacion->slug])->with('success', 'Proveedor actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $supplier = Supplier::find(request()->id);

        $supplier->delete();

        return redirect()->back()->with('success', 'Proveedor eliminado correctamente');
    }
}

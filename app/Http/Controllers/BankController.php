<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Http\Requests\StoreBankRequest;
use App\Http\Requests\UpdateBankRequest;
use App\Models\Instalacion;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->first();

        $banks = $instalacion->banks;

        return view('banks.index', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('banks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBankRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBankRequest $request)
    {

        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->first();

        $bank = new Bank($request->validated());

        $bank->instalacion()->associate($instalacion);

        $bank->save();

        return redirect()->route('banks.index', ['slug_instalacion' => $instalacion->slug]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        $bank = Bank::find(request()->id);
        return view('banks.show', compact('bank'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        $bank = Bank::find(request()->id);
        return view('banks.edit', compact('bank'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBankRequest  $request
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBankRequest $request, Bank $bank)
    {
        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->first();
        $bank = Bank::find(request()->id);
        $bank->update($request->validated());

        return redirect()->route('banks.index', ['slug_instalacion' => $bank->instalacion->slug]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        $bank = Bank::find(request()->id);
        $bank->delete();

        return redirect()->route('banks.index', ['slug_instalacion' => $bank->instalacion->slug]);
    }
}

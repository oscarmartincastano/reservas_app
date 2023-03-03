<?php

namespace App\Http\Controllers;

use App\Models\Instalacion;
use App\Models\ServiceType;
use App\Http\Requests\StoreServiceTypeRequest;
use App\Http\Requests\UpdateServiceTypeRequest;

class ServiceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->firstOrFail();
        $serviceTypes = $instalacion->serviceTypes()->get();

        return view('service_types.index', compact('instalacion', 'serviceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->firstOrFail();

        return view('service_types.create', compact('instalacion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreServiceTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServiceTypeRequest $request)
    {
        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->firstOrFail();
        $serviceType = $instalacion->serviceTypes()->create($request->validated());

        return redirect()->route('serviceTypes.index', $instalacion->slug)->with('success', 'Tipo de servicio creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceType $serviceType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceType $serviceType)
    {
        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->firstOrFail();
        $serviceType = ServiceType::find(request()->id);

        return view('service_types.edit', compact('instalacion', 'serviceType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateServiceTypeRequest  $request
     * @param  \App\Models\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateServiceTypeRequest $request, ServiceType $serviceType)
    {
        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->firstOrFail();
        $serviceType = ServiceType::find(request()->id);
        $serviceType->update($request->validated());

        return redirect()->route('serviceTypes.index', $instalacion->slug)->with('success', 'Tipo de servicio actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceType $serviceType)
    {
        $serviceType = ServiceType::find(request()->id);
        $serviceType->delete();

        return redirect()->back()->with('success', 'Tipo de servicio eliminado con éxito');
    }
}

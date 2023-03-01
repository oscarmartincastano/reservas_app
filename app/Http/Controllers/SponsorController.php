<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Models\Instalacion;
use App\Http\Requests\StoreSponsorRequest;
use App\Http\Requests\UpdateSponsorRequest;
use Illuminate\Support\Str;

class SponsorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->firstOrFail();
        return view('sponsors.index', [
            'sponsors' => Sponsor::all(),
            'instalacion' => $instalacion,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sponsors.create', [
            'instalacion' => Instalacion::where('slug', request()->slug_instalacion)->firstOrFail(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSponsorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSponsorRequest $request)
    {

        $validated = $request->validated();

        $logo = $request->file('logo');
        $logoName = hash('sha256', time() . "-" . Str::slug($validated['name'])) . "." . $request->file('logo')->getClientOriginalExtension();
        $logo->move(public_path(Sponsor::$LOGO_PATH), $logoName);

        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->firstOrFail();

        Sponsor::create(
            array_merge(
                $request->validated(),
                [
                    'instalacion_id' => $instalacion->id,
                    'logo' => $logoName ?? null,
                ]


            )
        );
        return back()->with('status', 'Patrocinador creado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sponsor  $sponsor
     * @return \Illuminate\Http\Response
     */
    public function show(Instalacion $instalacion, Sponsor $sponsor)
    {
        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->firstOrFail();
        $sponsor = Sponsor::where(
            'id',
            request()->id
        )->firstOrFail();

        return view('sponsors.show', [
            'instalacion' => $instalacion,
            'sponsor' => $sponsor,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sponsor  $sponsor
     * @return \Illuminate\Http\Response
     */
    public function edit(Instalacion $instalacion, Sponsor $sponsor)
    {

        $instalacion = Instalacion::where('slug', request()->slug_instalacion)->firstOrFail();
        $sponsor = Sponsor::where(
            'id',
            request()->id
        )->firstOrFail();

        return view('sponsors.edit', [
            'instalacion' => $instalacion,
            'sponsor' => $sponsor,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSponsorRequest  $request
     * @param  \App\Models\Sponsor  $sponsor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSponsorRequest $request)
    {

        $sponsor = Sponsor::find(request()->id);

        $validated = $request->validated();

        $data = $request->validated();

        if ($request->hasFile('logo')) {

            $logo = $request->file('logo');
            $logoName = hash('sha256', time() . "-" . Str::slug($validated['name'])) . "." . $request->file('logo')->getClientOriginalExtension();
            $logo->move(public_path(Sponsor::$LOGO_PATH), $logoName);
            $data = array_merge(
                $data,
                [
                    'logo' => $logoName ?? null,
                ]
            );
        }

        $sponsor->update(
            $data
        );

        return redirect(
            route('sponsors.index', ['slug_instalacion' => $sponsor->instalacion->slug])

        )->with('status', 'Patrocinador actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sponsor  $sponsor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instalacion $instalacion, Sponsor $sponsor)
    {
        Sponsor::find(request()->id)->delete();
        return back()->with('status', 'Patrocinador eliminado con éxito');
    }
}

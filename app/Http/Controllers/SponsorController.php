<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Models\Instalacion;
use App\Http\Requests\StoreSponsorRequest;
use App\Http\Requests\UpdateSponsorRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SponsorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        config([
            'database.connections.dynamic_superadmin' => [
                'driver' => 'mysql',
                'host' => env('DB_SUPERADMIN_HOST', '127.0.0.1'),
                'port' => env('DB_SUPERADMIN_PORT', '3306'),
                'database' => env('DB_SUPERADMIN_DATABASE', 'superadmin'),
                'username' => env('DB_SUPERADMIN_USERNAME', 'forge'),
                'password' => env('DB_SUPERADMIN_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ],
        ]);
        // Extraer el slug de la URL eliminando "https://gestioninstalacion.es/"
        $slug = str_replace('https://gestioninstalacion.es/', '', request()->slug_instalacion);
       // Cambiar la conexión a 'superadmin'
    $ver_sponsor = DB::connection('superadmin')
    ->table('superadmin')
    ->where('url', 'https://gestioninstalacion.es/' . $slug)
    ->first();
        if ($ver_sponsor->ver_sponsor == 1) {
            $instalacion = Instalacion::where('slug', request()->slug_instalacion)->firstOrFail();
            $sponsor = Sponsor::where('instalacion_id',$instalacion->id)->get();

            return view('sponsors.index', [
                'sponsors' => $sponsor,
                'instalacion' => $instalacion,
            ]);
        }else{
            return back()->with('status', 'No tienes permisos para ver los patrocinadores');
        }
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
        config([
            'database.connections.dynamic_superadmin' => [
                'driver' => 'mysql',
                'host' => env('DB_SUPERADMIN_HOST', '127.0.0.1'),
                'port' => env('DB_SUPERADMIN_PORT', '3306'),
                'database' => env('DB_SUPERADMIN_DATABASE', 'superadmin'),
                'username' => env('DB_SUPERADMIN_USERNAME', 'forge'),
                'password' => env('DB_SUPERADMIN_PASSWORD', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ],
        ]);
        // Extraer el slug de la URL eliminando "https://gestioninstalacion.es/"
        $slug = str_replace('https://gestioninstalacion.es/', '', request()->slug_instalacion);
        $ver_sponsor = DB::table('superadmin')->where('url','https://gestioninstalacion.es/'.$slug)->first();

        if ($ver_sponsor->ver_sponsor == 1) {
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

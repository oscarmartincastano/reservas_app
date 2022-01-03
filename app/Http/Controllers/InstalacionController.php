<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pista;
use App\Models\Instalacion;

class InstalacionController extends Controller
{
    public function index() {
        $instalacion = auth()->user()->instalacion[0];
        return view('instalacion.home', compact('instalacion'));
    }

    public function edit_info(Request $request) {
        $instalacion = auth()->user()->instalacion[0]->toArray();

        return view('instalacion.editdata.edit', compact('instalacion'));
    }

    public function editar_info(Request $request) {
        $data = $request->all();
        array_shift($data);

        $instalacion = auth()->user()->instalacion[0];
        
        Instalacion::find($instalacion->id)->update($data);
        return redirect('/');
    }

    public function pistas() {
        $instalacion = auth()->user()->instalacion[0];
        return view('instalacion.pistas.list', compact('instalacion'));
    }

    public function add_pista_view() {
        $instalacion = auth()->user()->instalacion[0];
        return view('instalacion.pistas.add', compact('instalacion'));
    }

    public function add_pista(Request $request) {
        $instalacion = auth()->user()->instalacion[0];

        $data = $request->all();
        $data['id_instalacion'] = $instalacion->id;
        $data['horario'] = serialize($request->horario);

        Pista::create($data);

        return redirect('/pistas');
    }

    public function edit_pista_view(Request $request) {
        $instalacion = auth()->user()->instalacion[0];
        $pista = Pista::find($request->id);
        return view('instalacion.pistas.edit', compact('instalacion', 'pista'));
    }

    public function edit_pista(Request $request) {

        $data = $request->all();
        array_shift($data);
        $data['horario'] = serialize($request->horario);

        Pista::where('id', $request->id)->update($data);
        
        return redirect('/pistas');
    }

    public function configuracion(Request $request) {
        $instalacion = auth()->user()->instalacion[0];
        
        return view('instalacion.configuraciones.list', compact('instalacion'));
    }

    public function users() {
        $instalacion = auth()->user()->instalacion[0];
        return view('instalacion.users.list', compact('instalacion'));
    }
}
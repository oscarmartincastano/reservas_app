<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pista;
use App\Models\Instalacion;
use App\Models\User;

class InstalacionController extends Controller
{
    public function index() {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.home', compact('instalacion'));
    }

    public function edit_info(Request $request) {
        $instalacion = auth()->user()->instalacion->toArray();

        return view('instalacion.editdata.edit', compact('instalacion'));
    }

    public function editar_info(Request $request) {
        $data = $request->all();
        array_shift($data);

        $instalacion = auth()->user()->instalacion;
        
        Instalacion::find($instalacion->id)->update($data);
        return redirect('/admin/');
    }

    public function pistas() {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.pistas.list', compact('instalacion'));
    }

    public function add_pista_view() {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.pistas.add', compact('instalacion'));
    }

    public function add_pista(Request $request) {
        $instalacion = auth()->user()->instalacion;

        $data = $request->all();
        $data['id_instalacion'] = $instalacion->id;

        $horario = $request->horario;
        foreach ($horario as $indexhor => $item) {
            foreach ($item['intervalo'] as $indexinterval => $intervalo) {
                $a = new \DateTime($intervalo['hfin']);
                $b = new \DateTime($intervalo['hinicio']);
                $interval = $a->diff($b);
                $diff_minutes = $interval->format("%h") * 60;
                $diff_minutes += $interval->format("%i");
                $numero_veces = $diff_minutes/$intervalo['secuencia'];

                if (!is_int($diff_minutes/$intervalo['secuencia'])) {
                    $hora = new \DateTime($intervalo['hinicio']);
                    for ($i=0; $i < floor($numero_veces); $i++) { 
                        $hora->modify("+{$intervalo['secuencia']} minutes");
                    }
                    
                    $horario[$indexhor]['intervalo'][$indexinterval]['hfin'] = $hora->format('H:i');
                }

            }
        }
        
        $data['horario'] = serialize($request->horario);

        Pista::create($data);

        return redirect('/admin/pistas');
    }

    public function edit_pista_view(Request $request) {
        $instalacion = auth()->user()->instalacion;
        $pista = Pista::find($request->id);
        return view('instalacion.pistas.edit', compact('instalacion', 'pista'));
    }

    public function edit_pista(Request $request) {

        $data = $request->all();
        array_shift($data);

        $horario = $request->horario;
        foreach ($horario as $indexhor => $item) {
            foreach ($item['intervalo'] as $indexinterval => $intervalo) {
                $a = new \DateTime($intervalo['hfin']);
                $b = new \DateTime($intervalo['hinicio']);
                $interval = $a->diff($b);
                $diff_minutes = $interval->format("%h") * 60;
                $diff_minutes += $interval->format("%i");
                $numero_veces = $diff_minutes/$intervalo['secuencia'];

                if (!is_int($diff_minutes/$intervalo['secuencia'])) {
                    $hora = new \DateTime($intervalo['hinicio']);
                    for ($i=0; $i < floor($numero_veces); $i++) { 
                        $hora->modify("+{$intervalo['secuencia']} minutes");
                    }
                    
                    $horario[$indexhor]['intervalo'][$indexinterval]['hfin'] = $hora->format('H:i');
                }

            }
        }
        $data['horario'] = serialize($horario);
        /* return $data; */
        Pista::where('id', $request->id)->update($data);
        
        return redirect('/admin/pistas');
    }

    public function configuracion(Request $request) {
        $instalacion = auth()->user()->instalacion;
        
        return view('instalacion.configuraciones.list', compact('instalacion'));
    }

    public function users() {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.users.list', compact('instalacion'));
    }

    public function add_user_view(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.users.add', compact('instalacion'));
    }

    public function add_user(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'id_instalacion' => $request->id_instalacion,
            'rol' => 'user',
            'password' => \Hash::make($request->password),
        ]);

        return redirect('/admin/users');
    }

}
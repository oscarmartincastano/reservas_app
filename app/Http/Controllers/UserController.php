<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Instalacion;
use App\Models\Pista;
use App\Models\Reserva;
use App\Models\User;
use DateTime;

class UserController extends Controller
{
    public function index() {
        $instalacion = Instalacion::first();
        if (count($instalacion->pistas)>1 || count($instalacion->pistas) == 0) {
            return view('home', compact('instalacion'));
        }
        return redirect("{$instalacion->slug}/{$instalacion->pistas->first()->tipo}");
    }

    public function pistas(Request $request) {

        $current_date = new DateTime();

        $plus_date = new DateTime();
        $plus_date->add(new \DateInterval('P6D'));

        $period = new \DatePeriod($current_date, \DateInterval::createFromDateString('1 day'), $plus_date);

        $pistas = Pista::where('tipo', $request->deporte)->get();

        if (isset($request->id_pista)) {
            $pista_selected = Pista::find($request->id_pista);
        } else{
            $pista_selected = $pistas[0];
        }

        return view('pista.pista', compact('period', 'pistas', 'pista_selected'));
    }

    public function reserva(Request $request)
    {
        $reserva = Reserva::where([['id_pista', $request->id_pista], ['timestamp', $request->timestamp], ['estado', 'active']])->first();
        
        $pista = Pista::find($request->id_pista);
        $fecha = $request->timestamp;

        if (count(auth()->user()->reservas_activas) >= auth()->user()->instalacion->configuracion->num_reservas_por_user) {
            return view('pista.reservanodisponible')->with('maxreservas', 'true');
        }
        if ($reserva) {
            return view('pista.reservanodisponible');
        }
        
        foreach ($pista->horario_deserialized as $item){
            if (in_array(date('w', $fecha), $item['dias']) || ( date('w', $fecha) == 0 && in_array(7, $item['dias']) )){
                foreach ($item['intervalo'] as $index => $intervalo){
                    $a = new \DateTime($intervalo['hfin']);
                    $b = new \DateTime($intervalo['hinicio']);
                    $interval = $a->diff($b);
                    $diff_minutes = $interval->format("%h") * 60;
                    $diff_minutes += $interval->format("%i");
                    $numero_veces = $diff_minutes/$intervalo['secuencia'];

                    $hora = new \DateTime($intervalo['hinicio']);
                    for ($i=0; $i < floor($numero_veces); $i++) { 
                        if ($hora->format('h:i') == date(date('h:i', $fecha))) {
                            $secuencia = $intervalo['secuencia'];
                            $number = $numero_veces - $i;
                            /* $hfin = date('h:i',strtotime (date('h:i', $fecha) . " +{$intervalo['secuencia']} minutes")); */
                        }
                        $hora->modify("+{$intervalo['secuencia']} minutes");
                    }
                }
            }
        }

        return view('pista.reserva', compact('pista', 'fecha', 'secuencia', 'number'));
    }

    public function reservar(Request $request)
    {
        $minutos_totales = $request->secuencia * $request->tarifa;
        
        $reserva = Reserva::create([
            'id_pista' => $request->id_pista,
            'id_usuario' => auth()->user()->id,
            'timestamp' => $request->timestamp,
            'fecha' => date('Y/m/d', $request->timestamp),
            'hora' => date('Hi', $request->timestamp),
            'tarifa' => $request->tarifa,
            'minutos_totales' => $minutos_totales
        ]);

        if (isset($request->observaciones)) {
            $reserva->update(['observaciones' => $request->observaciones]);
        }
        
        return redirect("/{$request->slug_instalacion}/mis-reservas");
    }

    public function mis_reservas(Request $request)
    {
        $reservas = auth()->user()->reservas;

        return view('user.misreservas', compact('reservas'));
    }

    public function cancel_reservas(Request $request)
    {
        Reserva::find($request->id)->update(['estado' => 'canceled']);

        return redirect()->back();
    }

    public function perfil(Request $request)
    {
        return view('user.perfil');
    }

    public function edit_perfil(Request $request)
    {
        $data = $request->all();

        array_shift($data);

        if (!isset($request->password)) {
            unset($data['password']);
            unset($data['password_rep']);
            User::where('id', auth()->user()->id)->update($data);
        }else {
            if ($data['password'] == $data['password_rep']) {
                unset($data['password_rep']);
                $data['password'] = Hash::make($request->password);
                User::where('id', auth()->user()->id)->update($data);
            } else {
                return redirect()->back()->with('error', 'true');
            }
        }
        return redirect()->back()->with('success', 'true');
    }
}
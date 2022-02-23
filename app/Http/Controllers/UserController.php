<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewReserva;
use App\Models\Valor_campo_personalizado;
use App\Models\Instalacion;
use App\Models\Pista;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use DateTime;

class UserController extends Controller
{
    public function index(Request $request) {
        
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        if (count($instalacion->deportes)>1 || count($instalacion->pistas) == 0) {
            return view('home', compact('instalacion'));
        }
        return redirect("{$instalacion->slug}/{$instalacion->pistas->first()->tipo}");
    }

    public function pistas(Request $request) {
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        if (isset($request->semana)) {
            $current_date = new DateTime(date("Y-m-d", strtotime(date('Y-m-d')."+{$request->semana} weeks")));
            $plus_date = new DateTime(date("Y-m-d", strtotime(date('Y-m-d')."+{$request->semana} weeks")));
            $plus_date->add(new \DateInterval('P6D'));
        }elseif (isset($request->dia)) {
            $fecha = Carbon::createFromFormat('d/m/Y', $request->dia)->format('d-m-Y');
            $current_date = new DateTime($fecha);
            $plus_date = new DateTime($fecha);
            $plus_date->add(new \DateInterval('P6D'));
        }else{
            $current_date = new DateTime();
            $plus_date = new DateTime();
            $plus_date->add(new \DateInterval('P6D'));
        }

        $period = new \DatePeriod($current_date, \DateInterval::createFromDateString('1 day'), $plus_date);

        $pistas = Pista::where([['tipo', $request->deporte], ['id_instalacion', $instalacion->id]])->get();

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
        $user = User::find(auth()->user()->id);
        $fecha = $request->timestamp;

        /* return dd($user->numero_total_reservas_tipo($pista->tipo)); */
        if (!$pista->check_reserva_valida($request->timestamp)) {
            return view('pista.reservanodisponible');
        }
        if (!$user->check_maximo_reservas_espacio($pista->tipo)) {
            $max_reservas = true;
            return view('pista.reservanodisponible', compact('max_reservas'));
        }
        
        foreach ($pista->horario_deserialized as $item){
            if (in_array(date('w', $fecha), $item['dias']) || ( date('w', $fecha) == 0 && in_array(7, $item['dias']) )){
                foreach ($item['intervalo'] as $index => $intervalo){
                    $hora = new \DateTime(date('Y-m-d H:i', $fecha));
                    $a = new \DateTime(date('Y-m-d', $fecha) . ' ' . $intervalo['hfin']);
                    $b = new \DateTime(date('Y-m-d', $fecha) . ' ' . $intervalo['hinicio']);
                    if ($hora >= $b && $hora <= $a) {
                        $secuencia = $intervalo['secuencia'];
                        $interval = $a->diff($b);
                        $diff_minutes = $interval->format("%h") * 60;
                        $diff_minutes += $interval->format("%i");
                        $numero_veces = $diff_minutes/$secuencia;
                        
                        for ($i=0; $i < floor($numero_veces)+1; $i++) {
                            if (!$pista->check_reserva_valida($hora->getTimestamp())) {
                                $number = $i;
                                break;
                            } 
                            if ($hora->format('H:i') == $a->format('H:i')) {
                                $number = $i;
                                break;
                            }
                            $hora->modify("+{$secuencia} minutes");
                        }
                    }
                }
            }
        }
        return view('pista.reserva', compact('pista', 'fecha', 'secuencia', 'number'));
    }

    public function reservar(Request $request)
    {
        $pista = Pista::find($request->id_pista);
        if (!$pista->check_reserva_valida($request->timestamp)) {
            return redirect()->back();
        }

        $minutos_totales = $request->secuencia * $request->tarifa;

        $timestamps[0] = (int)$request->timestamp;
        
        if ($request->tarifa > 1) {
            for ($i=1; $i < $request->tarifa; $i++) {
                $timestamps[$i] = \Carbon\Carbon::parse(date('d-m-Y H:i:s', $request->timestamp))->addMinutes($request->secuencia*$i)->timestamp;
            }
        }
        
        $reserva = Reserva::create([
            'id_pista' => $request->id_pista,
            'id_usuario' => auth()->user()->id,
            'timestamp' => $request->timestamp,
            'horarios' => serialize($timestamps),
            'fecha' => date('Y/m/d', $request->timestamp),
            'hora' => date('Hi', $request->timestamp),
            'tarifa' => $request->tarifa,
            'minutos_totales' => $minutos_totales
        ]);

        if (isset($request->observaciones)) {
            $reserva->update(['observaciones' => $request->observaciones]);
        }
        
        if (isset($request->campo_adicional)) {
            foreach ($request->campo_adicional as $id_campo => $valor) {
                Valor_campo_personalizado::create([
                    'id_reserva' => $reserva->id,
                    'id_campo' => $id_campo,
                    'valor' => $valor
                ]);
            }
        }

        Mail::to(auth()->user()->instalacion->user_admin->email)->send(new NewReserva(auth()->user(), $reserva));

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
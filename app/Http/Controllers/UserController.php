<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ReservaEspera;
use App\Models\Valor_campo_personalizado;
use App\Models\Instalacion;
use App\Models\Pista;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use DateTime;

class UserController extends Controller
{
    public function index(Request $request)
    {
        
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        
        if (count($instalacion->deportes) > 1 || count($instalacion->pistas) == 0) {
            return view('home', compact('instalacion'));
        }
        return redirect("{$instalacion->slug}/{$instalacion->pistas->first()->tipo}");
    }

    public function pistas(Request $request)
    {
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();

        $pistas = Pista::where([['tipo', $request->deporte], ['id_instalacion', $instalacion->id], ['active', 1]])->get();

        if (isset($request->id_pista)) {
            $pista_selected = Pista::find($request->id_pista);
        } else {
            if (isset($pistas[0])) {
                $pista_selected = $pistas[0];
            } else {
                abort(404);
            }
        }
        if (isset($request->semana)) {
            if ($pista_selected->max_dias_antelacion > 10) {
                $current_date = new DateTime(date("Y-m-d", strtotime(date('Y-m-d') . "+{$request->semana} weeks")));
                $plus_date = new DateTime(date("Y-m-d", strtotime(date('Y-m-d') . "+{$request->semana} weeks")));
                $plus_date->add(new \DateInterval('P8D'));
            } else {
                $current_date = new DateTime(date("Y-m-d", strtotime(date('Y-m-d') . "+" . $request->semana * $pista_selected->max_dias_antelacion . " days")));
                $plus_date = new DateTime(date("Y-m-d", strtotime(date('Y-m-d') . "+" . $request->semana * $pista_selected->max_dias_antelacion . " days")));
                $plus_date->add(new \DateInterval('P' . $pista_selected->max_dias_antelacion . 'D'));
            }
        } elseif (isset($request->dia)) {
            if ($pista_selected->max_dias_antelacion > 10) {
                $fecha = Carbon::createFromFormat('d/m/Y', $request->dia)->format('d-m-Y');
                $current_date = new DateTime($fecha);
                $plus_date = new DateTime($fecha);
                $plus_date->add(new \DateInterval('P7D'));
            } else {
                $fecha = Carbon::createFromFormat('d/m/Y', $request->dia)->format('d-m-Y');
                $current_date = new DateTime($fecha);
                $plus_date = new DateTime($fecha);
                $plus_date->add(new \DateInterval('P' . $pista_selected->max_dias_antelacion . 'D'));
            }
        } else {
            if ($pista_selected->max_dias_antelacion > 10) {
                $current_date = new DateTime();
                $plus_date = new DateTime();
                $plus_date->add(new \DateInterval('P7D'));
            } else {
                $current_date = new DateTime();
                $plus_date = new DateTime();
                $plus_date->add(new \DateInterval('P' . $pista_selected->max_dias_antelacion . 'D'));
            }
        }

        $date_for_valid = new DateTime();
        $date_for_valid->add(new \DateInterval('P' . $pista_selected->max_dias_antelacion . 'D'));

        $valid_period = new \DatePeriod(new DateTime(), \DateInterval::createFromDateString('1 day'), $date_for_valid);
        $period = new \DatePeriod($current_date, \DateInterval::createFromDateString('1 day'), $plus_date);

        $horarios_final = $pista_selected->horarios_final($period);

        return view('pista.pista', compact('period', 'valid_period', 'pistas', 'pista_selected', 'instalacion', 'horarios_final'));
    }

    public function reserva(Request $request)
    {
        $reserva = Reserva::where([['id_pista', $request->id_pista], ['timestamp', $request->timestamp], ['estado', 'active']])->first();

        $pista = Pista::find($request->id_pista);
        $user = User::find(auth()->user()->id);
        $fecha = $request->timestamp;
        $res_siguiente_espera = $pista->siguiente_reserva_lista_espera($request->timestamp);

        if (!$pista->check_reserva_valida($request->timestamp) && !$res_siguiente_espera) {
            return view('pista.reservanodisponible');
        }
        if ($res_siguiente_espera && $user->reservas_en_espera()->count() >= 1) {
            $error = 'No puedes apuntarte a la lista de espera porque ya estás en lista de espera de otro intervalo horario.';
            return view('pista.reservanodisponible', compact('error'));
        }
        if (!$user->check_maximo_reservas_espacio($pista->tipo) && !$res_siguiente_espera) {
            $max_reservas = true;
            return view('pista.reservanodisponible', compact('max_reservas'));
        }
        if (!$user->aprobado) {
            $user_no_valid = true;
            return view('pista.reservanodisponible', compact('user_no_valid'));
        }


        foreach ($pista->horario_deserialized as $item) {
            if (in_array(date('w', $fecha), $item['dias']) || (date('w', $fecha) == 0 && in_array(7, $item['dias']))) {
                foreach ($item['intervalo'] as $index => $intervalo) {
                    $hora = new \DateTime(date('Y-m-d H:i', $fecha));
                    $a = new \DateTime(date('Y-m-d', $fecha) . ' ' . $intervalo['hfin']);
                    $b = new \DateTime(date('Y-m-d', $fecha) . ' ' . $intervalo['hinicio']);
                    if ($hora >= $b && $hora <= $a) {
                        $secuencia = $intervalo['secuencia'];
                        $interval = $a->diff($b);
                        $diff_minutes = $interval->format("%h") * 60;
                        $diff_minutes += $interval->format("%i");
                        $numero_veces = $diff_minutes / $secuencia;

                        for ($i = 0; $i < floor($numero_veces) + 1; $i++) {
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
        $user = User::find(auth()->user()->id);
        $reserva_espera = $pista->siguiente_reserva_lista_espera($request->timestamp);

        Log::channel('actividadreserva')->info("#{$user->id}: realiza reserva.");

        if ($reserva_espera && $user->reservas_en_espera()->count() >= 1) {
            return redirect()->back();
        }

        if (!$pista->check_reserva_valida($request->timestamp) && !$reserva_espera) {
            Log::channel('actividadreserva')->info("#{$user->id}: reserva no válida (tramo de reserva no disponible)");
            return redirect()->back();
        }

        if (!$user->check_maximo_reservas_espacio($pista->tipo) && !$reserva_espera) {
            Log::channel('actividadreserva')->info("#{$user->id}: reserva no válida (máximo de reservas del usuario).");
            $max_reservas = true;
            return view('pista.reservanodisponible', compact('max_reservas'));
        }

        $minutos_totales = $request->secuencia * $request->tarifa;

        $timestamps[0] = (int)$request->timestamp;

        if ($request->tarifa > 1) {
            for ($i = 1; $i < $request->tarifa; $i++) {
                $timestamps[$i] = \Carbon\Carbon::parse(date('d-m-Y H:i:s', $request->timestamp))->addMinutes($request->secuencia * $i)->timestamp;
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
            'minutos_totales' => $minutos_totales,
            'lista_espera' => $reserva_espera,
            'estado' => $reserva_espera ? 'espera' : 'active'
        ]);

        Log::channel('actividadreserva')->info("#{$user->id}: reserva realizada (#{$reserva->id}).");

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

        return redirect("/{$request->slug_instalacion}/mis-reservas");
    }

    public function mis_reservas(Request $request)
    {
        $reservas = Reserva::where('id_usuario', auth()->user()->id)->orderBy('created_at', 'desc')->simplePaginate();

        return view('user.misreservas', compact('reservas'));
    }

    public function cancel_reservas(Request $request)
    {   
        $reserva = Reserva::find($request->id);
        $estado_actual = $reserva->estado;
        $reserva->update(['estado' => 'canceled']);
        if ($estado_actual == 'active') {
            $new_reserva = Reserva::where('id_pista', $reserva->id_pista)->where('timestamp', $reserva->timestamp)->where('estado', 'espera')->orderBy('created_at')->first();

            if ($new_reserva) {
                $new_reserva->update(['estado' => 'active']);
                Mail::to($new_reserva->user->email)->send(new ReservaEspera($new_reserva->user, $new_reserva));
            }
        }


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
        } else {
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

    public function normas_instalacion(Request $request)
    {

        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        if (!$instalacion->html_normas) {
            return redirect("/{$instalacion->slug}");
        }
        return view("normas", compact('instalacion'));
    }

    public function delete_perfil(Request $request)
    {
        $usuario = User::where('id', auth()->user()->id)->delete();
        return redirect()->to('/superate/Gimnasio');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewReserva;
use App\Mail\ReservaAdmin;
use App\Mail\ReservaEspera;
use App\Models\Pista;
use App\Models\Instalacion;
use App\Models\User;
use App\Models\Cobro;
use App\Models\Configuracion;
use App\Models\Reserva;
use App\Models\Desactivacion_reserva;
use App\Models\Campos_personalizados;
use App\Models\Pistas_campos_relation;
use App\Models\Valor_campo_personalizado;
use App\Models\Desactivaciones_periodicas;
use App\Models\Reservas_periodicas;
use App\Models\Excepciones_desactivaciones_periodicas;
use Intervention\Image\ImageManagerStatic as Image;
use DateTime;

class InstalacionController extends Controller
{
    public function home()
    {
        return view('inicio');
    }
    public function rangeWeek($date)
    {
        $dt = strtotime($date);
        return [
            'start' => date('N', $dt) == 1 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('last monday', $dt)),
            'end' => date('Y-m-d', strtotime('next monday', $dt)),
        ];
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function index(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        if (isset($request->semana)) {
            if (isset($request->week)) {
                $semana = $request->week;
                $d = (int) substr($semana, 6) * 7;
                $date = DateTime::createFromFormat('z Y', $d . ' ' . substr($semana, 0, 4));

                $semana = $this->rangeWeek(date('Y-m-d', strtotime($date->format('Y-m-d') . "+{$request->semana} weeks")));
            } else {
                $semana = $this->rangeWeek(date('Y-m-d', strtotime(date('Y-m-d') . "+{$request->semana} weeks")));
            }
        } else {
            $semana = $this->rangeWeek(date('Y-m-d'));
        }

        if (isset($request->week) && !isset($request->semana)) {
            $semana = $request->week;
            $year = substr($semana, 0, 4);
            $week = substr($semana, 6);

            $date = new DateTime();
            $date->setISODate($year, $week);

            $semana = $this->rangeWeek($date->format('Y-m-d'));
        }

        $period = new \DatePeriod(new DateTime($semana['start']), new \DateInterval('P1D'), new DateTime($semana['end']));
        $pistas = Pista::where('id_instalacion', auth()->user()->instalacion->id)
            ->where('active', 1)
            ->get();
        return view('instalacion.home', compact('instalacion', 'period', 'pistas', 'instalacion'));
    }

    public function eliminar_reserva(Request $request)
    {
        try {
            $reserva = Reserva::find($request->id);
            $reserva->delete();
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function reservas_dia(Request $request)
    {
        $pistas = Pista::where('id_instalacion', auth()->user()->instalacion->id)->get();
        $ret_pistas = [];
        foreach ($pistas as $i => $pista) {
            $ret_pistas[$i] = $pista;
            $ret_pistas[$i]['num_reservas_dia'] = count($pista->reservas_activas_por_dia($request->fecha));
            $ret_pistas[$i]['res_dia'] = $pista->horario_con_reservas_por_dia_admin($request->fecha);
        }

        return $ret_pistas;
    }

    public function reservas_dia_por_pista(Request $request)
    {
        $pista = Pista::find($request->id_pista);
        $ret_pistas = $pista;
        $ret_pistas['num_reservas_dia'] = count($pista->reservas_activas_por_dia($request->fecha));
        $ret_pistas['res_dia'] = $pista->horario_con_reservas_por_dia_admin($request->fecha);

        return $ret_pistas;
    }

    public function numero_reservas_dia_por_pista(Request $request)
    {
        $reservas = Reserva::where('estado', 'active')->where('fecha', $request->fecha)->get();

        $retorno = [];
        foreach ($reservas as $key => $value) {
            if (isset($retorno[$value['id_pista']])) {
                $retorno[$value['id_pista']] += 1;
            } else {
                $retorno[$value['id_pista']] = 1;
            }
        }

        return $retorno;
    }

    public function print_reserva(Request $request)
    {
        // esta funccion servira para imprimir una unica reserva como si fuera un ticket
        $reserva = Reserva::find($request->id);
        return view('instalacion.reservas.print', compact('reserva'));
    }

    public function imprimir_reservas(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;

        // $reservas = Reserva::whereIn('id_pista', Pista::where('id_instalacion', $instalacion->id)->pluck('id'))->where('fecha', $fecha)->get();
        $reservas = Reserva::whereIn('id_pista', Pista::where('id_instalacion', $instalacion->id)->pluck('id'))
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->get();

        $formato = $request->formato;

        // en la cabecera el día y mes y en el cuerpo los nombres de las salas con reserva con su nombre de reserva la hora,duración y observaciones. imprimir a pdf
        return view('instalacion.reservas.print_all', compact('reservas', 'fecha_inicio', 'fecha_fin', 'formato'));
    }

    public function validar_reserva(Request $request)
    {
        $reserva = Reserva::find($request->id);

        if ($reserva->estado == 'active') {
            $new_reserva = Reserva::where('id_pista', $reserva->id_pista)->where('timestamp', $reserva->timestamp)->where('estado', 'espera')->orderBy('created_at')->first();

            if ($new_reserva) {
                $new_reserva->update(['estado' => 'active']);
                Mail::to($new_reserva->user->email)->send(new ReservaEspera($new_reserva->user, $new_reserva));
            }

            if ($reserva->reserva_multiple) {
                Reserva::where([['id_pista', $reserva->id_pista], ['reserva_multiple', $reserva->reserva_multiple], ['timestamp', $reserva->timestamp], ['id_usuario', $reserva->id_usuario]])->update(['estado' => $request->accion, 'observaciones_admin' => $request->observaciones]);
                return redirect()
                    ->back()
                    ->with('dia_reserva_hecha', date('Y-m-d', $reserva->timestamp));
            }
            $reserva->update(['estado' => $request->accion, 'observaciones_admin' => $request->observaciones]);
            // return redirect()->back()->with('dia_reserva_hecha', date('Y-m-d', $reserva->timestamp));
            return true;
        }

        return false;
    }

    public function hacer_reserva_view(Request $request)
    {
        $reserva = Reserva::where([['id_pista', $request->id_pista], ['timestamp', $request->timestamp], ['estado', 'active']])->first();

        $pista = Pista::find($request->id_pista);
        $fecha = $request->timestamp;

        foreach ($pista->horario_deserialized as $item) {
            if (in_array(date('w', $fecha), $item['dias']) || (date('w', $fecha) == 0 && in_array(7, $item['dias']))) {
                foreach ($item['intervalo'] as $index => $intervalo) {
                    $a = new \DateTime($intervalo['hfin']);
                    $b = new \DateTime($intervalo['hinicio']);
                    $interval = $a->diff($b);
                    $diff_minutes = $interval->format('%h') * 60;
                    $diff_minutes += $interval->format('%i');
                    $numero_veces = $diff_minutes / $intervalo['secuencia'];

                    $hora = new \DateTime($intervalo['hinicio']);
                    for ($i = 0; $i < floor($numero_veces); $i++) {
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

        return view('instalacion.reservas.add', compact('pista', 'fecha', 'secuencia', 'number'));
    }

    public function hacer_reserva(Request $request)
    {
        $pista = Pista::find($request->id_pista);
        /* if (!$pista->check_reserva_valida($request->timestamp)) {
            return redirect()->back();
        } */

        if ($request->user_id == 'new_user') {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'id_instalacion' => auth()->user()->instalacion->id,
                'rol' => 'user',
                'password' => \Hash::make($this->generateRandomString(6)),
            ]);
            $request->user_id = $user->id;
        }

        $minutos_totales = $request->secuencia * $request->tarifa;

        $timestamps[0] = (int) $request->timestamp;

        if ($request->tarifa > 1) {
            for ($i = 1; $i < $request->tarifa; $i++) {
                $timestamps[$i] = \Carbon\Carbon::parse(date('d-m-Y H:i:s', $request->timestamp))->addMinutes($request->secuencia * $i)->timestamp;
            }
        }
        $reserva_multiple_id = null;
        if (isset($request->numero_reservas) && $request->numero_reservas > 1) {
            for ($i = 0; $i < $request->numero_reservas; $i++) {
                $reserva = Reserva::create([
                    'id_pista' => $request->id_pista,
                    'id_usuario' => $request->user_id,
                    'timestamp' => $request->timestamp,
                    'horarios' => serialize($timestamps),
                    'fecha' => date('Y/m/d', $request->timestamp),
                    'hora' => \Carbon\Carbon::createFromTimestamp($request->timestamp)->format('Hi'),
                    'tarifa' => $request->tarifa,
                    'minutos_totales' => $minutos_totales,
                    'creado_por' => 'admin',
                ]);

                $reserva_multiple_id = $i == 0 ? $reserva->id : $reserva_multiple_id;
                $reserva->update(['reserva_multiple' => $reserva_multiple_id]);

                if (isset($request->observaciones)) {
                    $reserva->update(['observaciones' => $request->observaciones]);
                }

                if (isset($request->campo_adicional)) {
                    foreach ($request->campo_adicional as $id_campo => $valor) {
                        Valor_campo_personalizado::create([
                            'id_reserva' => $reserva->id,
                            'id_campo' => $id_campo,
                            'valor' => $valor,
                        ]);
                    }
                }
            }
        } else {
            $reserva = Reserva::create([
                'id_pista' => $request->id_pista,
                'id_usuario' => $request->user_id,
                'timestamp' => $request->timestamp,
                'horarios' => serialize($timestamps),
                'fecha' => date('Y/m/d', $request->timestamp),
                'hora' => \Carbon\Carbon::createFromTimestamp($request->timestamp)->format('Hi'),
                'tarifa' => $request->tarifa,
                'minutos_totales' => $minutos_totales,
                'creado_por' => 'admin',
            ]);

            if (isset($request->observaciones)) {
                $reserva->update(['observaciones' => $request->observaciones]);
            }

            if (isset($request->campo_adicional)) {
                foreach ($request->campo_adicional as $id_campo => $valor) {
                    Valor_campo_personalizado::create([
                        'id_reserva' => $reserva->id,
                        'id_campo' => $id_campo,
                        'valor' => $valor,
                    ]);
                }
            }
        }

        if (isset($request->sendmail)) {
            Mail::to($reserva->user->email)->send(new ReservaAdmin($reserva->user, $reserva));
            Mail::to(auth()->user()->instalacion->user_admin->email)->send(new NewReserva(auth()->user(), $reserva));
        }

        $date = new DateTime(date('Y-m-d', $request->timestamp));
        $week = $date->format('Y') . '-' . 'W' . $date->format('W');

        return redirect($request->slug_instalacion . '/admin/reservas?week=' . $week)->with('dia_reserva_hecha', date('Y-m-d', $request->timestamp));
    }

    public function desactivar_tramo(Request $request)
    {
        $instalacion = auth()->user()->instalacion;

        Desactivacion_reserva::create([
            'id_pista' => $request->id_pista,
            'timestamp' => $request->timestamp,
        ]);
        Excepciones_desactivaciones_periodicas::where([['id_pista', $request->id_pista], ['timestamp', $request->timestamp]])->delete();

        return redirect()
            ->back()
            ->with('dia_reserva_hecha', date('Y-m-d', $request->timestamp));
    }

    public function activar_tramo(Request $request)
    {
        $instalacion = auth()->user()->instalacion;

        if (isset($request->periodic)) {
            Excepciones_desactivaciones_periodicas::create([
                'id_pista' => $request->id_pista,
                'timestamp' => $request->timestamp,
            ]);
        } else {
            Desactivacion_reserva::where([['id_pista', $request->id_pista], ['timestamp', $request->timestamp]])->delete();
        }

        return redirect()
            ->back()
            ->with('dia_reserva_hecha', date('Y-m-d', $request->timestamp));
    }

    public function desactivar_dia(Request $request)
    {
        $pista = Pista::find($request->id_pista);

        foreach ($pista->horario_con_reservas_por_dia_admin($request->dia) as $item) {
            foreach ($item as $valor) {
                Desactivacion_reserva::create([
                    'id_pista' => $request->id_pista,
                    'timestamp' => $valor['timestamp'],
                ]);
            }
        }

        return redirect()->back();
    }

    public function activar_dia(Request $request)
    {
        $pista = Pista::find($request->id_pista);

        foreach ($pista->horario_con_reservas_por_dia_admin($request->dia) as $item) {
            foreach ($item as $valor) {
                Desactivacion_reserva::where([['id_pista', $request->id_pista], ['timestamp', $valor['timestamp']]])->delete();
            }
        }

        return redirect()->back();
    }

    public function edit_reserva_view(Request $request)
    {
        $reserva = Reserva::find($request->id);
        $pistas = Pista::where('id_instalacion', auth()->user()->instalacion->id)->get();
        $pista = $reserva->pista;
        $fecha = $reserva->timestamp;
        foreach ($pista->horario_deserialized as $item) {
            if (in_array(date('w', $fecha), $item['dias']) || (date('w', $fecha) == 0 && in_array(7, $item['dias']))) {
                foreach ($item['intervalo'] as $index => $intervalo) {
                    $a = new \DateTime($intervalo['hfin']);
                    $b = new \DateTime($intervalo['hinicio']);
                    $interval = $a->diff($b);
                    $diff_minutes = $interval->format('%h') * 60;
                    $diff_minutes += $interval->format('%i');
                    $numero_veces = $diff_minutes / $intervalo['secuencia'];

                    $hora = new \DateTime($intervalo['hinicio']);
                    for ($i = 0; $i < floor($numero_veces); $i++) {
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

        return view('instalacion.reservas.edit', compact('reserva', 'number', 'fecha', 'secuencia', 'pistas'));
    }

    public function edit_reserva(Request $request)
    {
        $reserva = Reserva::find($request->id);
        $pista = $reserva->pista;

        $minutos_totales = $request->secuencia * $reserva->tarifa;

        $timestamps[0] = (int) $reserva->timestamp;

        if ($reserva->tarifa > 1) {
            for ($i = 1; $i < $reserva->tarifa; $i++) {
                $timestamps[$i] = \Carbon\Carbon::parse(date('d-m-Y H:i:s', $reserva->timestamp))->addMinutes($request->secuencia * $i)->timestamp;
            }
        }
        /* return $timestamps; */
        $reserva->update(['horarios' => serialize($timestamps), 'tarifa' => $reserva->tarifa, 'id_pista' => $request->id_pista, 'minutos_totales' => $minutos_totales]);

        if (isset($request->observaciones)) {
            $reserva->update(['observaciones' => $request->observaciones]);
        }

        if (isset($request->campo_adicional)) {
            Valor_campo_personalizado::where('id_reserva', $request->id)->delete();
            foreach ($request->campo_adicional as $id_campo => $valor) {
                Valor_campo_personalizado::create([
                    'id_reserva' => $reserva->id,
                    'id_campo' => $id_campo,
                    'valor' => $valor,
                ]);
            }
        }

        return redirect("/{$request->slug_instalacion}/admin/reservas/list");
    }

    public function listado_todas_reservas(Request $request)
    {
        $ids_pistas = Pista::where('id_instalacion', auth()->user()->instalacion->id)->pluck('id');

        if ($request->fecha_inicio && $request->fecha_fin) {
            $reservas = Reserva::whereIn('id_pista', $ids_pistas)->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
        } elseif ($request->fecha) {
            switch ($request->fecha) {
                case 'all':
                    $reservas = Reserva::whereIn('id_pista', $ids_pistas);
                    break;
                case 'today':
                    $reservas = Reserva::whereIn('id_pista', $ids_pistas)->where('fecha', date('Y-m-d'));
                    break;
                case 'week':
                    $week = $this->rangeWeek(date('Y-m-d'));
                    $reservas = Reserva::whereIn('id_pista', $ids_pistas)->where([['timestamp', '>=', strtotime($week['start'])], ['timestamp', '<=', strtotime($week['end'])]]);
                    break;
                case 'month':
                    $month_start = date('Y-m', strtotime(date('Y-m-d'))) . '-01';
                    $month_end = date('Y-m-t', strtotime(date('Y-m-d')));
                    $reservas = Reserva::whereIn('id_pista', $ids_pistas)->where([['timestamp', '>=', strtotime($month_start)], ['timestamp', '<=', strtotime($month_end)]]);
                    break;
                default:
                    break;
            }
        } else {
            $reservas = auth()->user()->instalacion->id == 2 ? Reserva::where('fecha', '>=', date('Y-m-d'))->whereIn('id_pista', $ids_pistas) : Reserva::where('reserva_periodica', null)->whereIn('id_pista', $ids_pistas)->where('fecha', date('Y-m-d'));
        }
        if ($request->periodicas) {
            if ($request->periodicas == 'periodicas') {
                $reservas = $reservas->where('reserva_periodica', '!=', null)->get();
            } else {
                abort(404);
            }
        } else {
            $reservas = $reservas->get();
        }
        /* return $request->fecha; */

        return view('instalacion.reservas.list', compact('reservas'));
    }

    public function reservas_periodicas(Request $request)
    {
        $reservas_periodicas = Reservas_periodicas::whereIn('id_pista', Pista::where('id_instalacion', auth()->user()->instalacion->id)->pluck('id'))->get();

        return view('instalacion.reservas.reservas_periodicas', compact('reservas_periodicas'));
    }

    public function add_reservas_periodicas_view(Request $request)
    {
        $reservas_periodicas = Reservas_periodicas::whereIn('id_pista', Pista::where('id_instalacion', auth()->user()->instalacion->id)->pluck('id'))->get();

        return view('instalacion.reservas.add_reserva_periodica', compact('reservas_periodicas'));
    }

    public function add_reservas_periodicas(Request $request)
    {
        $pista = Pista::find($request->espacio);

        $reserva_periodica = Reservas_periodicas::create([
            'id_pista' => $request->espacio,
            'id_user' => $request->user_id,
            'dias' => serialize($request->dias),
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        $period = new \DatePeriod(new DateTime($request->fecha_inicio), new \DateInterval('P1D'), new DateTime($request->fecha_fin));

        foreach ($period as $fecha) {
            if (in_array($fecha->format('w'), $request->dias)) {
                foreach ($pista->horario_tramos($fecha->format('Y-m-d')) as $horas) {
                    foreach ($horas as $hora) {
                        if (strtotime(date('H:i', $hora)) >= strtotime($request->hora_inicio) && strtotime(date('H:i', $hora)) < strtotime($request->hora_fin)) {
                            /* if (strtotime(date('Y-m-d', $hora)) >= strtotime('2022-03-27 00:00') && strtotime(date('Y-m-d', $hora)) <= strtotime('2022-10-30 00:00')) {
                                    $hora = $hora + 3600;
                               } */
                            $reserva = Reserva::create([
                                'id_pista' => $pista->id,
                                'id_usuario' => $request->user_id,
                                'timestamp' => $hora,
                                'horarios' => serialize([$hora]),
                                'fecha' => date('Y/m/d', $hora),
                                'hora' => date('Hi', $hora),
                                'tarifa' => 1,
                                'minutos_totales' => $pista->get_minutos_given_timestamp($hora),
                                'reserva_periodica' => $reserva_periodica->id,
                                'creado_por' => 'admin',
                            ]);

                            if (isset($request->campo_adicional)) {
                                foreach ($request->campo_adicional as $id_campo => $valor) {
                                    Valor_campo_personalizado::create([
                                        'id_reserva' => $reserva->id,
                                        'id_campo' => $id_campo,
                                        'valor' => $valor,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        return redirect('/' . request()->slug_instalacion . '/admin/reservas/periodicas');
    }

    /* public function arreglos_reservas()
    {
        $reservas = Reserva::where([['timestamp', '>=', strtotime('2022-03-27 00:00')], ['timestamp', '<=', strtotime('2022-10-30 00:00')]], ['creado_por', 'admin'], ['reserva_periodica', '!=', null])->get();

        foreach ($reservas as $reserva) {
            $res = Reserva::find($reserva->id);

            $hora = $res->timestamp - 3600;

            $res->timestamp = $hora;
            $res->horarios = serialize([$hora]);
            $res->hora = date('Hi', $hora);
            $res->minutos_totales = 60;

            $res->save();
        }

        return Reserva::where([['timestamp', '>=', strtotime('2022-03-27 00:00')], ['timestamp', '<=', strtotime('2022-10-30 00:00')]], ['creado_por', 'admin'], ['reserva_periodica', '!=', null])->get();
    } */

    public function editar_reservas_periodicas_view(Request $request)
    {
        $reserva_periodica = Reservas_periodicas::find($request->id);

        return view('instalacion.reservas.edit_reserva_periodica', compact('reserva_periodica'));
    }

    public function update_reserva_periodica(Request $request)
    {
        $reserva_periodica = Reservas_periodicas::find($request->id);
        $reserva_periodica->id_pista = $request->espacio;
        $reserva_periodica->id_user = $request->user_id;
        $pista = Pista::find($reserva_periodica->id_pista);

        $reserva_periodica->update([
            'dias' => serialize($request->dias),
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        $period = new \DatePeriod(new DateTime($request->fecha_inicio), new \DateInterval('P1D'), new DateTime($request->fecha_fin));

        Reserva::where('reserva_periodica', $request->id)->delete();

        foreach ($period as $fecha) {
            if (in_array($fecha->format('w'), $request->dias)) {
                foreach ($pista->horario_tramos($fecha->format('Y-m-d')) as $horas) {
                    foreach ($horas as $hora) {
                        if (strtotime(date('H:i', $hora)) >= strtotime($request->hora_inicio) && strtotime(date('H:i', $hora)) < strtotime($request->hora_fin)) {
                            $reserva = Reserva::create([
                                'id_pista' => $pista->id,
                                'id_usuario' => $reserva_periodica->id_user,
                                'timestamp' => $hora,
                                'horarios' => serialize([$hora]),
                                'fecha' => date('Y/m/d', $hora),
                                'hora' => date('Hi', $hora),
                                'tarifa' => 1,
                                'minutos_totales' => $pista->get_minutos_given_timestamp($hora),
                                'reserva_periodica' => $reserva_periodica->id,
                                'creado_por' => 'admin',
                            ]);

                            if (isset($request->campo_adicional)) {
                                foreach ($request->campo_adicional as $id_campo => $valor) {
                                    Valor_campo_personalizado::create([
                                        'id_reserva' => $reserva->id,
                                        'id_campo' => $id_campo,
                                        'valor' => $valor,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        return redirect('/' . request()->slug_instalacion . '/admin/reservas/periodicas');
    }

    public function borrar_reservas_periodicas(Request $request)
    {
        Reservas_periodicas::find($request->id)->delete();

        Reserva::where([['reserva_periodica', $request->id], ['fecha', '>=', date('Y-m-d')]])->delete();

        return redirect('/' . request()->slug_instalacion . '/admin/reservas/periodicas');
    }

    public function desactivaciones_periodicas(Request $request)
    {
        $desactivaciones = Desactivaciones_periodicas::whereIn('id_pista', Pista::where('id_instalacion', auth()->user()->instalacion->id)->pluck('id'))->get();

        return view('instalacion.reservas.desactivaciones', compact('desactivaciones'));
    }

    public function add_desactivaciones_periodicas_view(Request $request)
    {
        $desactivaciones = Desactivaciones_periodicas::whereIn('id_pista', Pista::where('id_instalacion', auth()->user()->instalacion->id)->pluck('id'))->get();

        return view('instalacion.reservas.add_desactivacion', compact('desactivaciones'));
    }

    public function add_desactivaciones_periodicas(Request $request)
    {
        Desactivaciones_periodicas::create([
            'id_pista' => $request->espacio,
            'dias' => serialize($request->dias),
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        return redirect('/' . request()->slug_instalacion . '/admin/reservas/desactivaciones');
    }

    public function borrar_desactivaciones_periodicas(Request $request)
    {
        Desactivaciones_periodicas::find($request->id)->delete();

        return back();
    }

    public function edit_info(Request $request)
    {
        $instalacion = auth()->user()->instalacion->toArray();

        return view('instalacion.editdata.edit', compact('instalacion'));
    }

    public function editar_info(Request $request)
    {
        $instalacion = auth()->user()->instalacion;

        $data = $request->all();
        array_shift($data);

        if ($request->logo) {
            $image = $request->file('logo');
            $img = Image::make($image->getRealPath());
            $img->orientate();
            $path = public_path() . '/img';

            $name = $instalacion->slug . '.png';

            if (getimagesize($image)[0] > 1000) {
                $img->resize(900, 900, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path . '/' . $name, 85, 'png');
            } else {
                $img->save($path . '/' . $name, 85, 'png');
            }
        } else {
            Instalacion::find($instalacion->id)->update($data);
        }

        return redirect()->route('edit_config_inst', ['slug_instalacion' => $instalacion->slug]);
    }

    public function pistas()
    {
        $instalacion = auth()->user()->instalacion;
        $pistas = $instalacion->pistas;
        return view('instalacion.pistas.list', compact('instalacion', 'pistas'));
    }

    public function add_pista_view()
    {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.pistas.add', compact('instalacion'));
    }

    public function add_pista(Request $request)
    {
        $instalacion = auth()->user()->instalacion;

        $data = $request->all();
        $data['id_instalacion'] = $instalacion->id;

        $horario = $request->horario;
        foreach ($horario as $indexhor => $item) {
            foreach ($item['intervalo'] as $indexinterval => $intervalo) {
                $a = new \DateTime($intervalo['hfin']);
                $b = new \DateTime($intervalo['hinicio']);
                $interval = $a->diff($b);
                $diff_minutes = $interval->format('%h') * 60;
                $diff_minutes += $interval->format('%i');

                $intervalo['secuencia'] = $intervalo['secuencia'] == 'completo' ? $diff_minutes : $intervalo['secuencia'];

                $numero_veces = $diff_minutes / $intervalo['secuencia'];

                if (!is_int($diff_minutes / $intervalo['secuencia'])) {
                    $hora = new \DateTime($intervalo['hinicio']);
                    for ($i = 0; $i < floor($numero_veces); $i++) {
                        $hora->modify("+{$intervalo['secuencia']} minutes");
                    }

                    $horario[$indexhor]['intervalo'][$indexinterval]['hfin'] = $hora->format('H:i');
                }

                //si es intervalo completo
                $horario[$indexhor]['intervalo'][$indexinterval]['secuencia'] = $intervalo['secuencia'] == 'completo' ? $diff_minutes : $intervalo['secuencia'];
            }
        }

        $data['horario'] = serialize($horario);

        $pista = Pista::create($data);

        return redirect('/' . auth()->user()->instalacion->slug . '/admin/pistas');
    }

    public function edit_pista_view(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        $pista = Pista::find($request->id);
        return view('instalacion.pistas.edit', compact('instalacion', 'pista'));
    }

    public function edit_pista(Request $request)
    {
        $data = $request->all();
        array_shift($data);

        $horario = $request->horario;
        foreach ($horario as $indexhor => $item) {
            foreach ($item['intervalo'] as $indexinterval => $intervalo) {
                $a = new \DateTime($intervalo['hfin']);
                $b = new \DateTime($intervalo['hinicio']);
                $interval = $a->diff($b);
                $diff_minutes = $interval->format('%h') * 60;
                $diff_minutes += $interval->format('%i');

                $intervalo['secuencia'] = $intervalo['secuencia'] == 'completo' ? $diff_minutes : $intervalo['secuencia'];

                $numero_veces = $diff_minutes / $intervalo['secuencia'];

                if (!is_int($diff_minutes / $intervalo['secuencia'])) {
                    $hora = new \DateTime($intervalo['hinicio']);
                    for ($i = 0; $i < floor($numero_veces); $i++) {
                        $hora->modify("+{$intervalo['secuencia']} minutes");
                    }

                    $horario[$indexhor]['intervalo'][$indexinterval]['hfin'] = $hora->format('H:i');
                }

                //si es intervalo completo
                $horario[$indexhor]['intervalo'][$indexinterval]['secuencia'] = $intervalo['secuencia'] == 'completo' ? $diff_minutes : $intervalo['secuencia'];
            }
        }

        $data['horario'] = serialize($horario);
        /* return $data; */
        Pista::where('id', $request->id)->update($data);

        return redirect('/' . auth()->user()->instalacion->slug . '/admin/pistas');
    }

    public function configuracion_pistas_reservas(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.configuraciones.pistas_reservas', compact('instalacion'));
    }

    public function configuracion_instalacion(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.configuraciones.instalacion', compact('instalacion'));
    }

    public function edit_configuracion(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        $data = $request->all();
        array_shift($data);

        if (!isset($request->allow_cancel)) {
            $data['allow_cancel'] = 0;
        }
        if (!isset($request->block_today)) {
            $data['block_today'] = 0;
        }
        if (!isset($request->observaciones)) {
            $data['observaciones'] = 0;
        }
        if (isset($request->max_reservas_tipo_espacio)) {
            $data['max_reservas_tipo_espacio'] = serialize($request->max_reservas_tipo_espacio);
        }

        Configuracion::find($instalacion->configuracion->id)->update($data);

        // Actualizar el tipo de calendario en la tabla instalaciones
        if (isset($request->tipo_calendario)) {
            $instalacion->update(['tipo_calendario' => $request->tipo_calendario]);
        }

        return redirect()->back();
    }

    public function campos_adicionales(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.configuraciones.campos_adicionales', compact('instalacion'));
    }

    public function view_campos_personalizados(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.configuraciones.campos_personalizados', compact('instalacion'));
    }

    public function add_campos_personalizados(Request $request)
    {
        if (!isset($request->required_field)) {
            $request->required_field = 0;
        }
        if (!isset($request->opcion)) {
            $opciones = null;
        } else {
            $opciones = serialize($request->opcion);
        }

        $campo = Campos_personalizados::create([
            'id_instalacion' => auth()->user()->instalacion->id,
            'tipo' => $request->tipo,
            'label' => $request->label,
            'opciones' => $opciones,
            'required' => $request->required_field,
        ]);

        if (is_array($request->pistas)) {
            foreach ($request->pistas as $id_pista) {
                DB::table('pistas_campos')->insert([
                    'id_pista' => $id_pista,
                    'id_campo' => $campo->id,
                ]);
            }
        } else {
            $campo->update(['all_pistas' => 1]);
        }

        return redirect('/' . auth()->user()->instalacion->slug . '/admin/campos-adicionales');
    }

    public function view_edit_campos_personalizados(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        $campo = Campos_personalizados::find($request->id);
        return view('instalacion.configuraciones.edit_campos_personalizados', compact('instalacion', 'campo'));
    }

    public function edit_campos_personalizados(Request $request)
    {
        if (!isset($request->required_field)) {
            $request->required_field = 0;
        }
        if (!isset($request->opciones)) {
            $opciones = null;
        } else {
            $opciones = serialize($request->opciones);
        }

        $campo = Campos_personalizados::find($request->id);

        $campo->update([
            'id_instalacion' => auth()->user()->instalacion->id,
            'tipo' => $request->tipo,
            'label' => $request->label,
            'opciones' => $opciones,
            'required' => $request->required_field,
        ]);

        DB::table('pistas_campos')->where('id_campo', $campo->id)->delete();

        if (is_array($request->pistas)) {
            foreach ($request->pistas as $id_pista) {
                DB::table('pistas_campos')->insert([
                    'id_pista' => $id_pista,
                    'id_campo' => $campo->id,
                ]);
            }
        } else {
            $campo->update(['all_pistas' => 1]);
        }

        return redirect('/' . auth()->user()->instalacion->slug . '/admin/campos-adicionales');
    }

    public function delete_campos_personalizados(Request $request)
    {
        Campos_personalizados::find($request->id)->delete();
        Pistas_campos_relation::where('id_campo', $request->id)->delete();

        return redirect('/' . auth()->user()->instalacion->slug . '/admin/campos-adicionales');
    }

    public function users()
    {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.users.list', compact('instalacion'));
    }

    public function users_no_valid()
    {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.users.list_no_valid', compact('instalacion'));
    }

    public function validar_user(Request $request)
    {
        $user = User::find($request->id);
        $user->update(['aprobado' => date('Y-m-d H:i:s')]);

        return redirect()->back();
    }

    public function borrar_permanente_user(Request $request)
    {
        DB::table('users')->where('id', $request->id)->delete();

        return redirect()->back();
    }

    public function add_user_view(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.users.add', compact('instalacion'));
    }

    public function add_user(Request $request)
    {
        $data = $request->all();

        if (User::where([['email', $request->email], ['id_instalacion', auth()->user()->instalacion->id]])->first()) {
            return redirect()->back()->with('error', 'Ya existe un usuario con ese email. Prueba otro email.');
        }

        $data['password'] = \Hash::make($request->password);
        $data['aprobado'] = date('Y-m-d H:i:s');

        User::where('id', $request->id)->create($data);

        return redirect('/' . auth()->user()->instalacion->slug . '/admin/users');
    }

    public function desactivar_user(Request $request)
    {
        $user = User::withTrashed()->find($request->id);

        if (!$user->deleted_at) {
            $user->delete();
        } else {
            $user->restore();
        }
        return redirect()->back();
    }

    public function edit_user_view(Request $request)
    {
        $instalacion = auth()->user()->instalacion;
        $user = User::find($request->id);

        return view('instalacion.users.edit', compact('instalacion', 'user'));
    }

    public function editar_user(Request $request)
    {
        $data = $request->all();
        $user = User::find($request->id);

        array_shift($data);

        if (isset($request->email)) {
            $emails = User::withTrashed()
                ->where([['email', $request->email], ['id_instalacion', auth()->user()->instalacion->id]])
                ->get();
            foreach ($emails as $value) {
                if ($value->id != $user->id) {
                    if ($value->deleted_at) {
                        return redirect()->back()->with('error', 'Un usuario desactivado está usando ese mail. Reactiva ese usuario o elige otro email.');
                    }
                    return redirect()->back()->with('error', 'No se puede usar ese email porque ya está siendo usado por otro usuario. Por favor, elige otro email.');
                }
            }
        }

        if (isset($request->max_reservas_tipo_espacio)) {
            $max_reservas_tipo_espacio = $request->max_reservas_tipo_espacio;

            foreach ($max_reservas_tipo_espacio as $tipo => $value) {
                if (isset(unserialize($user->instalacion->configuracion->max_reservas_tipo_espacio)[$tipo]) && unserialize($user->instalacion->configuracion->max_reservas_tipo_espacio)[$tipo] == $value) {
                    unset($max_reservas_tipo_espacio[$tipo]);
                }
            }

            $data['max_reservas_tipo_espacio'] = $max_reservas_tipo_espacio ? serialize($max_reservas_tipo_espacio) : null;
        }

        if (!isset($request->password)) {
            unset($data['password']);
            $user->update($data);
        } else {
            $data['password'] = \Hash::make($request->password);
            $user->update($data);
        }
        return redirect('/' . auth()->user()->instalacion->slug . '/admin/users');
    }

    public function cambiar_foto_user(Request $request)
    {
        $user = User::find($request->id);

        return view('instalacion.users.change_photo', compact('user'));
    }

    public function ver_user(Request $request)
    {
        $user = User::withTrashed()->find($request->id);

        return view('instalacion.users.ver', compact('user'));
    }

    public function update_max_reservas_user(Request $request)
    {
        $user = User::find($request->id);

        $max_reservas_tipo_espacio = $request->max_reservas_tipo_espacio;

        foreach ($max_reservas_tipo_espacio as $tipo => $value) {
            if (isset(unserialize($user->instalacion->configuracion->max_reservas_tipo_espacio)[$tipo]) && unserialize($user->instalacion->configuracion->max_reservas_tipo_espacio)[$tipo] == $value) {
                unset($max_reservas_tipo_espacio[$tipo]);
            }
        }

        $max_reservas_tipo_espacio = $max_reservas_tipo_espacio ? serialize($max_reservas_tipo_espacio) : null;

        $user->update(['max_reservas_tipo_espacio' => $max_reservas_tipo_espacio]);

        return back();
    }

    public function user_add_cobro_view(Request $request)
    {
        $user = User::find($request->id);

        return view('instalacion.users.add_cobro', compact('user'));
    }

    public function user_add_cobro(Request $request)
    {
        $user = User::find($request->id);
        $data = $request->all();
        $data['id_user'] = $request->id;

        Cobro::create($data);

        return redirect("/{$request->slug_instalacion}/admin/users/{$request->id}/ver");
    }

    public function list_cobros(Request $request)
    {
        $instalacion = auth()->user()->instalacion;

        return view('instalacion.cobros.list', compact('instalacion'));
    }

    public function add_cobro_view(Request $request)
    {
        $instalacion = auth()->user()->instalacion;

        return view('instalacion.cobros.add');
    }

    public function add_cobro(Request $request)
    {
        $data = $request->all();

        Cobro::create($data);

        return redirect("/{$request->slug_instalacion}/admin/cobro");
    }

    public function edit_cobro_view(Request $request)
    {
        $cobro = Cobro::find($request->id);

        return view('instalacion.cobros.edit', compact('cobro'));
    }

    public function edit_cobro(Request $request)
    {
        $cobro = Cobro::find($request->id);

        $data = $request->all();
        array_shift($data);

        $cobro->update($data);

        return redirect("/{$request->slug_instalacion}/admin/users/{$cobro->user->id}/ver");
    }

    public function delete_cobro(Request $request)
    {
        Cobro::find($request->id)->delete();

        return redirect()->back();
    }

    function csvToArray($filename = '', $delimiter = ';')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $header = null;
        $data = [];
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }

    /* public function import_users(Request $request)
    {
        $users = $this->csvToArray('export-users.csv');


        foreach ($users as $key => $value) {
            User::create(['name' => str_replace('"', '', str_replace("'", '', str_replace('\"', '', $value["'display_name'"]))),
                          'id_instalacion' => 7,
                          'email' => str_replace("'", '', $value["'user_email'"]),
                          'password' => \Hash::make('adfasdfasd433wsd'),
                          'rol'=> 'user',
                          'aprobado' => date('Y-m-d H:i:s')]);
        }

        return true;
    } */
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewReserva;
use App\Mail\ReservaAdmin;
use App\Models\Pista;
use App\Models\Instalacion;
use App\Models\User;
use App\Models\Configuracion;
use App\Models\Reserva;
use App\Models\Desactivacion_reserva;
use App\Models\Campos_personalizados;
use App\Models\Pistas_campos_relation;
use App\Models\Valor_campo_personalizado;
use App\Models\Desactivaciones_periodicas;
use App\Models\Reservas_periodicas;
use Intervention\Image\ImageManagerStatic as Image;
use DateTime;

class InstalacionController extends Controller
{
    public function rangeWeek ($date) {
        $dt = strtotime ($date);
        return array (
          "start" => date ('N', $dt) == 1 ? date ('Y-m-d', $dt) : date ('Y-m-d', strtotime ('last monday', $dt)),
          "end" => date('N', $dt) == 7 ? date ('Y-m-d', $dt) : date ('Y-m-d', strtotime ('next monday', $dt))
        );
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
      
    public function index(Request $request) {
        $instalacion = auth()->user()->instalacion;

        if (isset($request->semana)) {
            $semana = $this->rangeWeek(date("Y-m-d", strtotime(date('Y-m-d')."+{$request->semana} weeks")));
        }else{
            $semana = $this->rangeWeek(date('Y-m-d'));
        }
        
        if (isset($request->week)) {
            $semana = $request->week;
            $d = (int)substr($semana, 6) * 7;
            $date = DateTime::createFromFormat('z Y', $d . ' ' . substr($semana, 0, 4));

            $semana = $this->rangeWeek($date->format('Y-m-d'));
        }

        $period = new \DatePeriod(new DateTime($semana['start']), new \DateInterval('P1D'), new DateTime($semana['end']));
        $pistas = Pista::where('id_instalacion', auth()->user()->instalacion->id)->get();


        return view('instalacion.home', compact('instalacion', 'period', 'pistas'));
    }

    public function reservas_dia(Request $request)
    {
        $pistas = Pista::where('id_instalacion', auth()->user()->instalacion->id)->get();
        $ret_pistas = [];
        foreach ($pistas as $i => $pista){
            $ret_pistas[$i] = $pista;
            $ret_pistas[$i]['num_reservas_dia'] = count($pista->reservas_activas_por_dia($request->fecha));
            $ret_pistas[$i]['res_dia'] = $pista->horario_con_reservas_por_dia_admin($request->fecha);
        }

        return $ret_pistas;
    }

    public function validar_reserva(Request $request)
    {
        $reserva = Reserva::find($request->id);
        if ($reserva->estado == 'active') {
            $reserva->update((['estado' =>$request->accion, 'observaciones_admin' => $request->observaciones]));
            return redirect()->back();
        }

        return redirect()->back()->with('error', 'true');
    }

    public function hacer_reserva_view(Request $request)
    {
        $reserva = Reserva::where([['id_pista', $request->id_pista], ['timestamp', $request->timestamp], ['estado', 'active']])->first();
        
        $pista = Pista::find($request->id_pista);
        $fecha = $request->timestamp;
        
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

        $timestamps[0] = (int)$request->timestamp;
        
        if ($request->tarifa > 1) {
            for ($i=1; $i < $request->tarifa; $i++) {
                $timestamps[$i] = \Carbon\Carbon::parse(date('d-m-Y H:i:s', $request->timestamp))->addMinutes($request->secuencia*$i)->timestamp;
            }
        }
        
        $reserva = Reserva::create([
            'id_pista' => $request->id_pista,
            'id_usuario' => $request->user_id,
            'timestamp' => $request->timestamp,
            'horarios' => serialize($timestamps),
            'fecha' => date('Y/m/d', $request->timestamp),
            'hora' => date('Hi', $request->timestamp),
            'tarifa' => $request->tarifa,
            'minutos_totales' => $minutos_totales,
            'creado_por' => 'admin'
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

        /* Mail::to($reserva->user->email)->send(new ReservaAdmin($reserva->user, $reserva));
        Mail::to(auth()->user()->instalacion->user_admin->email)->send(new NewReserva(auth()->user(), $reserva)); */

        return redirect($request->slug_instalacion . '/admin');
    }

    public function desactivar_tramo(Request $request) {
        $instalacion = auth()->user()->instalacion;

        Desactivacion_reserva::create([
            'id_pista' => $request->id_pista,
            'timestamp' => $request->timestamp
        ]);

        return redirect($request->slug_instalacion . '/admin');
    }

    public function activar_tramo(Request $request) {
        $instalacion = auth()->user()->instalacion;

        Desactivacion_reserva::where([['id_pista', $request->id_pista],['timestamp', $request->timestamp]])->delete();

        return redirect($request->slug_instalacion . '/admin');
    }

    public function desactivar_dia(Request $request) {
        $pista = Pista::find($request->id_pista);

        foreach ($pista->horario_con_reservas_por_dia_admin($request->dia) as $item) {
            foreach ($item as $valor) {
                Desactivacion_reserva::create([
                    'id_pista' => $request->id_pista,
                    'timestamp' => $valor['timestamp']
                ]);
            }
        }

        return redirect($request->slug_instalacion . '/admin');
    }

    public function activar_dia(Request $request) {
        $pista = Pista::find($request->id_pista);

        foreach ($pista->horario_con_reservas_por_dia_admin($request->dia) as $item) {
            foreach ($item as $valor) {
                Desactivacion_reserva::where([['id_pista', $request->id_pista],['timestamp', $valor['timestamp']]])->delete();
            }
        }

        return redirect($request->slug_instalacion . '/admin');
    }

    public function listado_todas_reservas(Request $request)
    {
        $reservas = Reserva::whereIn('id_pista', Pista::where('id_instalacion', auth()->user()->instalacion->id)->pluck('id'))->get();

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
                        if (
                            strtotime(date('H:i', $hora)) >= strtotime($request->hora_inicio) && 
                            strtotime(date('H:i', $hora)) < strtotime($request->hora_fin)
                           ) {
                            Reserva::create([
                                'id_pista' => $pista->id,
                                'id_usuario' => $request->user_id,
                                'timestamp' => $hora,
                                'horarios' => serialize([$hora]),
                                'fecha' => date('Y/m/d', $hora),
                                'hora' => date('Hi', $hora),
                                'tarifa' => 1,
                                'minutos_totales' => $pista->get_minutos_given_timestamp($hora),
                                'reserva_periodica' => $reserva_periodica->id,
                                'creado_por' => 'admin'
                            ]);
                        }
                    }
                }
            }
        }

        return redirect('/'.request()->slug_instalacion.'/admin/reservas/periodicas');
    }

    public function borrar_reservas_periodicas(Request $request)
    {
        Reservas_periodicas::find($request->id)->delete();

        Reserva::where('reserva_periodica', $request->id)->delete();
        
        return redirect('/'.request()->slug_instalacion.'/admin/reservas/periodicas');
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

        return redirect('/'.request()->slug_instalacion.'/admin/reservas/desactivaciones');
    }

    public function edit_info(Request $request) {
        $instalacion = auth()->user()->instalacion->toArray();

        return view('instalacion.editdata.edit', compact('instalacion'));
    }

    public function editar_info(Request $request) {
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
                })->save($path .'/'. $name, 85, 'png');
            }else{
                $img->save($path .'/'. $name, 85, 'png');
            }

        } else{
            Instalacion::find($instalacion->id)->update($data);
        }
        
        return redirect()->route('edit_config_inst', ['slug_instalacion'=> $instalacion->slug]);
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

        $pista = Pista::create($data);

        if (isset($request->desactivaciones)) {
            foreach ($request->desactivaciones as $item) {
                Desactivaciones_periodicas::create([
                    'id_pista' => $pista->id,
                    'dias' => serialize($item['dias']),
                    'hora_inicio' => $item['hora_inicio'],
                    'hora_fin' => $item['hora_fin']
                ]);
            }
        }

        return redirect("/" . auth()->user()->instalacion->slug . "/admin/pistas");
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
        
        return redirect('/' . auth()->user()->instalacion->slug . '/admin/pistas');
    }

    public function configuracion_pistas_reservas(Request $request) {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.configuraciones.pistas_reservas', compact('instalacion'));
    }

    public function configuracion_instalacion(Request $request) {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.configuraciones.instalacion', compact('instalacion'));
    }

    public function edit_configuracion(Request $request) {
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
        return redirect()->back();
    }

    public function campos_adicionales(Request $request) {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.configuraciones.campos_adicionales', compact('instalacion'));
    }

    public function view_campos_personalizados(Request $request) {
        $instalacion = auth()->user()->instalacion;
        return view('instalacion.configuraciones.campos_personalizados', compact('instalacion'));
    }

    public function add_campos_personalizados(Request $request) {
        if (!isset($request->required_field)) {
            $request->required_field = 0;
        }
        if (!isset($request->opcion)) {
            $opciones = null;
        }else{
            $opciones = serialize($request->opcion);
        }

        $campo = Campos_personalizados::create([
            'id_instalacion' => auth()->user()->instalacion->id,
            'tipo' => $request->tipo,
            'label' => $request->label,
            'opciones' => $opciones,
            'required' => $request->required_field
        ]);
        
        if (is_array($request->pistas)) {
            foreach ($request->pistas as $id_pista) {
                DB::table('pistas_campos')->insert([
                    'id_pista' => $id_pista,
                    'id_campo' => $campo->id
                ]);
            }
        }else{
            $campo->update(['all_pistas' => 1]);
        }

        return redirect('/' . auth()->user()->instalacion->slug . '/admin/campos-adicionales');
    }

    public function view_edit_campos_personalizados(Request $request) {
        $instalacion = auth()->user()->instalacion;
        $campo = Campos_personalizados::find($request->id);
        return view('instalacion.configuraciones.edit_campos_personalizados', compact('instalacion','campo'));
    }

    public function edit_campos_personalizados(Request $request) {
        if (!isset($request->required_field)) {
            $request->required_field = 0;
        }
        if (!isset($request->opciones)) {
            $opciones = null;
        }else{
            $opciones = serialize($request->opciones);
        }

        $campo = Campos_personalizados::find($request->id);

        $campo->update([
            'id_instalacion' => auth()->user()->instalacion->id,
            'tipo' => $request->tipo,
            'label' => $request->label,
            'opciones' => $opciones,
            'required' => $request->required_field
        ]);

        DB::table('pistas_campos')->where('id_campo', $campo->id)->delete();
        
        if (is_array($request->pistas)) {
            foreach ($request->pistas as $id_pista) {
                DB::table('pistas_campos')->insert([
                    'id_pista' => $id_pista,
                    'id_campo' => $campo->id
                ]);
            }
        }else{
            $campo->update(['all_pistas' => 1]);
        }

        return redirect('/' . auth()->user()->instalacion->slug . '/admin/campos-adicionales');
    }

    public function delete_campos_personalizados(Request $request) {
        Campos_personalizados::find($request->id)->delete();
        Pistas_campos_relation::where('id_campo', $request->id)->delete();

        return redirect('/' . auth()->user()->instalacion->slug . '/admin/campos-adicionales');
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
        $data = $request->all();
        $data['password'] = \Hash::make($request->password);
        $data['aprobado'] = date('Y-m-d H:i:s');
        
        User::where('id', $request->id)->create($data);

        return redirect("/". auth()->user()->instalacion->slug . "/admin/users");
    }

    public function desactivar_user(Request $request)
    {
        $user = User::withTrashed()->find($request->id);

        if (!$user->deleted_at) {
            $user->delete();
        } else{
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

        array_shift($data);

        if (isset($request->max_reservas_tipo_espacio)) {
            $data['max_reservas_tipo_espacio'] = serialize($request->max_reservas_tipo_espacio);
        }

        if (!isset($request->password)) {
            unset($data['password']);
            User::where('id', $request->id)->update($data);
        }else {
            $data['password'] = \Hash::make($request->password);
            User::where('id', $request->id)->update($data);
        }
        return redirect("/". auth()->user()->instalacion->slug . "/admin/users");
    }

    public function ver_user(Request $request)
    {
        $user = User::find($request->id);

        return view('instalacion.users.ver', compact('user'));
    }
}

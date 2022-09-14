<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Reserva;
use App\Models\Instalacion;
use App\Models\Desactivacion_reserva;
use App\Models\Campos_personalizados;
use App\Models\Desactivaciones_periodicas;
use App\Models\Excepciones_desactivaciones_periodicas;

class Pista extends Model
{
    use HasFactory;

    protected $table = 'pistas';

    protected $fillable = [
        'id_instalacion',
        'nombre',
        'nombre_corto',
        'tipo',
        'horario',
        'allow_cancel',
        'atenlacion_reserva',
        'antelacion_cancelacion',
        'allow_more_res',
        'reservas_por_tramo',
        'max_dias_antelacion',
        'active',
    ];

    public function instalacion()
    {
        return $this->hasOne(Instalacion::class, 'id', 'id_instalacion');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id', 'id_pista');
    }

    public function desactivaciones_periodicas()
    {
        return $this->hasMany(Desactivaciones_periodicas::class, 'id_pista', 'id');
    }

    public function campos_personalizados()
    {
        return $this->belongsToMany(Campos_personalizados::class, 'pistas_campos', 'id_pista', 'id_campo');
    }

    public function getAllCamposPersonalizadosAttribute()
    {
        return $this->allCamposPersonalizados();
    }

    public function allCamposPersonalizados() {
        
        $campos_personalizados = $this->campos_personalizados;
        foreach (Campos_personalizados::where('id_instalacion', $this->id_instalacion)->where('all_pistas', 1)->get() as $key => $value) {
            $campos_personalizados->push($value);
        }

        return $campos_personalizados;
    }

    public function getHorarioDeserializedAttribute() {
        return $this->horarioDeserializado();
    }

    public function horarioDeserializado() {
        $horario = unserialize($this->horario);
        return $horario;
    }

    public function reservas_por_dia($fecha)
    {
        return Reserva::where('id_pista', $this->id)->where('fecha', $fecha)->get();
    }

    public function reservas_activas_por_dia($fecha)
    {
        return Reserva::where('id_pista', $this->id)->where('estado', 'active')->where('fecha', $fecha)->get();
    }

    public function get_reservas_fecha_hora($timestamp)
    {
        $reservas = Reserva::with('user')->where([['id_pista', $this->id], ['fecha', date('Y-m-d', $timestamp)]])->orderByRaw("FIELD (estado, 'active', 'pasado', 'canceled') ASC")->get()->filter(function($reserva) use ($timestamp) {
            return in_array($timestamp, $reserva->horarios_deserialized);
        });

        $jump = 0;
        $ret_reservas = [];
        foreach ($reservas as $key => $reserva) {
            /* if (in_array($timestamp, $reserva->horarios_deserialized)) { */
                if ($jump) {
                    $jump=$jump-1;
                    continue;
                }
                $reserva->valores_campos_pers = $reserva->valores_campos_pers;
                /* $reserva->usuario = User::find($reserva->id_usuario); */
                if ($reserva->reserva_multiple) {
                    $reserva->numero_reservas = Reserva::where([['id_pista', $reserva->id_pista], ['reserva_multiple', $reserva->reserva_multiple], ['timestamp', $reserva->timestamp], ['estado', $reserva->estado], ['id_usuario', $reserva->id_usuario]])->count();
                    $jump = Reserva::where([['id_pista', $reserva->id_pista], ['reserva_multiple', $reserva->reserva_multiple], ['timestamp', $reserva->timestamp], ['estado', $reserva->estado], ['id_usuario', $reserva->id_usuario]])->count()-1;
                }
                $reserva->string_intervalo = date('H:i', $timestamp) . ' - ' . date('H:i', strtotime("+{$reserva->minutos_totales} minutes", strtotime(date('H:i', $timestamp))));
                array_push($ret_reservas, $reserva);
            /* } */
        }

        return $ret_reservas;
    }

    public function get_reserva_activa_fecha_hora($timestamp)
    {
        $reservas = Reserva::where([['id_pista', $this->id], ['fecha', date('Y-m-d', $timestamp)]])->orderByRaw('estado ASC')->get()->filter(function($reserva) use ($timestamp) {
            return in_array($timestamp, $reserva->horarios_deserialized) && $reserva->estado != 'canceled';
        });
        return $reservas;

        /* $reservas = Reserva::where([['id_pista', $this->id], ['fecha', date('Y-m-d', $timestamp)]])->orderByRaw('estado ASC')->get();

        $ret_reservas = [];
        foreach ($reservas as $key => $reserva) {
            if (in_array($timestamp, $reserva->horarios_deserialized) && $reserva->estado != 'canceled') {
                $reserva->usuario = User::find($reserva->id_usuario);
                array_push($ret_reservas, $reserva);
            }
        }

        return $ret_reservas; */
    }

    public function check_desactivacion_periodica($fecha)
    {
        $desactivaciones_dia =[];
        foreach ($this->desactivaciones_periodicas as $desactivacion) {
            if (in_array(date('w', strtotime($fecha)), unserialize($desactivacion->dias)) && 
                $fecha > $desactivacion->fecha_inicio && 
                $fecha < $desactivacion->fecha_fin
               ) {
                array_push($desactivaciones_dia, $desactivacion);
            }
        }
        return count($desactivaciones_dia) != 0 ? $desactivaciones_dia : false;
    }

    public function check_desactivado($timestamp)
    {
        if (Desactivacion_reserva::where([['id_pista', $this->id], ['timestamp', $timestamp]])->first()) {
            return true;
        }
        $desactivaciones_periodicas_dia = $this->check_desactivacion_periodica(date('Y-m-d', $timestamp));
        if ($desactivaciones_periodicas_dia) {
            foreach ($desactivaciones_periodicas_dia as $desactivacion) {
                /* if ((strtotime(date('Y-m-d', $timestamp)) >= strtotime('2022-03-27 00:00') && strtotime(date('Y-m-d', $timestamp)) <= strtotime('2022-10-30 00:00'))) {
                    if (
                        strtotime(date('H:i', $timestamp)) -3600 >= strtotime($desactivacion->hora_inicio) && 
                        strtotime(date('H:i', $timestamp)) -3600 < strtotime($desactivacion->hora_fin)
                    ) {
                        return true;
                    }
                }else { */
                    if (
                        strtotime(date('H:i', $timestamp)) >= strtotime($desactivacion->hora_inicio) && 
                        strtotime(date('H:i', $timestamp)) < strtotime($desactivacion->hora_fin)
                    ) {
                        if (Excepciones_desactivaciones_periodicas::where([['id_pista', $this->id], ['timestamp', $timestamp]])->first()) {
                            return false;
                        }
                        return 2;
                    }
                /* } */
            }
        }
        return false;
    }

    public function check_reserva_valida($timestamp)
    {
        if (
            strtotime(date('Y-m-d', $timestamp)) < strtotime(date('Y-m-d') . " +{$this->max_dias_antelacion} days") &&
            !$this->check_desactivado($timestamp) && 
            $this->reservas_por_tramo > count($this->get_reserva_activa_fecha_hora($timestamp)) && 
            new \DateTime(date('d-m-Y H:i', strtotime("+{$this->atenlacion_reserva} hours"))) < new \DateTime(date('d-m-Y H:i', strtotime(date('d-m-Y H:i', $timestamp) . " +{$this->get_minutos_given_timestamp($timestamp)} minutes" )))
            ) {
            return true;
        }
        return false;
    }

    public function reservas_permitidas_restantes($timestamp)
    {
        return $this->reservas_por_tramo - count($this->get_reserva_activa_fecha_hora($timestamp));
    }

    public function horario_tramos($fecha)
    {
        $fecha = new \DateTime($fecha);
        $horario=[];
        foreach ($this->horario_deserialized as $key => $item) {
            if (in_array($fecha->format('w'), $item['dias']) || ($fecha->format('w') == 0 && in_array(7, $item['dias']))) {
                foreach ($item['intervalo'] as $index => $intervalo) {
                    $a = new \DateTime($intervalo['hfin']);
                    $b = new \DateTime($intervalo['hinicio']);
                    $interval = $a->diff($b);
                    $dif = $interval->format('%h') * 60;
                    $dif += $interval->format('%i');
                    $dif = $dif / $intervalo['secuencia'];

                    $hora = new \DateTime($fecha->format('d-m-Y') . ' ' . $intervalo['hinicio']);

                    for ($i = 0; $i < $dif+1; $i++) {
                        
                        $string_hora = $hora->format('H:i') . ' - ' . $hora->modify("+{$intervalo['secuencia']} minutes")->format('H:i');
                        $timestamp = \Carbon\Carbon::parse($hora->format('d-m-Y H:i:s'))->subMinutes($intervalo['secuencia'])->timestamp;

                        $horario[$index][$i] = $timestamp;

                        if ($hora->format('H:i') == $intervalo['hfin']) {
                            break;
                        }
                    }
                }
            }
        }
        return $horario;
    }

    public function horario_con_reservas_por_dia($fecha)
    {
        $fecha = new \DateTime($fecha);
        $horario=[];
        foreach ($this->horario_deserialized as $key => $item) {
            if (in_array($fecha->format('w'), $item['dias']) || ($fecha->format('w') == 0 && in_array(7, $item['dias']))) {
                foreach ($item['intervalo'] as $index => $intervalo) {
                    $a = new \DateTime($intervalo['hfin']);
                    $b = new \DateTime($intervalo['hinicio']);
                    $interval = $a->diff($b);
                    $dif = $interval->format('%h') * 60;
                    $dif += $interval->format('%i');
                    $dif = $dif / $intervalo['secuencia'];

                    $hora = new \DateTime($fecha->format('d-m-Y') . ' ' . $intervalo['hinicio']);

                    for ($i = 0; $i < $dif+1; $i++) {
                        
                        $string_hora = $hora->format('H:i') . ' - ' . $hora->modify("+{$intervalo['secuencia']} minutes")->format('H:i');
                        $timestamp = \Carbon\Carbon::parse($hora->format('d-m-Y H:i:s'))->subMinutes($intervalo['secuencia'])->timestamp;

                        $horario[$index][$i]['reservado'] = $this->get_reserva_activa_fecha_hora($timestamp) ? true : false;
                        $horario[$index][$i]['string'] = $string_hora;
                        $horario[$index][$i]['height'] = str_replace(',', '.', $intervalo['secuencia'] / 10);
                        $horario[$index][$i]['tramos'] = 1;
                        $horario[$index][$i]['timestamp'] = $timestamp;
                        $horario[$index][$i]['num_res'] = count($this->get_reserva_activa_fecha_hora($timestamp));
                        $horario[$index][$i]['valida'] = $this->check_reserva_valida($timestamp);
                        $horario[$index][$i]['reunion'] = $this->id_instalacion == 2 ? ($this->get_reservas_fecha_hora($timestamp)[0] ?? null) : null;

                        if ($hora->format('H:i') == $intervalo['hfin']) {
                            break;
                        }
                    }
                }
            }
        }
        return $horario;
    }

    public function horario_con_reservas_por_dia_admin($fecha)
    {
        $fecha = new \DateTime($fecha);
        $horario=[];
        foreach ($this->horario_deserialized as $key => $item) {
            if (in_array($fecha->format('w'), $item['dias']) || ($fecha->format('w') == 0 && in_array(7, $item['dias']))) {
                foreach ($item['intervalo'] as $index => $intervalo) {
                    $a = new \DateTime($intervalo['hfin']);
                    $b = new \DateTime($intervalo['hinicio']);
                    $interval = $a->diff($b);
                    $dif = $interval->format('%h') * 60;
                    $dif += $interval->format('%i');
                    $dif = $dif / $intervalo['secuencia'];

                    $hora = new \DateTime($fecha->format('d-m-Y') . ' ' . $intervalo['hinicio']);

                    for ($i = 0; $i < $dif+1; $i++) {
                        
                        $string_hora = $hora->format('H:i') . ' - ' . $hora->modify("+{$intervalo['secuencia']} minutes")->format('H:i');
                        $timestamp = \Carbon\Carbon::parse($hora->format('d-m-Y H:i:s'))->subMinutes($intervalo['secuencia'])->timestamp;

						$all_reservas = $this->get_reservas_fecha_hora($timestamp);

                        $horario[$index][$i]['reservado'] = $all_reservas ? true : false;
                        $horario[$index][$i]['string'] = $string_hora;
                        $horario[$index][$i]['tramos'] = 1;
                        $horario[$index][$i]['reservas'] = $all_reservas;
                        $horario[$index][$i]['num_res'] = $this->get_reserva_activa_fecha_hora($timestamp)->count();
                        $horario[$index][$i]['timestamp'] = $timestamp;
                        $horario[$index][$i]['desactivado'] = $this->check_desactivado($timestamp);

                        if ($hora->format('H:i') == $intervalo['hfin']) {
                            break;
                        }
                    }
                }
            }
        }
        return $horario;
    }

    public function get_minutos_given_timestamp($timestamp)
    {
        $fecha = new \DateTime(date('Y-m-d H:i', $timestamp));

        foreach ($this->horario_deserialized as $key => $item) {
            if (in_array($fecha->format('w'), $item['dias']) || ($fecha->format('w') == 0 && in_array(7, $item['dias']))) {
                foreach ($item['intervalo'] as $index => $intervalo) {
                    if (strtotime(date('H:i', $timestamp)) >= strtotime($intervalo['hinicio']) && strtotime(date('H:i', $timestamp)) < strtotime($intervalo['hfin'])) {
                        return $intervalo['secuencia'];
                    }
                }
            }
        }
    }

    public function maximo_reservas_para_usuario(User $user)
    {
        return $this->reservas_por_tramo < $user->numero_reservas_perimitidas($this->tipo) ? ($this->reservas_permitidas_restantes(request()->timestamp) < $this->reservas_por_tramo ? $this->reservas_permitidas_restantes(request()->timestamp) : $this->reservas_por_tramo) : ($this->reservas_permitidas_restantes(request()->timestamp) < $user->numero_reservas_perimitidas($this->tipo) ?  $this->reservas_permitidas_restantes(request()->timestamp) : $user->numero_reservas_perimitidas($this->tipo));
    }

    public function reservas_given_date($date)
    {
        $reservas = Reserva::where([['id_pista', $this->id], ['fecha', $date], ['estado', '!=', 'canceled']])->orderByRaw('estado ASC')->get();
        return $reservas;
    }
    public function reservas_given_two_dates($date_inicio, $date_fin)
    {
        $reservas = Reserva::where([['id_pista', $this->id], ['fecha', '>=',  $date_inicio], ['fecha', '<=',  $date_fin], ['estado', '!=', 'canceled']])->orderByRaw('estado ASC')->get();
        return $reservas;
    }
}
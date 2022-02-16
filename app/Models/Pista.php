<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Reserva;
use App\Models\Instalacion;
use App\Models\Desactivacion_reserva;
use App\Models\Campos_personalizados;

class Pista extends Model
{
    use HasFactory;

    protected $table = 'pistas';

    protected $fillable = [
        'id_instalacion',
        'nombre',
        'tipo',
        'horario',
        'allow_cancel',
        'atenlacion_reserva',
        'allow_more_res',
        'reservas_por_tramo',
    ];

    protected $appends = ['horario_deserialized', 'all_campos_personalizados'];

    public function instalacion()
    {
        return $this->hasOne(Instalacion::class, 'id', 'id_instalacion');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id', 'id_pista');
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
        $reservas = Reserva::where([['id_pista', $this->id], ['fecha', date('Y-m-d', $timestamp)]])->orderByRaw("FIELD (estado, 'active', 'pasado', 'canceled') ASC")->get();

        $ret_reservas = [];
        foreach ($reservas as $key => $reserva) {
            if (in_array($timestamp, $reserva->horarios_deserialized)) {
                $reserva->usuario = User::find($reserva->id_usuario);
                $reserva->string_intervalo = date('H:i', $timestamp) . ' - ' . date('H:i', strtotime("+{$reserva->minutos_totales} minutes", strtotime(date('H:i', $timestamp))));
                array_push($ret_reservas, $reserva);
            }
        }

        return $ret_reservas;
    }

    public function get_reserva_activa_fecha_hora($timestamp)
    {
        $reservas = Reserva::where([['id_pista', $this->id], ['fecha', date('Y-m-d', $timestamp)]])->orderByRaw('estado ASC')->get();

        $ret_reservas = [];
        foreach ($reservas as $key => $reserva) {
            if (in_array($timestamp, $reserva->horarios_deserialized) && $reserva->estado != 'canceled') {
                $reserva->usuario = User::find($reserva->id_usuario);
                array_push($ret_reservas, $reserva);
            }
        }

        return $ret_reservas;
    }

    public function check_desactivado($timestamp)
    {
        if (Desactivacion_reserva::where([['id_pista', $this->id], ['timestamp', $timestamp]])->first()) {
            return true;
        }
        return false;
    }

    public function check_reserva_valida($timestamp)
    {
        if (
            !$this->check_desactivado($timestamp) && 
            $this->reservas_por_tramo > count($this->get_reserva_activa_fecha_hora($timestamp)) && 
            new \DateTime(date('d-m-Y H:i', strtotime("+{$this->atenlacion_reserva} hours"))) < new \DateTime(date('d-m-Y H:i', $timestamp))
            ) {
            return true;
        }
        return false;
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

                        $horario[$index][$i]['reservado'] = $this->get_reservas_fecha_hora($timestamp) ? true : false;
                        $horario[$index][$i]['string'] = $string_hora;
                        $horario[$index][$i]['tramos'] = 1;
                        $horario[$index][$i]['reservas'] = $this->get_reservas_fecha_hora($timestamp);
                        $horario[$index][$i]['num_res'] = count($this->get_reserva_activa_fecha_hora($timestamp));
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
}

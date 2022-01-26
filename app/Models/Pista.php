<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Reserva;
use App\Models\Instalacion;

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
    ];

    protected $appends = ['horario_deserialized'/* , 'string_horario' */];

    public function instalacion()
    {
        return $this->hasOne(Instalacion::class, 'id', 'id_instalacion');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'id', 'id_pista');
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

    public function horario_con_reservas_por_dia($fecha)
    {
        /* return Reserva::where('id_pista', $this->id)->where('fecha', $fecha)->get(); */
        $fecha = new \DateTime($fecha);
        foreach ($this->horario_deserialized as $key => $item) {
            if (in_array($fecha->format('w'), $item['dias']) || ($fecha->format('w') == 0 && in_array(7, $item['dias']))) {

            }
        }
    }
}

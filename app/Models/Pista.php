<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
        'antelacion_cancel',
        'atenlacion_reserva',
        'tiempo_limite_reserva',
    ];

    protected $appends = ['horario_deserialized'];

    public function getHorarioDeserializedAttribute() {
        return $this->horarioDeserializado();
    }

    public function horarioDeserializado() {
        $horario = unserialize($this->horario);
        return $horario;
    }
}

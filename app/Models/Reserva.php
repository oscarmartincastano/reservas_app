<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pista;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = [
        'id_pista',
        'id_usuario',
        'timestamp',
        'horarios',
        'tarifa',
        'fecha',
        'hora',
        'minutos_totales',
        'estado',
        'observaciones',
        'observaciones_admin',
    ];

    protected $appends = ['horarios_deserialized', 'formated_updated_at'];

    public function pista()
    {
        return $this->hasOne(Pista::class, 'id', 'id_pista');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_usuario');
    }

    public function getHorariosDeserializedAttribute() {
        return $this->horariosDeserializado();
    }

    public function horariosDeserializado() {
        $horarios = unserialize($this->horarios);
        return $horarios;
    }

    public function getFormatedUpdatedAtAttribute() {
        return $this->updated_at_formateado();
    }

    public function updated_at_formateado() {
        $date = date('d/m H:i', strtotime($this->updated_at));
        return $date;
    }
}

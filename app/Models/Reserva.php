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
        'tarifa',
        'fecha',
        'hora',
        'minutos_totales',
        'estado',
        'observaciones',
    ];

    public function pista()
    {
        return $this->hasOne(Pista::class, 'id', 'id_pista');
    }

}

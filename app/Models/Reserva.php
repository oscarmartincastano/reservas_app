<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pista;
use App\Models\Valor_campo_personalizado;

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
        'salida',
        'observaciones',
        'observaciones_admin',
        'reserva_periodica',
        'lista_espera',
        'creado_por',
    ];

    /* protected $appends = ['valores_campos_pers']; */

    public function pista()
    {
        return $this->hasOne(Pista::class, 'id', 'id_pista');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_usuario');
    }

    public function valores_campos_personalizados()
    {
        return $this->hasMany(Valor_campo_personalizado::class, 'id_reserva', 'id');
    }

    public function getValorNombreReunionAttribute() {
        return self::valores_campos_personalizados()->where('id_campo', 18)->first()->valor ?? '---';
    }

    public function getValorOrganizacionAttribute() {
        return self::valores_campos_personalizados()->where('id_campo', 14)->first()->valor ?? '---';
    }

    public function getValorPresupuestoAttribute() {
        return self::valores_campos_personalizados()->where('id_campo', 17)->first()->valor ?? '---';
    }

    public function getComentariosAttribute() {
        return self::valores_campos_personalizados()->where('id_campo', 21)->first()->valor ?? '---';
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

    public function getValoresCamposPersAttribute() {
        return $this->valores_campos_pers();
    }

    public function valores_campos_pers() {
        return $this->valores_campos_personalizados;
    }
}

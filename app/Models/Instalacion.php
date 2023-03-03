<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pista;
use App\Models\Configuracion;
use App\Models\Campos_personalizados;
use App\Models\Cobro;

class Instalacion extends Model
{
    use HasFactory;

    protected $table = 'instalaciones';

    protected $fillable = [
        'nombre',
        'direccion',
        'tlfno',
        'html_normas',
        'slug',
    ];

    protected $appends = ['deportes', 'user_admin', 'users_sin_validar'];

    public function users()
    {
        return $this->hasMany(User::class, 'id_instalacion')->withTrashed();
    }

    public function pistas()
    {
        return $this->hasMany(Pista::class, 'id_instalacion');
    }

    public function campos_personalizados()
    {
        return $this->hasMany(Campos_personalizados::class, 'id_instalacion');
    }

    public function configuracion()
    {
        return $this->hasOne(Configuracion::class, 'id_instalacion');
    }

    public function getUsersValidosAttribute()
    {
        return User::where('id_instalacion', $this->id)->whereNotNull('aprobado')->where('rol', 'user')->orderBy('name', 'asc')->get();
    }

    public function getDeportesAttribute()
    {
        return $this->deportes();
    }

    public function deportes()
    {
        $deportes = [];
        foreach ($this->pistas as $pista) {
            array_push($deportes, $pista->tipo);
        }
        return array_unique($deportes);
    }

    public function getUsersSinValidarAttribute()
    {
        return $this->users_sin_validar();
    }

    public function users_sin_validar()
    {
        return User::where([['id_instalacion', $this->id], ['aprobado', null]])->get();
    }

    public function getUserAdminAttribute()
    {
        return $this->user_admin();
    }

    public function user_admin()
    {
        return User::where([['id_instalacion', $this->id], ['rol', 'admin']])->first();
    }

    public function getCobrosAttribute()
    {
        return $this->cobros();
    }

    public function cobros()
    {
        return Cobro::whereIn('id_user', User::where('id_instalacion', $this->id)->pluck('id'))->get();
    }

    public function check_reservas_dia($fecha)
    {
        $reservas = Reserva::whereIn('id_pista', Pista::where('id_instalacion', $this->id)->pluck('id'))->where('fecha', $fecha)->where('estado', 'active')->get();

        return count($reservas) ? count($reservas) : false;
    }

    public function sponsors()
    {
        return $this->hasMany(Sponsor::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function banks()
    {
        return $this->hasMany(Bank::class);
    }

    public function serviceTypes()
    {
        return $this->hasMany(ServiceType::class);
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pista;
use App\Models\Configuracion;

class Instalacion extends Model
{
    use HasFactory;

    protected $table = 'instalaciones';

    protected $fillable = [
        'nombre',
        'direccion',
        'tlfno',
        'slug',
    ];

    protected $appends = ['deportes'];

    public function users()
    {
        return $this->hasMany(User::class, 'id_instalacion');
    }

    public function pistas()
    {
        return $this->hasMany(Pista::class, 'id_instalacion');
    }

    public function configuracion()
    {
        return $this->hasOne(Configuracion::class, 'id_instalacion');
    }

    public function getDeportesAttribute() {
        return $this->deportes();
    }

    public function deportes() {
        $deportes = [];
        foreach ($this->pistas as $pista) {
            array_push($deportes, $pista->tipo);
        }
        return array_unique($deportes);
    }
}

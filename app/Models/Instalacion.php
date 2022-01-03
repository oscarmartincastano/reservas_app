<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pista;

class Instalacion extends Model
{
    use HasFactory;

    protected $table = 'instalaciones';

    protected $fillable = [
        'nombre',
        'direccion',
        'tlfno',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_instalaciones', 'id_instalacion', 'id_user');
    }

    public function pistas()
    {
        return $this->hasMany(Pista::class, 'id_instalacion');
    }
}

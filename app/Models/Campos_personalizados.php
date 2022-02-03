<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Instalacion;

class Campos_personalizados extends Model
{
    use HasFactory;

    protected $table = 'campos_personalizados';

    protected $fillable = [
        'id_instalacion',
        'tipo',
        'label',
        'required',
        'opciones',
    ];

    public function instalacion()
    {
        return $this->hasOne(Instalacion::class, 'id', 'id_instalacion');
    }
}

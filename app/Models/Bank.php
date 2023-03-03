<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'instalacion_id'
    ];

    public function instalacion()
    {
        return $this->belongsTo(Instalacion::class);
    }
}
